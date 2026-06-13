<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalProducts = Product::count();
        $totalSales = Order::sum('total_amount');
        $totalCustomers = User::where('role', 'user')->count();
        
        // Last 7 days - sab dates show hongi, gap nahi hoga
        $dailyOrders = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Order::whereDate('created_at', $date)->count();
            $dailyOrders->push(['day' => $date, 'total' => $count]);
        }

        $monthlySales = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'totalProducts',
            'totalSales',
            'totalCustomers',
            'dailyOrders',
            'monthlySales'
        ));
    }
}
