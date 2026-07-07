<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $fillable = [
        'jenis',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima_kirim',
        'pengirim_penerima',
        'perihal',
        'file_surat',
    ];
}
