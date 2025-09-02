<?php

namespace App\Http\Controllers;

use App\Quotation;
use App\Order;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of quotations
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isSeller()) {
            // Show quotation requests received by this seller
            $quotations = Quotation::with(['buyer', 'order.items.product'])
                                  ->where('seller_id', $user->id)
                                  ->when($request->status, function($query, $status) {
                                      return $query->where('status', $status);
                                  })
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(20);
        } else {
            // Show quotations sent to this buyer
            $quotations = Quotation::with(['seller', 'order.items.product'])
                                  ->where('buyer_id', $user->id)
                                  ->when($request->status, function($query, $status) {
                                      return $query->where('status', $status);
                                  })
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(20);
        }

        return view('quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new quotation
     */
    public function create(Order $order)
    {
        $this->authorize('createQuotation', $order);
        
        $order->load(['buyer', 'items.product']);
        
        return view('quotations.create', compact('order'));
    }

    /**
     * Store a newly created quotation
     */
    public function store(Request $request, Order $order)
    {
        $this->authorize('createQuotation', $order);

        $validated = $request->validate([
            'quoted_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'valid_until' => 'required|date|after:now',
        ]);

        $originalPrice = $order->total_amount;
        $discountPercentage = (($originalPrice - $validated['quoted_price']) / $originalPrice) * 100;

        $quotation = Quotation::create([
            'quotation_number' => Quotation::generateQuotationNumber(),
            'order_id' => $order->id,
            'buyer_id' => $order->user_id,
            'seller_id' => auth()->id(),
            'quoted_price' => $validated['quoted_price'],
            'original_price' => $originalPrice,
            'discount_percentage' => max(0, $discountPercentage),
            'notes' => $validated['notes'],
            'valid_until' => $validated['valid_until'],
        ]);

        return redirect()->route('quotations.show', $quotation)
                        ->with('success', 'Quotation sent successfully!');
    }

    /**
     * Display the specified quotation
     */
    public function show(Quotation $quotation)
    {
        $this->authorize('view', $quotation);
        
        $quotation->load(['buyer', 'seller', 'order.items.product']);
        
        return view('quotations.show', compact('quotation'));
    }

    /**
     * Accept a quotation
     */
    public function accept(Quotation $quotation)
    {
        $this->authorize('respond', $quotation);

        if ($quotation->isExpired()) {
            return back()->withErrors(['error' => 'This quotation has expired.']);
        }

        if ($quotation->status !== Quotation::STATUS_PENDING) {
            return back()->withErrors(['error' => 'This quotation has already been responded to.']);
        }

        $quotation->update([
            'status' => Quotation::STATUS_ACCEPTED,
            'responded_at' => now(),
        ]);

        // Update the order with the new price
        $quotation->order->update([
            'total_amount' => $quotation->quoted_price,
            'status' => Order::STATUS_CONFIRMED,
        ]);

        return back()->with('success', 'Quotation accepted successfully!');
    }

    /**
     * Reject a quotation
     */
    public function reject(Request $request, Quotation $quotation)
    {
        $this->authorize('respond', $quotation);

        if ($quotation->status !== Quotation::STATUS_PENDING) {
            return back()->withErrors(['error' => 'This quotation has already been responded to.']);
        }

        $quotation->update([
            'status' => Quotation::STATUS_REJECTED,
            'responded_at' => now(),
            'notes' => $quotation->notes . "\n\nRejection reason: " . $request->rejection_reason,
        ]);

        return back()->with('success', 'Quotation rejected.');
    }
}
