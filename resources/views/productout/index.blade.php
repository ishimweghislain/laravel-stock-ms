@extends('layouts.app')

@section('title', 'Product Stock Out Entries')

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
    .warning-message{
        background-color: red;
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
        <h1>Product Stock Out Entries</h1>
        <a href="{{ route('productout.create') }}" class="btn btn-add">Record New Stock Out</a>
    </div>
    @if (session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productOuts as $productOut)
                <tr>
                    <td>{{ $productOut->product->pname }}</td>
                    <td>{{ $productOut->date->format('Y-m-d') }}</td>
                    <td>{{ $productOut->quantity }}</td>
                    <td>${{ number_format($productOut->unit_price, 2) }}</td>
                    <td>${{ number_format($productOut->total_price, 2) }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('productout.edit', $productOut->outid) }}" class="btn btn-edit">Edit</a>
                        <form action="{{ route('productout.destroy', $productOut->outid) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this stock out entry?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $productOuts->links() }}
        </div>
    </div>
</div>
@endsection