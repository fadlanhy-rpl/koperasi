<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika menggunakan Sanctum untuk API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Tambahkan HasApiTokens jika perlu

    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_anggota',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis hash password
    ];

    // Relasi Simpanan
    public function simpananPokoks()
    {
        return $this->hasMany(SimpananPokok::class);
    }

    public function simpananWajibs()
    {
        return $this->hasMany(SimpananWajib::class);
    }

    public function simpananSukarelas()
    {
        return $this->hasMany(SimpananSukarela::class);
    }

    // Relasi Pembelian
    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    // Relasi Cicilan (jika cicilan bisa dibayar oleh user lain selain pembeli)
    // public function cicilans()
    // {
    //     return $this->hasMany(Cicilan::class);
    // }

    // Helper untuk cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPengurus()
    {
        return $this->role === 'pengurus';
    }

    public function isAnggota()
    {
        return $this->role === 'anggota';
    }
}