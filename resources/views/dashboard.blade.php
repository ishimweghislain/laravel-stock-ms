@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .container {
        width: 90%;
        margin: 20px auto;
        padding: 20px;
    }
    .header {
        margin-bottom: 20px;
    }
    .stats-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        gap: 20px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        flex: 1;
        text-align: center;
    }
    .stat-card h3 {
        margin: 0 0 10px;
        font-size: 1.2em;
        color: #333;
    }
    .stat-card p {
        margin: 0;
        font-size: 1.5em;
        font-weight: bold;
        color: #007bff;
    }
    .table-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
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
    .section-header {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    .section-header h2 {
        margin: 0;
    }
    .low-stock {
        color: #dc3545;
        font-weight: bold;
    }
    .search-filter {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .search-filter input {
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    .search-filter button {
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .search-filter button:hover {
        background-color: #0069d9;
    }
</style>

<div class="container">
    <div class="header">
        <h1>Inventory Dashboard</h1>
    </div>

    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Products</h3>
            <p>{{ $totalProducts }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Stock</h3>
            <p>{{ number_format($totalStock, 0) }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Money</h3>
            <p>${{ number_format($totalMoney, 2) }}</p>
        </div>
    </div>

    <div class="table-container">
        <div class="section-header">
            <h2>Filter by Date</h2>
        </div>
        <div class="search-filter">
            <form action="{{ route('dashboard') }}" method="GET" style="display: flex; gap: 10px; width: 100%;">
                <input type="date" name="start_date" class="form-input" value="{{ $startDate ?? '' }}">
                <input type="date" name="end_date" class="form-input" value="{{ $endDate ?? '' }}">
                <button type="submit" class="btn">Filter</button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="section-header">
            <h2>Product Stock</h2>
        </div>
        <table id="productTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Unit</th>
                    <th>Current Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productStock as $product)
                <tr class="{{ $product->current_stock <= 5 ? 'low-stock' : '' }}">
                    <td>{{ $product->pname }}</td>
                    <td>{{ $product->unit }}</td>
                    <td>{{ number_format($product->current_stock, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">No products found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <div class="section-header">
            <h2>Recent Stock In</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentProductIn as $productIn)
                <tr>
                    <td>{{ $productIn->product->pname }}</td>
                    <td>{{ $productIn->date->format('Y-m-d') }}</td>
                    <td>{{ $productIn->quantity }}</td>
                    <td>${{ number_format($productIn->unit_price, 2) }}</td>
                    <td>${{ number_format($productIn->total_price, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No recent stock in entries</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <div class="section-header">
            <h2>Recent Stock Out</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentProductOut as $productOut)
                <tr>
                    <td>{{ $productOut->product->pname }}</td>
                    <td>{{ $productOut->date->format('Y-m-d') }}</td>
                    <td>{{ $productOut->quantity }}</td>
                    <td>${{ number_format($productOut->unit_price, 2) }}</td>
                    <td>${{ number_format($productOut->total_price, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No recent stock out entries</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection