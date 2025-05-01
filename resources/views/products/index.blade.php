@extends('layouts.app')

@section('title', 'Products')

@section('content')
<style>
    .container {
        width: 90%;
        margin: 20px auto;
        padding: 20px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        color: white;
    }
    .btn-add {
        background-color: #28a745;
    }
    .btn-edit {
        background-color: #007bff;
    }
    .btn-delete {
        background-color: #dc3545;
    }
    .table-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f8f9fa;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>

<div class="container">
    <div class="header">
        <h1>Product Records</h1>
        <a href="{{ route('products.create') }}" class="btn btn-add">Add New Product</a>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Unit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->pname }}</td>
                    <td>{{ $product->unit }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('products.edit', $product->productid) }}" class="btn btn-edit">Edit</a>
                        <form action="{{ route('products.destroy', $product->productid) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection