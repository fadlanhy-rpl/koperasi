<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananSukarela extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipe_transaksi',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'tanggal_transaksi',
        'pengurus_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengurus()
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }
}
