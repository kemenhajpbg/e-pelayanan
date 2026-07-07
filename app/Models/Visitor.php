<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'kelompok_usia',
        'keperluan',
        'tingkat_kepuasan',
        'foto_pelayanan',
    ];
}
