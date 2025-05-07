<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductIn;

class ProductInController extends Controller
{
    public function index()
    {
        $productIns = ProductIn::with('product')
            ->latest('date')
            ->paginate(10);
        return view('productin.index', compact('productIns'));
    }

    public function create()
    {
        $products = Product::all();
        return view('productin.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'productid' => 'required|exists:products,productid',
            'date' => 'required|date:before_or_equal:today',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        
        // Calculate total price
        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];
        
        // Create new record with validated data
        ProductIn::create($validated);
        
        return redirect()->route('productin.index')
            ->with('success', 'Product stock added successfully');
    }

    public function edit(ProductIn $productin)
    {
        $products = Product::all();
        return view('productin.edit', compact('productin', 'products'));
    }

    public function update(Request $request, ProductIn $productin)
    {
        $validated = $request->validate([
            'productid' => 'required|exists:products,productid',
            'date' => 'required|date|before_or_equal:today',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        
        // Calculate total price
        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];
        
        // Update the record
        $productin->update($validated);
        
        return redirect()->route('productin.index')
            ->with('success', 'Product stock updated successfully');
    }

    public function destroy(ProductIn $productin)
    {
        $productin->delete();

        return redirect()->route('productin.index')
            ->with('success', 'Product stock entry deleted successfully');
    }
}