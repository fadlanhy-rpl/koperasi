<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_id',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $table = 'detail_pembelians'; // Eksplisit nama tabel

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}