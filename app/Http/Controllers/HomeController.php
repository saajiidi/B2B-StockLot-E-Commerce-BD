<?php

namespace App\Http\Controllers;

use App\Product;
use App\Order;
use App\Category;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        // Dashboard statistics
        $stats = [];
        
        if ($user->isSeller()) {
            $stats = [
                'total_products' => $user->products()->count(),
                'active_products' => $user->products()->active()->count(),
                'total_orders' => $user->receivedOrders()->count(),
                'pending_orders' => $user->receivedOrders()->byStatus('pending')->count(),
                'total_revenue' => $user->receivedOrders()->byPaymentStatus('paid')->sum('total_amount'),
            ];
            
            $recentOrders = $user->receivedOrders()
                                ->with(['buyer', 'items'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                                
            $lowStockProducts = $user->products()
                                   ->where('stock_quantity', '<=', 10)
                                   ->where('stock_quantity', '>', 0)
                                   ->limit(5)
                                   ->get();
        } else {
            $stats = [
                'total_orders' => $user->orders()->count(),
                'pending_orders' => $user->orders()->byStatus('pending')->count(),
                'completed_orders' => $user->orders()->byStatus('delivered')->count(),
                'total_spent' => $user->orders()->byPaymentStatus('paid')->sum('total_amount'),
            ];
            
            $recentOrders = $user->orders()
                               ->with(['seller', 'items'])
                               ->orderBy('created_at', 'desc')
                               ->limit(5)
                               ->get();
                               
            $lowStockProducts = collect();
        }

        // Featured products and categories for all users
        $featuredProducts = Product::active()
                                  ->inStock()
                                  ->orderBy('created_at', 'desc')
                                  ->limit(8)
                                  ->get();
                                  
        $categories = Category::active()
                            ->root()
                            ->withCount('products')
                            ->orderBy('products_count', 'desc')
                            ->limit(6)
                            ->get();

        return view('home', compact('stats', 'recentOrders', 'lowStockProducts', 'featuredProducts', 'categories'));
    }

    /**
     * Show seller dashboard
     */
    public function sellerDashboard()
    {
        $user = auth()->user();
        
        if (!$user->isSeller()) {
            abort(403, 'Access denied. Seller account required.');
        }

        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'orders' => $user->receivedOrders()
                               ->whereYear('created_at', $date->year)
                               ->whereMonth('created_at', $date->month)
                               ->count(),
                'revenue' => $user->receivedOrders()
                                ->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->byPaymentStatus('paid')
                                ->sum('total_amount'),
            ];
        }

        $topProducts = $user->products()
                          ->withCount('orderItems')
                          ->orderBy('order_items_count', 'desc')
                          ->limit(10)
                          ->get();

        return view('seller.dashboard', compact('monthlyStats', 'topProducts'));
    }

    /**
     * Show buyer dashboard  
     */
    public function buyerDashboard()
    {
        $user = auth()->user();
        
        if (!$user->isBuyer()) {
            abort(403, 'Access denied. Buyer account required.');
        }

        $monthlySpending = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlySpending[] = [
                'month' => $date->format('M Y'),
                'amount' => $user->orders()
                               ->whereYear('created_at', $date->year)
                               ->whereMonth('created_at', $date->month)
                               ->byPaymentStatus('paid')
                               ->sum('total_amount'),
            ];
        }

        $favoriteCategories = Category::whereHas('products.orderItems.order', function($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                })
                                ->withCount(['products as orders_count' => function($query) use ($user) {
                                    $query->join('order_items', 'products.id', '=', 'order_items.product_id')
                                          ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                          ->where('orders.user_id', $user->id);
                                }])
                                ->orderBy('orders_count', 'desc')
                                ->limit(5)
                                ->get();

        return view('buyer.dashboard', compact('monthlySpending', 'favoriteCategories'));
    }

    /**
     * Show users management (admin function)
     */
    public function myUsers()
    {
        $user = auth()->user();
        
        // Simple admin check - you might want to implement proper roles
        if (!$user->is_verified) {
            abort(403, 'Access denied.');
        }

        $users = User::with(['products', 'orders'])
                    ->withCount(['products', 'orders'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('admin.users', compact('users'));
    }
}
