<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
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
        'cost',
    ];

    public function priceType()
    {
        return $this->belongsTo(PriceType::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}