<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- TAMBAHKAN IMPORT INI

class Barang extends Model
{
    use HasFactory, SoftDeletes; // <-- TAMBAHKAN SoftDeletes DI SINI

    protected $fillable = [
        'unit_usaha_id',
        'kode_barang',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'deskripsi',
        // 'deleted_at' tidak perlu di fillable, Laravel handle otomatis
    ];

    // Casts untuk memastikan deleted_at adalah instance Carbon
    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'deleted_at' => 'datetime', // <-- TAMBAHKAN CASTING UNTUK deleted_at
    ];

    public function unitUsaha()
    {
        return $this->belongsTo(UnitUsaha::class);
    }

    public function historiStoks()
    {
        return $this->hasMany(HistoriStok::class);
    }

    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}