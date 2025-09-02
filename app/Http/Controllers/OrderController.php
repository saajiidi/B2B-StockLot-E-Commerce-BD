<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isSeller()) {
            // Show orders received by this seller
            $orders = Order::with(['buyer', 'items.product'])
                          ->where('seller_id', $user->id)
                          ->when($request->status, function($query, $status) {
                              return $query->byStatus($status);
                          })
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);
        } else {
            // Show orders placed by this buyer
            $orders = Order::with(['seller', 'items.product'])
                          ->where('user_id', $user->id)
                          ->when($request->status, function($query, $status) {
                              return $query->byStatus($status);
                          })
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create(Request $request)
    {
        $productIds = $request->products ?? [];
        $products = Product::whereIn('id', $productIds)->with('seller')->get();
        
        return view('orders.create', compact('products'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|array',
            'billing_address' => 'required|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Group items by seller
        $itemsBySeller = collect($validated['items'])->groupBy(function($item) {
            return Product::find($item['product_id'])->user_id;
        });

        $orders = [];

        foreach ($itemsBySeller as $sellerId => $items) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                
                // Check stock availability
                if ($product->stock_quantity < $item['quantity']) {
                    return back()->withErrors([
                        'stock' => "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}"
                    ]);
                }

                // Check minimum order quantity
                if ($item['quantity'] < $product->min_order_quantity) {
                    return back()->withErrors([
                        'min_qty' => "Minimum order quantity for {$product->name} is {$product->min_order_quantity}"
                    ]);
                }

                $unitPrice = $product->getBulkPrice($item['quantity']);
                $totalPrice = $unitPrice * $item['quantity'];
                $subtotal += $totalPrice;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                ];
            }

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'seller_id' => $sellerId,
                'subtotal' => $subtotal,
                'total_amount' => $subtotal, // Add tax/shipping calculation later
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);
                
                // Update product stock
                $product = Product::find($orderItem['product_id']);
                $product->decrement('stock_quantity', $orderItem['quantity']);
            }

            $orders[] = $order;
        }

        if (count($orders) === 1) {
            return redirect()->route('orders.show', $orders[0])
                           ->with('success', 'Order placed successfully!');
        } else {
            return redirect()->route('orders.index')
                           ->with('success', count($orders) . ' orders placed successfully!');
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['buyer', 'seller', 'items.product', 'quotations']);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $order->update([
            'status' => $validated['status'],
            'shipped_at' => $validated['status'] === 'shipped' ? now() : $order->shipped_at,
            'delivered_at' => $validated['status'] === 'delivered' ? now() : $order->delivered_at,
        ]);

        // If cancelled, restore stock
        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        if (!$order->canBeCancelled()) {
            return back()->withErrors(['error' => 'This order cannot be cancelled.']);
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        return back()->with('success', 'Order cancelled successfully!');
    }
}
