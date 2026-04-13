<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KawasanKonservasi extends Model
{
    protected $table = 'kawasankonservasi';
    public $timestamps = true;

    protected $fillable = [
        'deskripsi',
        'luasKawasan',
        'jenisKawasan',
        'alamat',
        'kondisi',
        'status',
        'gambar',
        'created_at',
        'updated_at',
    ];
}
