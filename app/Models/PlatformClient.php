<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'platform',
        'platform_id',
        'name',
        'phone',
        'email'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
