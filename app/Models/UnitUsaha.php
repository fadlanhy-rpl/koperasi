<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitUsaha extends Model
{
    use HasFactory;

    protected $fillable = ['nama_unit_usaha', 'deskripsi'];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    /**
     * Get all of the detail pembelians for the unit usaha through its barangs.
     */
    public function detailPembelians()
    {
        // UnitUsaha -> hasMany -> Barang -> hasMany -> DetailPembelian
        // Argumen pertama: Model tujuan akhir (DetailPembelian::class)
        // Argumen kedua: Model perantara (Barang::class)
        // Argumen ketiga (opsional): Foreign key di model perantara (barangs.unit_usaha_id)
        // Argumen keempat (opsional): Foreign key di model tujuan (detail_pembelians.barang_id)
        // Argumen kelima (opsional): Local key di model asal (unit_usahas.id)
        // Argumen keenam (opsional): Local key di model perantara (barangs.id)
        return $this->hasManyThrough(
            DetailPembelian::class, // Model tujuan
            Barang::class,          // Model perantara
            'unit_usaha_id',        // Foreign key pada tabel 'barangs' (merujuk ke unit_usahas.id)
            'barang_id',            // Foreign key pada tabel 'detail_pembelians' (merujuk ke barangs.id)
            'id',                   // Local key pada tabel 'unit_usahas' (unit_usahas.id)
            'id'                    // Local key pada tabel 'barangs' (barangs.id)
        );
    }
}