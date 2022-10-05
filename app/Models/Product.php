<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'expired_at',
        'platform',
        'platform_id'
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    /**
     * Скоуп для фильтрации продуктов пользователя
     *
     * @param $q
     * @param $id
     * @return void
     */
    public function scopeForUser($q, $id = null)
    {
        $q->whereHas('order', function ($qb) use ($id) {
            $qb->whereClientId(auth()->id() ?? $id);
        });
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function goods()
    {
        return $this->hasMany(ProductGood::class);
    }
}
