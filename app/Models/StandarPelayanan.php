<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarPelayanan extends Model
{
    protected $table = 'standar_pelayanan';

    protected $fillable = [
        'nama',
        'email',
        'nomor_hp',
        'judul',
        'pesan',
    ];
}