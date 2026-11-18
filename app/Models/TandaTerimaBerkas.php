<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TandaTerimaBerkas extends Model
{
    protected $table = 'tanda_terima_berkas';

    protected $fillable = [
        'tanda_terima_id',
        'jenis_berkas',
    ];

    public const JENIS_BERKAS_LIST = [
        'Berkas Pelimpahan',
        'Berkas Pembatalan',
        'Paspor',
        'Berkas Perbaikan Data Jemaah',
        'Berkas Pengajuan Penggabungan/Pendampingan',
        'Berkas Pengajuan Mutasi',
        'Berkas Keberangkatan Haji',
    ];

    public function tandaTerima(): BelongsTo
    {
        return $this->belongsTo(TandaTerima::class, 'tanda_terima_id');
    }
}
