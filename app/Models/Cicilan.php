<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cicilan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'pengurus_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function pengurus() // Pengurus yang mencatat
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }

    // Opsional: untuk mendapatkan user yg melakukan cicilan (pemilik pembelian)
    public function anggota()
    {
        return $this->pembelian->user();
    }
}
