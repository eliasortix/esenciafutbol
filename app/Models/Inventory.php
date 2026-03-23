<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    // Añadimos TODOS los campos nuevos aquí para que dejen de salir a 0
    protected $fillable = [
        'product_id', 
        'supplier_product_name', 
        'cost_price', 
        'size', 
        'is_sold',
        'patches_qty',
        'patches_description',
        'has_dorsal',
        'dorsal_name',
        'dorsal_number',
        'total_computed_cost'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}