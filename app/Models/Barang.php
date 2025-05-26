<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_usaha_id',
        'kode_barang',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'deskripsi',
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