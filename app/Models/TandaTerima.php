<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TandaTerima extends Model
{
    protected $table = 'tanda_terima';

    protected $fillable = [
        'nomor_tanda_terima',
        'nama_penyetor',
        'no_hp',
        'keterangan',
        'tanda_tangan',
        'petugas_id',
        'status',
        'alasan_status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public const STATUS_DITERIMA = 'Diterima';
    public const STATUS_DIPROSES = 'Diproses';
    public const STATUS_SELESAI = 'Selesai';
    public const STATUS_DIKEMBALIKAN = 'Dikembalikan';
    public const STATUS_DIBATALKAN = 'Dibatalkan';

    public const STATUS_LIST = [
        self::STATUS_DITERIMA,
        self::STATUS_DIPROSES,
        self::STATUS_SELESAI,
        self::STATUS_DIKEMBALIKAN,
        self::STATUS_DIBATALKAN,
    ];

    public function berkas(): HasMany
    {
        return $this->hasMany(TandaTerimaBerkas::class, 'tanda_terima_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(TandaTerimaStatusHistory::class, 'tanda_terima_id')->orderBy('created_at', 'asc');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Format tanggal setor resmi: "Rabu, 18 November 2026"
     */
    public function getFormattedTanggalAttribute(): string
    {
        if (!$this->submitted_at) {
            return '-';
        }
        return Carbon::parse($this->submitted_at)->timezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY');
    }

    /**
     * Format waktu setor resmi: "10:47:35 WIB"
     */
    public function getFormattedWaktuAttribute(): string
    {
        if (!$this->submitted_at) {
            return '-';
        }
        return Carbon::parse($this->submitted_at)->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB';
    }

    /**
     * Format ringkas tanggal & waktu untuk tabel
     */
    public function getFormattedShortDateAttribute(): string
    {
        if (!$this->submitted_at) {
            return '-';
        }
        return Carbon::parse($this->submitted_at)->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY');
    }

    public function getFormattedShortTimeAttribute(): string
    {
        if (!$this->submitted_at) {
            return '-';
        }
        return Carbon::parse($this->submitted_at)->timezone('Asia/Jakarta')->format('H:i') . ' WIB';
    }

    /**
     * Badge visual status styling
     */
    public function getStatusBadgeClassesAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_DITERIMA => [
                'bg' => 'bg-amber-50',
                'text' => 'text-amber-700',
                'border' => 'border-amber-200',
                'dot' => 'bg-amber-500',
                'icon' => 'fa-inbox',
            ],
            self::STATUS_DIPROSES => [
                'bg' => 'bg-sky-50',
                'text' => 'text-sky-700',
                'border' => 'border-sky-200',
                'dot' => 'bg-sky-500',
                'icon' => 'fa-spinner fa-spin',
            ],
            self::STATUS_SELESAI => [
                'bg' => 'bg-emerald-50',
                'text' => 'text-emerald-700',
                'border' => 'border-emerald-200',
                'dot' => 'bg-emerald-500',
                'icon' => 'fa-circle-check',
            ],
            self::STATUS_DIKEMBALIKAN => [
                'bg' => 'bg-purple-50',
                'text' => 'text-purple-700',
                'border' => 'border-purple-200',
                'dot' => 'bg-purple-500',
                'icon' => 'fa-arrow-rotate-left',
            ],
            self::STATUS_DIBATALKAN => [
                'bg' => 'bg-rose-50',
                'text' => 'text-rose-700',
                'border' => 'border-rose-200',
                'dot' => 'bg-rose-500',
                'icon' => 'fa-ban',
            ],
            default => [
                'bg' => 'bg-slate-50',
                'text' => 'text-slate-700',
                'border' => 'border-slate-200',
                'dot' => 'bg-slate-500',
                'icon' => 'fa-circle-question',
            ],
        };
    }

    /**
     * Generate nomor tanda terima otomatis dan unik: TTB-YYYYMMDD-XXXX
     */
    public static function generateNomorTandaTerima(?Carbon $dateTime = null): string
    {
        $date = $dateTime ?: Carbon::now('Asia/Jakarta');
        $datePrefix = 'TTB-' . $date->format('Ymd') . '-';

        // Cari nomor terakhir pada tanggal tersebut
        $lastRecord = self::where('nomor_tanda_terima', 'like', $datePrefix . '%')
            ->orderBy('nomor_tanda_terima', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNumberStr = substr($lastRecord->nomor_tanda_terima, strlen($datePrefix));
            $nextSequence = intval($lastNumberStr) + 1;
        } else {
            $nextSequence = 1;
        }

        $formattedSequence = str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
        $nomor = $datePrefix . $formattedSequence;

        // Double check uniqueness (in case of race conditions)
        while (self::where('nomor_tanda_terima', $nomor)->exists()) {
            $nextSequence++;
            $formattedSequence = str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
            $nomor = $datePrefix . $formattedSequence;
        }

        return $nomor;
    }
}
