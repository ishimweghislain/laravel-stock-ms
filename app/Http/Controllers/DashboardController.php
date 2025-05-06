<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductIn;
use App\Models\ProductOut;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate date range
        if ($startDate && $endDate && $startDate <= $endDate) {
            $dateFilter = true;
        } else {
            $startDate = null;
            $endDate = null;
            $dateFilter = false;
        }

        // Total products
        $totalProducts = Product::count();

        // Total stock
        $totalStock = ProductIn::sum('quantity') - ProductOut::sum('quantity');

        // Recent ProductIn entries (latest 5, filtered by date if valid)
        $recentProductInQuery = ProductIn::with('product')->latest();
        if ($dateFilter) {
            $recentProductInQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $recentProductIn = $recentProductInQuery->take(5)->get();

        // Recent ProductOut entries (latest 5, filtered by date if valid)
        $recentProductOutQuery = ProductOut::with('product')->latest();
        if ($dateFilter) {
            $recentProductOutQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $recentProductOut = $recentProductOutQuery->take(5)->get();

        // Product stock details
        $productStockQuery = Product::select(
            'products.productid',
            'products.pname',
            'products.unit',
            \DB::raw('COALESCE(SUM(pin.quantity), 0) as total_in'),
            \DB::raw('COALESCE(SUM(pout.quantity), 0) as total_out'),
            \DB::raw('COALESCE(SUM(pin.quantity), 0) - COALESCE(SUM(pout.quantity), 0) as current_stock')
        )
        ->leftJoin('productin as pin', 'products.productid', '=', 'pin.productid')
        ->leftJoin('productout as pout', 'products.productid', '=', 'pout.productid')
        ->groupBy('products.productid', 'products.pname', 'products.unit')
        ->orderBy('products.pname');

        if ($dateFilter) {
            $productStockQuery->whereBetween('pin.date', [$startDate, $endDate])
                             ->whereBetween('pout.date', [$startDate, $endDate]);
        }

        $productStock = $productStockQuery->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'recentProductIn',
            'recentProductOut',
            'productStock',
            'startDate',
            'endDate'
        ));
    }
}