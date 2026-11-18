<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TandaTerima;
use App\Models\TandaTerimaBerkas;
use App\Models\TandaTerimaStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TandaTerimaController extends Controller
{
    /**
     * Halaman Riwayat Tanda Terima Berkas dengan Pencarian & Filter.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenisBerkas = $request->input('jenis_berkas');
        $status = $request->input('status');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');
        $tanggal = $request->input('tanggal');

        $query = TandaTerima::with(['berkas', 'petugas']);

        // 1. Filter Pencarian Teks
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_tanda_terima', 'like', "%{$search}%")
                  ->orWhere('nama_penyetor', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('berkas', function ($bq) use ($search) {
                      $bq->where('jenis_berkas', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Jenis Berkas
        if ($jenisBerkas) {
            $query->whereHas('berkas', function ($q) use ($jenisBerkas) {
                $q->where('jenis_berkas', $jenisBerkas);
            });
        }

        // 3. Filter Status
        if ($status) {
            $query->where('status', $status);
        }

        // 4. Filter Tanggal Spesifik
        if ($tanggal) {
            $query->whereDate('submitted_at', $tanggal);
        }

        // 5. Filter Rentang Tanggal
        if ($dariTanggal) {
            $query->whereDate('submitted_at', '>=', $dariTanggal);
        }
        if ($sampaiTanggal) {
            $query->whereDate('submitted_at', '<=', $sampaiTanggal);
        }

        $tandaTerimaList = $query->orderBy('submitted_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan Statistik untuk Card Atas
        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();
        $currentYear = Carbon::now('Asia/Jakarta')->year;
        $currentMonth = Carbon::now('Asia/Jakarta')->month;

        $stats = [
            'total' => TandaTerima::count(),
            'today' => TandaTerima::whereDate('submitted_at', $todayStr)->count(),
            'this_month' => TandaTerima::whereYear('submitted_at', $currentYear)->whereMonth('submitted_at', $currentMonth)->count(),
            'diterima' => TandaTerima::where('status', TandaTerima::STATUS_DITERIMA)->count(),
            'diproses' => TandaTerima::where('status', TandaTerima::STATUS_DIPROSES)->count(),
            'selesai' => TandaTerima::where('status', TandaTerima::STATUS_SELESAI)->count(),
            'dikembalikan' => TandaTerima::where('status', TandaTerima::STATUS_DIKEMBALIKAN)->count(),
            'dibatalkan' => TandaTerima::where('status', TandaTerima::STATUS_DIBATALKAN)->count(),
        ];

        $jenisBerkasList = TandaTerimaBerkas::JENIS_BERKAS_LIST;
        $statusList = TandaTerima::STATUS_LIST;

        return view('tanda_terima.index', compact(
            'tandaTerimaList',
            'stats',
            'jenisBerkasList',
            'statusList',
            'search',
            'jenisBerkas',
            'status',
            'dariTanggal',
            'sampaiTanggal',
            'tanggal'
        ));
    }

    /**
     * Halaman Buat Tanda Terima Baru.
     */
    public function create()
    {
        $jenisBerkasList = TandaTerimaBerkas::JENIS_BERKAS_LIST;
        $petugasName = Auth::user() ? Auth::user()->name : 'Petugas Pelayanan';
        $currentTimeWIB = Carbon::now('Asia/Jakarta');

        return view('tanda_terima.create', compact('jenisBerkasList', 'petugasName', 'currentTimeWIB'));
    }

    /**
     * Simpan Data Tanda Terima Baru & Generate Nomor Otomatis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penyetor' => 'required|string|max:255',
            'no_hp' => 'required|string|max:30',
            'jenis_berkas' => 'required|array|min:1',
            'jenis_berkas.*' => 'required|string|in:' . implode(',', TandaTerimaBerkas::JENIS_BERKAS_LIST),
            'keterangan' => 'nullable|string|max:1000',
            'tanda_tangan' => 'required|string',
        ], [
            'nama_penyetor.required' => 'Nama lengkap penyetor berkas wajib diisi.',
            'no_hp.required' => 'Nomor HP / WhatsApp penyetor wajib diisi.',
            'jenis_berkas.required' => 'Pilih minimal satu jenis berkas yang disetorkan.',
            'jenis_berkas.min' => 'Pilih minimal satu jenis berkas yang disetorkan.',
            'tanda_tangan.required' => 'Tanda tangan digital penyetor wajib digoreskan pada area tanda tangan.',
        ]);

        // Verifikasi tanda tangan tidak kosong
        if (trim($validated['tanda_tangan']) === '' || !str_starts_with($validated['tanda_tangan'], 'data:image/')) {
            return back()->withInput()->withErrors(['tanda_tangan' => 'Goresan tanda tangan digital penyetor wajib diisi.']);
        }

        // Waktu submitted_at otomatis dan permanen (Asia/Jakarta)
        $submittedAt = Carbon::now('Asia/Jakarta');

        $tandaTerima = null;

        DB::transaction(function () use ($validated, $submittedAt, &$tandaTerima) {
            // Generate nomor tanda terima unik: TTB-YYYYMMDD-XXXX
            $nomorTandaTerima = TandaTerima::generateNomorTandaTerima($submittedAt);

            $tandaTerima = TandaTerima::create([
                'nomor_tanda_terima' => $nomorTandaTerima,
                'nama_penyetor' => trim($validated['nama_penyetor']),
                'no_hp' => trim($validated['no_hp']),
                'keterangan' => $validated['keterangan'] ? trim($validated['keterangan']) : null,
                'tanda_tangan' => $validated['tanda_tangan'],
                'petugas_id' => Auth::id() ?? 1,
                'status' => TandaTerima::STATUS_DITERIMA,
                'alasan_status' => null,
                'submitted_at' => $submittedAt,
            ]);

            // Simpan jenis-jenis berkas ke tabel relasi tanda_terima_berkas
            foreach ($validated['jenis_berkas'] as $jenis) {
                $tandaTerima->berkas()->create([
                    'jenis_berkas' => $jenis,
                ]);
            }

            // Catat riwayat status perdana (Audit trail)
            $tandaTerima->statusHistories()->create([
                'petugas_id' => Auth::id() ?? 1,
                'status' => TandaTerima::STATUS_DITERIMA,
                'keterangan' => 'Berkas pertama kali diterima oleh petugas di loket pelayanan.',
            ]);
        });

        return redirect()->route('tanda-terima.show', ['id' => $tandaTerima->id, 'print' => 1])
            ->with('success', "Tanda terima berkas berhasil dibuat dengan nomor {$tandaTerima->nomor_tanda_terima}.");
    }

    /**
     * Halaman Detail Tanda Terima Berkas & Riwayat Status.
     */
    public function show($id)
    {
        $tandaTerima = TandaTerima::with(['berkas', 'petugas', 'statusHistories.petugas'])->findOrFail($id);
        $autoPrint = request()->boolean('print');
        $statusList = TandaTerima::STATUS_LIST;

        return view('tanda_terima.show', compact('tandaTerima', 'autoPrint', 'statusList'));
    }

    /**
     * Tampilan Cetak Thermal Printer 80 mm.
     */
    public function printThermal($id)
    {
        $tandaTerima = TandaTerima::with(['berkas', 'petugas'])->findOrFail($id);

        return view('tanda_terima.print_thermal', compact('tandaTerima'));
    }

    /**
     * Pembaruan Status Pelayanan Berkas & Perekaman Jejak Audit.
     */
    public function updateStatus(Request $request, $id)
    {
        $tandaTerima = TandaTerima::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', TandaTerima::STATUS_LIST),
            'alasan' => 'nullable|string|max:1000',
        ]);

        $newStatus = $validated['status'];
        $alasan = $validated['alasan'] ? trim($validated['alasan']) : null;

        // Validasi alasan jika status dikembalikan atau dibatalkan
        if (in_array($newStatus, [TandaTerima::STATUS_DIKEMBALIKAN, TandaTerima::STATUS_DIBATALKAN]) && empty($alasan)) {
            return back()->withErrors([
                'alasan' => "Alasan wajib diisi ketika memperbarui status menjadi '{$newStatus}'."
            ])->withInput();
        }

        DB::transaction(function () use ($tandaTerima, $newStatus, $alasan) {
            $tandaTerima->update([
                'status' => $newStatus,
                'alasan_status' => $alasan ?: $tandaTerima->alasan_status,
            ]);

            $catatanHistory = match ($newStatus) {
                TandaTerima::STATUS_DIKEMBALIKAN => "Berkas dikembalikan: {$alasan}",
                TandaTerima::STATUS_DIBATALKAN => "Tanda terima dibatalkan: {$alasan}",
                default => $alasan ?: "Status pelayanan diperbarui menjadi {$newStatus}."
            };

            $tandaTerima->statusHistories()->create([
                'petugas_id' => Auth::id() ?? 1,
                'status' => $newStatus,
                'keterangan' => $catatanHistory,
            ]);
        });

        return back()->with('success', "Status tanda terima {$tandaTerima->nomor_tanda_terima} berhasil diperbarui menjadi '{$newStatus}'.");
    }

    /**
     * Halaman Laporan & Statistik Terpadu (Harian, Bulanan, Tahunan, Rentang Tanggal).
     */
    public function laporan(Request $request)
    {
        $periode = $request->input('periode', 'bulanan'); // harian, bulanan, tahunan, rentang
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));
        $dariTanggal = $request->input('dari_tanggal', Carbon::now('Asia/Jakarta')->subDays(30)->toDateString());
        $sampaiTanggal = $request->input('sampai_tanggal', Carbon::now('Asia/Jakarta')->toDateString());
        $jenisBerkas = $request->input('jenis_berkas');
        $status = $request->input('status');

        $query = TandaTerima::with(['berkas', 'petugas']);

        // Terapkan filter berdasarkan periode
        if ($periode === 'harian') {
            $query->whereDate('submitted_at', $tanggal);
            $labelPeriode = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        } elseif ($periode === 'tahunan') {
            $query->whereYear('submitted_at', $tahun);
            $labelPeriode = "Tahun {$tahun}";
        } elseif ($periode === 'rentang') {
            $query->whereDate('submitted_at', '>=', $dariTanggal)
                  ->whereDate('submitted_at', '<=', $sampaiTanggal);
            $labelPeriode = Carbon::parse($dariTanggal)->locale('id')->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($sampaiTanggal)->locale('id')->isoFormat('D MMM YYYY');
        } else {
            // Default: Bulanan
            $query->whereYear('submitted_at', $tahun)->whereMonth('submitted_at', $bulan);
            $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM');
            $labelPeriode = "Bulan {$namaBulan} {$tahun}";
        }

        if ($jenisBerkas) {
            $query->whereHas('berkas', fn($q) => $q->where('jenis_berkas', $jenisBerkas));
        }

        if ($status) {
            $query->where('status', $status);
        }

        $allResults = (clone $query)->orderBy('submitted_at', 'asc')->get();
        $totalTransaksi = $allResults->count();

        // Statistik Status
        $statusCounts = [];
        foreach (TandaTerima::STATUS_LIST as $st) {
            $statusCounts[$st] = $allResults->where('status', $st)->count();
        }

        // Statistik Berkas
        $jenisBerkasList = TandaTerimaBerkas::JENIS_BERKAS_LIST;
        $jenisBerkasCounts = [];
        foreach ($jenisBerkasList as $jb) {
            $jenisBerkasCounts[$jb] = 0;
        }

        foreach ($allResults as $item) {
            foreach ($item->berkas as $b) {
                if (isset($jenisBerkasCounts[$b->jenis_berkas])) {
                    $jenisBerkasCounts[$b->jenis_berkas]++;
                }
            }
        }

        return view('tanda_terima.laporan', compact(
            'allResults',
            'totalTransaksi',
            'statusCounts',
            'jenisBerkasCounts',
            'jenisBerkasList',
            'periode',
            'tanggal',
            'bulan',
            'tahun',
            'dariTanggal',
            'sampaiTanggal',
            'jenisBerkas',
            'status',
            'labelPeriode'
        ));
    }

    /**
     * Ekspor Laporan ke Format Excel / CSV dengan UTF-8 BOM.
     */
    public function exportExcel(Request $request)
    {
        $periode = $request->input('periode', 'bulanan');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));
        $dariTanggal = $request->input('dari_tanggal', date('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', date('Y-m-d'));
        $jenisBerkas = $request->input('jenis_berkas');
        $status = $request->input('status');

        $query = TandaTerima::with(['berkas', 'petugas']);

        if ($periode === 'harian') {
            $query->whereDate('submitted_at', $tanggal);
            $suffix = "harian_{$tanggal}";
        } elseif ($periode === 'tahunan') {
            $query->whereYear('submitted_at', $tahun);
            $suffix = "tahunan_{$tahun}";
        } elseif ($periode === 'rentang') {
            $query->whereDate('submitted_at', '>=', $dariTanggal)
                  ->whereDate('submitted_at', '<=', $sampaiTanggal);
            $suffix = "rentang_{$dariTanggal}_sd_{$sampaiTanggal}";
        } else {
            $query->whereYear('submitted_at', $tahun)->whereMonth('submitted_at', $bulan);
            $suffix = "bulanan_{$tahun}_{$bulan}";
        }

        if ($jenisBerkas) {
            $query->whereHas('berkas', fn($q) => $q->where('jenis_berkas', $jenisBerkas));
        }

        if ($status) {
            $query->where('status', $status);
        }

        $records = $query->orderBy('submitted_at', 'asc')->get();

        $filename = "laporan_tanda_terima_berkas_{$suffix}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM untuk Microsoft Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No',
                'Nomor Tanda Terima',
                'Tanggal Setor',
                'Waktu Setor (WIB)',
                'Nama Penyetor',
                'Nomor HP',
                'Jenis Berkas Yang Disetorkan',
                'Status Pelayanan',
                'Keterangan / Alasan',
                'Petugas Pelayanan',
            ]);

            foreach ($records as $index => $row) {
                $berkasStr = $row->berkas->pluck('jenis_berkas')->implode('; ');
                $keteranganGabung = $row->keterangan;
                if ($row->alasan_status) {
                    $keteranganGabung .= ($keteranganGabung ? ' | Alasan: ' : 'Alasan: ') . $row->alasan_status;
                }

                fputcsv($file, [
                    $index + 1,
                    $row->nomor_tanda_terima,
                    $row->formatted_tanggal,
                    $row->formatted_waktu,
                    $row->nama_penyetor,
                    $row->no_hp,
                    $berkasStr,
                    $row->status,
                    $keteranganGabung ?: '-',
                    $row->petugas ? $row->petugas->name : 'Petugas Front Office',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Cetak Resmi Laporan / Ekspor PDF.
     */
    public function exportPdf(Request $request)
    {
        $periode = $request->input('periode', 'bulanan');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));
        $dariTanggal = $request->input('dari_tanggal', date('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', date('Y-m-d'));
        $jenisBerkas = $request->input('jenis_berkas');
        $status = $request->input('status');

        $query = TandaTerima::with(['berkas', 'petugas']);

        if ($periode === 'harian') {
            $query->whereDate('submitted_at', $tanggal);
            $labelPeriode = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        } elseif ($periode === 'tahunan') {
            $query->whereYear('submitted_at', $tahun);
            $labelPeriode = "Tahun {$tahun}";
        } elseif ($periode === 'rentang') {
            $query->whereDate('submitted_at', '>=', $dariTanggal)
                  ->whereDate('submitted_at', '<=', $sampaiTanggal);
            $labelPeriode = Carbon::parse($dariTanggal)->locale('id')->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($sampaiTanggal)->locale('id')->isoFormat('D MMM YYYY');
        } else {
            $query->whereYear('submitted_at', $tahun)->whereMonth('submitted_at', $bulan);
            $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM');
            $labelPeriode = "Bulan {$namaBulan} {$tahun}";
        }

        if ($jenisBerkas) {
            $query->whereHas('berkas', fn($q) => $q->where('jenis_berkas', $jenisBerkas));
        }

        if ($status) {
            $query->where('status', $status);
        }

        $records = $query->orderBy('submitted_at', 'asc')->get();

        $stats = [
            'total' => $records->count(),
            'diterima' => $records->where('status', TandaTerima::STATUS_DITERIMA)->count(),
            'diproses' => $records->where('status', TandaTerima::STATUS_DIPROSES)->count(),
            'selesai' => $records->where('status', TandaTerima::STATUS_SELESAI)->count(),
            'dikembalikan' => $records->where('status', TandaTerima::STATUS_DIKEMBALIKAN)->count(),
            'dibatalkan' => $records->where('status', TandaTerima::STATUS_DIBATALKAN)->count(),
        ];

        $jenisBerkasCounts = [];
        foreach (TandaTerimaBerkas::JENIS_BERKAS_LIST as $jb) {
            $jenisBerkasCounts[$jb] = 0;
        }
        foreach ($records as $item) {
            foreach ($item->berkas as $b) {
                if (isset($jenisBerkasCounts[$b->jenis_berkas])) {
                    $jenisBerkasCounts[$b->jenis_berkas]++;
                }
            }
        }

        $petugasName = Auth::user() ? Auth::user()->name : 'Petugas Pelayanan';

        return view('tanda_terima.print_laporan', compact(
            'records',
            'stats',
            'jenisBerkasCounts',
            'labelPeriode',
            'petugasName'
        ));
    }
}
