<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_bayar';

    protected $fillable = [
        'tanggal_pesan',
        'jenis_lapangan',
        'jam_main',
        'lama_sewa',
        'jam_habis',
        'total',
        'id_pemesanan',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }
}
