<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'tel',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Filter user by type
     *
     * @param Builder $query
     * @param varchar $value
     *
     * @return Builder
     */
    public function scopeName(Builder $query, $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeEmail(Builder $query, $email): Builder
    {
        return $query->where('email', 'like', '%' . $email . '%');
    }
    public function scopeTel(Builder $query, $tel): Builder
    {
        return $query->where('tel', 'like', '%' . $tel . '%');
    }
    public function scopeAddress(Builder $query, $address): Builder
    {
        return $query->where('address', 'like', '%' . $address . '%');
    }
}
