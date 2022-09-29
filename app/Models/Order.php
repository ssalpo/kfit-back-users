<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'product_id',
        'price',
        'status',
        'paid_at',
        'expired_at',
        'platform',
        'platform_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function scopeFilter($q, array $data)
    {
        $q->when(Arr::get($data, 'product'), function ($q, $id) {
            $q->whereProductId($id);
        });

        $q->when(Arr::get($data, 'client'), function ($q, $id) {
            $q->whereClientId($id);
        });

        $q->when(Arr::get($data, 'price'), function ($q, $price) {
            $q->wherePrice($price);
        });

        $q->when(Arr::get($data, 'status'), function ($q, $status) {
            $q->whereStatus($status);
        });

        $q->when(Arr::get($data, 'paid_at'), function ($q, $dates) {
            $q->whereBetween('paid_at', $dates);
        });

        $q->when(Arr::get($data, 'expired_at'), function ($q, $dates) {
            $q->whereBetween('expired_at', $dates);
        });

        $q->when(Arr::get($data, 'platformType'), function ($q, $platform) {
            $q->wherePlatform($platform);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
