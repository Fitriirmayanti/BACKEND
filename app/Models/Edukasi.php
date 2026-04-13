<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edukasi extends Model
{
    protected $table = 'edukasi';
    public $timestamps = true;

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'foto',
        'kategori',
        'keygaleri',
        'created_at',
        'updated_at',
    ];
}
