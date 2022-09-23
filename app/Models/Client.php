<?php

namespace App\Models;

use App\Utils\Formatters\PhoneFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Client extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'avatar',
        'active',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function platformClients(): HasMany
    {
        return $this->hasMany(PlatformClient::class);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = PhoneFormatter::onlyNumbers($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($this->phone);
    }
}
