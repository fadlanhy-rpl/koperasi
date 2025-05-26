<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pembelian',
        'user_id',
        'kasir_id',
        'tanggal_pembelian',
        'total_harga',
        'total_bayar',
        'kembalian',
        'status_pembayaran',
        'metode_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'datetime',
    ];

    public function user() // Pembeli
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kasir() // Kasir
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class);
    }

    public function cicilans()
    {
        return $this->hasMany(Cicilan::class);
    }
}
