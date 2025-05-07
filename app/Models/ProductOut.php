<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOut extends Model
{
    protected $table = 'productout';
    protected $primaryKey = 'outid';
    
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