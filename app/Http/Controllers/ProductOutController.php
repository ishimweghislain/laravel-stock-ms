<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductIn;
use App\Models\ProductOut;
use Illuminate\Http\Request;

class ProductOutController extends Controller
{
    public function index()
    {
        $productOuts = ProductOut::with('product')->orderBy('date', 'desc')->paginate(10);
        return view('productout.index', compact('productOuts'));
    }

    public function create()
    {
        $products = Product::whereHas('productIns', function ($query) {
            $query->selectRaw('productid, SUM(quantity) as total_in')
                  ->groupBy('productid')
                  ->havingRaw('total_in > COALESCE((SELECT SUM(quantity) FROM productout WHERE productout.productid = productin.productid), 0)');
        })->orderBy('pname')->get();
        return view('productout.create', compact('products'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'productid' => 'required|exists:products,productid',
        'date' => 'required|date',
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
    ]);

    // Check available stock
    $totalIn = ProductIn::where('productid', $validated['productid'])->sum('quantity');
    $totalOut = ProductOut::where('productid', $validated['productid'])->sum('quantity');
    $availableQuantity = $totalIn - $totalOut;

    if ($validated['quantity'] > $availableQuantity) {
        return back()->withErrors(['quantity' => 'Not enough stock available'])->withInput();
    }

    // Calculate average unit_price from ProductIn
    $averageInPrice = ProductIn::where('productid', $validated['productid'])
        ->avg('unit_price') ?? 0;

    // Check for potential loss
    if ($validated['unit_price'] < $averageInPrice) {
        $warning = sprintf(
            'Warning: The unit price (%.2f) is lower than the average purchase price (%.2f). This may result in a loss.',
            $validated['unit_price'],
            $averageInPrice
        );
        // Store the warning in session to display it after redirect
        session()->flash('warning', $warning);
    }

    $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

    ProductOut::create($validated);
    
    return redirect()->route('productout.index')->with('success', 'Product stock out recorded successfully');
}

    public function edit(ProductOut $productout)
    {
        $products = Product::orderBy('pname')->get();
        return view('productout.edit', compact('productout', 'products'));
    }

    public function update(Request $request, ProductOut $productout)
{
    $validated = $request->validate([
        'productid' => 'required|exists:products,productid',
        'date' => 'required|date',
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
    ]);

    // Check available stock
    $totalIn = ProductIn::where('productid', $validated['productid'])->sum('quantity');
    $totalOut = ProductOut::where('productid', $validated['productid'])
                         ->where('outid', '!=', $productout->outid)
                         ->sum('quantity');
    $availableQuantity = $totalIn - $totalOut;

    if ($validated['quantity'] > $availableQuantity) {
        return back()->withErrors(['quantity' => 'Not enough stock available'])->withInput();
    }

    // Calculate average unit_price from ProductIn
    $averageInPrice = ProductIn::where('productid', $validated['productid'])
        ->avg('unit_price') ?? 0;

    // Check for potential loss
    if ($validated['unit_price'] < $averageInPrice) {
        $warning = sprintf(
            'Warning: The unit price (%.2f) is lower than the average purchase price (%.2f). This may result in a loss.',
            $validated['unit_price'],
            $averageInPrice
        );
        // Store the warning in session to display it after redirect
        session()->flash('warning', $warning);
    }

    $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

    $productout->update($validated);
    
    return redirect()->route('productout.index')->with('success', 'Product stock out updated successfully');
}
    public function destroy(ProductOut $productout)
    {
        $productout->delete();
        
        return redirect()->route('productout.index')->with('success', 'Product stock out entry deleted successfully');
    }
}