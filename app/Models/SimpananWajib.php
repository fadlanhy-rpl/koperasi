<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananWajib extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah',
        'tanggal_bayar',
        'bulan',
        'tahun',
        'pengurus_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
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