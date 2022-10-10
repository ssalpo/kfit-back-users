<?php

namespace App\Models;

use App\Utils\Formatters\PhoneFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Client extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'phone_code',
        'email',
        'avatar',
        'active',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function scopeFilter($qb, $params)
    {
        $qb->when(Arr::get($params, 'query'), function ($qb, $query) {
            $qb->where('name', 'ILIKE', '%' . trim($query) . '%')
                ->orWhere('email', 'ILIKE', '%' . trim($query) . '%')
                ->orWhere('phone', 'ILIKE', '%' . trim($query) . '%');
        });

        $qb->when(Arr::get($params, 'platformType'), function ($qb, $platformType) {
            $qb->whereHas('platformClients', function ($qb) use ($platformType) {
                $qb->wherePlatform($platformType);
            });
        });
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = PhoneFormatter::onlyNumbers($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function platformClients(): HasMany
    {
        return $this->hasMany(PlatformClient::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Find the user instance for the given username and password.
     *
     * @param string $username
     * @param string $password
     * @return Client|null
     */
    public function findAndValidateForPassport(string $username, string $password)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $client = $this->where('email', $username)->first();

            return Hash::check($password, $client->password)
                ? $client
                : null;
        }

        return $this->where('phone', $username)
            ->where('phone_code', $password)
            ->first();
    }
}
