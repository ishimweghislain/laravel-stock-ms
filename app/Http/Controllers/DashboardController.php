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
        
        $totalProducts = Product::count();

       
        $totalStock = ProductIn::sum('quantity') - ProductOut::sum('quantity');

        
        $totalValue = DB::selectOne('
            SELECT SUM(net_quantity * avg_unit_price) as total_value
            FROM (
                SELECT 
                    pi.productid,
                    (SUM(pi.quantity) - COALESCE(SUM(po.quantity), 0)) as net_quantity,
                    AVG(pi.unit_price) as avg_unit_price
                FROM productin pi
                LEFT JOIN productout po ON pi.productid = po.productid
                GROUP BY pi.productid
                HAVING net_quantity > 0
            ) as stock
        ')->total_value ?? 0;

        $recentProductIn = ProductIn::with('product')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Recent ProductOut records
        $recentProductOut = ProductOut::with('product')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('dashboard', compact('totalProducts', 'totalStock', 'totalValue', 'recentProductIn', 'recentProductOut'));
    }
}