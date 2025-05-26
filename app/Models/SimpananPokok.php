<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananPokok extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah',
        'tanggal_bayar',
        'pengurus_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function user() // Anggota
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengurus() // Pengurus yg mencatat
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }
}