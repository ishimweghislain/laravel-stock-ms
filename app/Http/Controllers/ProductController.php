<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('pname')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pname' => 'required|string|max:255|unique:products',
            'unit' => 'required|string|max:50',
        ]);

        Product::create($validated);
        
        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'pname' => 'required|string|max:255|unique:products,pname,' . $product->productid . ',productid',
            'unit' => 'required|string|max:50',
        ]);

        $product->update($validated);
        
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->productIns()->delete();
        $product->productOuts()->delete();
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}