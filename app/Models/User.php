<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Support\Facades\Storage; // Tidak perlu jika URL dibuat di Blade
// use Illuminate\Database\Eloquent\Casts\Attribute; // Tidak perlu jika accessor foto dihapus

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'nomor_anggota', 'role', 'status', 
        'last_login_at', 'profile_photo_path','date_of_birth',
    ];

    protected $hidden = [ 'password', 'remember_token' ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'status' => 'string',
        'deleted_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    // HAPUS ATAU KOMENTARI ACCESSOR INI JIKA INGIN MENGIKUTI PENDEKATAN DARI PROYEK LIBRARY
    /*
    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['profile_photo_path']
                                ? Storage::disk('public')->url($attributes['profile_photo_path'])
                                : asset('img/placeholder_avatar.png'), 
        );
    }
    */

    public function getAgeAttribute(): ?int
    {
        if ($this->date_of_birth) {
            // Pastikan date_of_birth adalah instance Carbon karena casting
            return $this->date_of_birth->age; 
        }
        return null;
    }
    
    // ... (Relasi dan helper lain yang sudah ada) ...
    public function simpananPokoks() { return $this->hasMany(SimpananPokok::class); }
    public function simpananWajibs() { return $this->hasMany(SimpananWajib::class); }
    public function simpananSukarelas() { return $this->hasMany(SimpananSukarela::class); }
    public function pembelians() { return $this->hasMany(Pembelian::class); }
    public function isAdmin() { return $this->role === 'admin'; }
    public function isPengurus() { return $this->role === 'pengurus'; }
    public function isAnggota() { return $this->role === 'anggota'; }
}