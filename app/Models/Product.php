<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'team_id',
        'season_id',
        'sku',
        'name',
        'slug',
        'description',
        'status',
        'section_type',
        'season',
        'kit_type',
        'version_type',
        'price_type_id',
        'supplier_id',
        'supplier_product_name', // AÑADE ESTO
        'cost',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function seasonModel(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // Dentro de la clase Product
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    // Un pequeño "truco" para obtener el stock disponible rápido
    public function getStockAttribute()
    {
        return $this->inventories()->where('is_sold', false)->count();
    }
}