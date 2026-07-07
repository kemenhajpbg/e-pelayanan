<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsReport extends Model
{
    protected $fillable = [
        'judul',
        'tanggal_tayang',
        'link_berita',
        'kategori',
        'narasi',
        'file_dokumen',
    ];
}
