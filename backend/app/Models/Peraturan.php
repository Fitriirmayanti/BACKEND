<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    protected $table = 'peraturan';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'deskripsi',
        'tahun',
        'nomor',
        'file',
        'created_at',
        'updated_at',
    ];
}
