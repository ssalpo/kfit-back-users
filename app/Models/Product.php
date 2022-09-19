<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'expired_at',
        'platform',
        'platform_id'
    ];

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
