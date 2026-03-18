<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = ['product_id', 'supplier_product_name', 'cost_price', 'size', 'is_sold'];

    // Relación: Cada unidad de inventario pertenece a un producto del catálogo
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}