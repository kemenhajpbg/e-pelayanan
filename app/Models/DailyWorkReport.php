<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyWorkReport extends Model
{
    protected $fillable = [
        'user_id',
        'petugas',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'kegiatan',
        'kategori',
        'output_hasil',
        'volume',
        'satuan',
        'status',
        'keterangan',
        'file_dokumentasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'volume' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDaily($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeMonthly($query, $year, $month)
    {
        return $query->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
    }
}
