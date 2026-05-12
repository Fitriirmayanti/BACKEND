<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class masyarakat extends Model
{
    protected $table = 'masyarakat';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'email',
        'nohp',
        'pesan',
        'negara',
        'perusahaan',
        'departement',
        'created_at',
        'updated_at',
    ];
}
