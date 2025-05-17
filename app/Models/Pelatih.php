<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pelatih extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'pelatih';

    protected $fillable = [
        'nama',
        'perguruan',
        'no_hp',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function kontingens()
    {
        return $this->hasMany(Kontingen::class);
    }
}