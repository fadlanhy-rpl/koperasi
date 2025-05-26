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
}