<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductIn;
use Illuminate\Http\Request;

class ProductInController extends Controller
{
    public function index()
    {
        $productIns = ProductIn::with('product')->orderBy('date', 'desc')->paginate(10);
        return view('productin.index', compact('productIns'));
    }

    public function create()
    {
        $products = Product::orderBy('pname')->get();
        return view('productin.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'productid' => 'required|exists:products,productid',
            'date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

        ProductIn::create($validated);
        
        return redirect()->route('productin.index')->with('success', 'Product stock added successfully');
    }

    public function edit(ProductIn $productin)
    {
        $products = Product::orderBy('pname')->get();
        return view('productin.edit', compact('productin', 'products'));
    }

    public function update(Request $request, ProductIn $productin)
{
    $validated = $request->validate([
        'productid' => 'required|exists:products,productid',
        'date' => 'required|date',
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
    ]);

    // Check if unit_price is being changed
    if ($validated['unit_price'] != $productin->unit_price) {
        return back()->withErrors(['unit_price' => 'Editing the unit price is not allowed to prevent system misuse.'])->withInput();
    }

    // Exclude unit_price from the update to ensure it doesn't change
    $updateData = [
        'productid' => $validated['productid'],
        'date' => $validated['date'],
        'quantity' => $validated['quantity'],
        'total_price' => $validated['quantity'] * $productin->unit_price, // Use original unit_price
    ];

    $productin->update($updateData);
    
    return redirect()->route('productin.index')->with('success', 'Product stock updated successfully');
}
    public function destroy(ProductIn $productin)
    {
        $productin->delete();
        
        return redirect()->route('productin.index')->with('success', 'Product stock entry deleted successfully');
    }
}