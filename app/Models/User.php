<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    public $timestamps=false;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function charges(){
        return $this->hasMany(ChargesHistory::class);
    }
    public function buys(){
        return $this->hasMany(BuyHistory::class);
    }
    public function balances(){
        return $this->hasMany(Balance::class);
    }
    public function phones(){
        return $this->hasMany(Phone::class);
    }
    public function chargesH(){
        return $this->hasMany(ChargeHistory::class);
    }
}
