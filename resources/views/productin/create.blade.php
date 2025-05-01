@extends('layouts.app')

@section('title', 'Add Product Stock')

@section('content')
<style>
    .container {
        width: 80%;
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
    }
    .form-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .card-header {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        color: white;
    }
    .btn-primary {
        background-color: #007bff;
    }
    .btn-secondary {
        background-color: #6c757d;
    }
    .button-group {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
</style>

<div class="container">
    <div class="form-card">
        <div class="card-header">
            <h2>Add New Product Stock</h2>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productin.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Product</label>
                <select name="productid" class="form-input" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->productid }}" {{ old('productid') == $product->productid ? 'selected' : '' }}>
                            {{ $product->pname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-input" value="{{ old('date') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-input" value="{{ old('quantity') }}" 
                       min="1" required>
            </div>

            <div class="form-group">
                <label class="form-label">Unit Price</label>
                <input type="number" name="unit_price" class="form-input" value="{{ old('unit_price') }}" 
                       min="0" step="0.01" required>
            </div>

            <div class="button-group">
                <a href="{{ route('productin.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Stock</button>
            </div>
        </form>
    </div>
</div>
@endsection