<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangan';
    protected $primaryKey = 'id_lapangan';

    public $timestamps = false;

    protected $fillable = [
        'jenis',
        'harga',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_lapangan');
    }
}
