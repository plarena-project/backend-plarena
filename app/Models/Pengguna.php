<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'no_hp',
        'email',
        'password',
        'foto',
        'role',
    ];

    protected $hidden = ['password'];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_pengguna');
    }
}
