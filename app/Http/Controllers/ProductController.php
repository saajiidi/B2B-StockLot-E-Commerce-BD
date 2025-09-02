<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'seller'])
                       ->active()
                       ->inStock();

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stocklot
        if ($request->stocklot) {
            $query->stocklot();
        }

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        // Price range filter
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort options
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(20);
        $categories = Category::active()->root()->with('children')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::active()->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_order_quantity' => 'required|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'is_stocklot' => 'boolean',
            'bulk_price_1' => 'nullable|numeric|min:0',
            'bulk_qty_1' => 'nullable|integer|min:1',
            'bulk_price_2' => 'nullable|numeric|min:0',
            'bulk_qty_2' => 'nullable|integer|min:1',
            'bulk_price_3' => 'nullable|numeric|min:0',
            'bulk_qty_3' => 'nullable|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']) . '-' . time();
        $validated['sku'] = 'SKU-' . strtoupper(Str::random(8));

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)
                        ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'seller', 'reviews.user']);
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->active()
                                 ->inStock()
                                 ->limit(4)
                                 ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::active()->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_order_quantity' => 'required|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'is_stocklot' => 'boolean',
            'is_active' => 'boolean',
            'bulk_price_1' => 'nullable|numeric|min:0',
            'bulk_qty_1' => 'nullable|integer|min:1',
            'bulk_price_2' => 'nullable|numeric|min:0',
            'bulk_qty_2' => 'nullable|integer|min:1',
            'bulk_price_3' => 'nullable|numeric|min:0',
            'bulk_qty_3' => 'nullable|integer|min:1',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . $product->id;

        $product->update($validated);

        return redirect()->route('products.show', $product)
                        ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        $product->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Product deleted successfully!');
    }

    /**
     * Get bulk pricing for a product
     */
    public function getBulkPricing(Product $product, Request $request)
    {
        $quantity = $request->quantity;
        $price = $product->getBulkPrice($quantity);
        
        return response()->json([
            'price' => $price,
            'total' => $price * $quantity,
            'savings' => ($product->price - $price) * $quantity
        ]);
    }
}
