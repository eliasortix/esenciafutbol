<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

   protected $fillable = [
    'product_id',
    'product_name',
    'supplier_product_name',
    'cost_price',
    'is_available'
];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}