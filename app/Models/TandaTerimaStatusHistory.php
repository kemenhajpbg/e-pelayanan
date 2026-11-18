<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TandaTerimaStatusHistory extends Model
{
    protected $table = 'tanda_terima_status_history';

    protected $fillable = [
        'tanda_terima_id',
        'petugas_id',
        'status',
        'keterangan',
    ];

    public function tandaTerima(): BelongsTo
    {
        return $this->belongsTo(TandaTerima::class, 'tanda_terima_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM Y');
    }

    public function getFormattedTimeAttribute(): string
    {
        return Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->format('H:i');
    }
}
