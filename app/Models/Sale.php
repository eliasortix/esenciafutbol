<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
    'inventory_id',
    'product_id',
    'supplier_product_name', // Asegúrate de que este sea el nombre exacto
    'cost_price',
    'sale_price',
    'seller_name',
    'seller_commission',
    'company_profit',
];

    /**
     * Relación: Una venta pertenece a un registro de inventario.
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Relación: Una venta puede pertenecer a un producto del catálogo.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}