<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
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
