<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';
    public $timestamps = true;

    protected $fillable = [
        'judul',
        'keterangan',
        'gambar',
        'keygaleri',
        'created_at',
        'updated_at',
    ];
}
