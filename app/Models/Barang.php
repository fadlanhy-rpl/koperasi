<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'gambar_path',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
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

    /**
     * Check if barang has image
     */
    public function hasImage()
    {
        return !empty($this->gambar_path) && Storage::disk('public')->exists($this->gambar_path);
    }

    /**
     * Get full path to image file
     */
    public function getImagePath()
    {
        if ($this->gambar_path) {
            return storage_path('app/public/' . $this->gambar_path);
        }
        return null;
    }
}