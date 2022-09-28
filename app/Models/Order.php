<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'price' => 'double',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
