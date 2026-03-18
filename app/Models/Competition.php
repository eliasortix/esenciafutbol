<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'active',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}