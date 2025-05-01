<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductIn extends Model
{
    protected $table = 'productin';
    protected $primaryKey = 'inid';
    
    protected $fillable = [
        'productid', 'date', 'quantity', 'unit_price', 'total_price'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productid');
    }
}