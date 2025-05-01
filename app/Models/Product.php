<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'productid';
    
    protected $fillable = [
        'pname', 'unit'
    ];

    public function productIns()
    {
        return $this->hasMany(ProductIn::class, 'productid');
    }

    public function productOuts()
    {
        return $this->hasMany(ProductOut::class, 'productid');
    }
}