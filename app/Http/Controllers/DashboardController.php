<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductIn;
use App\Models\ProductOut;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple count for total products
        $totalProducts = Product::count();
        
        // Simple calculation for total stock
        $totalStock = ProductIn::sum('quantity') - ProductOut::sum('quantity');
        
        // Calculate total inventory value using a simpler query
        $inventory = DB::table('productin as pi')
            ->select('pi.productid', 
                     DB::raw('SUM(pi.quantity) as total_in'), 
                     DB::raw('AVG(pi.unit_price) as avg_price'))
            ->groupBy('pi.productid')
            ->get();
            
        $outQuantities = DB::table('productout')
            ->select('productid', DB::raw('SUM(quantity) as total_out'))
            ->groupBy('productid')
            ->pluck('total_out', 'productid')
            ->toArray();
            
        $totalValue = 0;
        foreach ($inventory as $item) {
            $outQty = $outQuantities[$item->productid] ?? 0;
            $netQty = $item->total_in - $outQty;
            if ($netQty > 0) {
                $totalValue += $netQty * $item->avg_price;
            }
        }
        
        // Get recent product entries - simple with eager loading
        $recentProductIn = ProductIn::with('product')
            ->latest()
            ->take(5)
            ->get();
            
        // Get recent product exits - simple with eager loading
        $recentProductOut = ProductOut::with('product')
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'totalProducts', 
            'totalStock', 
            'totalValue', 
            'recentProductIn', 
            'recentProductOut'
        ));
    }
}