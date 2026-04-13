<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKonservasi extends Model
{
    protected $table = 'laporankonservasi';
    public $timestamps = true;

    protected $fillable = [
        'pengirim',
        'judulLaporan',
        'jenisKegiatan',
        'tanggalMulai',
        'tanggalSelesai',
        'keterangan',
        'daerahLokasi',
        'kabupaten',
        'kecamatan',
        'lokasiKegiatan',
        'latitude',
        'longitude',
        'suratTugas',
        'luasArea',
        'fotoSebelum',
        'fotoSetelah',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pengirim', 'id');
    }
}
