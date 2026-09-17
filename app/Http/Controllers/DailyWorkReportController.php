<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyWorkReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DailyWorkReportController extends Controller
{
    /**
     * Kategori standar kegiatan kinerja harian.
     */
    public static array $kategoriList = [
        'Pelayanan Front Office',
        'Verifikasi & Validasi Dokumen',
        'Administrasi & Pengarsipan',
        'Konsultasi & Pengaduan',
        'Rapat / Koordinasi',
        'Lainnya',
    ];

    /**
     * Tampilkan halaman Laporan Kinerja Harian (Harian & Rekap Bulanan).
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'daily'); // 'daily' atau 'monthly'

        // Filter harian
        $selectedDate = $request->input('tanggal', date('Y-m-d'));

        // Filter bulanan
        $selectedMonth = (int) $request->input('bulan', (int) date('m'));
        $selectedYear = (int) $request->input('tahun', (int) date('Y'));

        // Query dasar
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $status = $request->input('status');

        // 1. Data untuk Tab Harian
        $dailyQuery = DailyWorkReport::whereDate('tanggal', $selectedDate);

        if ($search) {
            $dailyQuery->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                  ->orWhere('output_hasil', 'like', "%{$search}%")
                  ->orWhere('petugas', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }
        if ($kategori) {
            $dailyQuery->where('kategori', $kategori);
        }
        if ($status) {
            $dailyQuery->where('status', $status);
        }

        $dailyReports = $dailyQuery->orderBy('waktu_mulai', 'asc')->orderBy('id', 'asc')->get();

        // Statistik Harian
        $statsToday = [
            'total' => DailyWorkReport::whereDate('tanggal', $selectedDate)->count(),
            'selesai' => DailyWorkReport::whereDate('tanggal', $selectedDate)->where('status', 'selesai')->count(),
            'proses' => DailyWorkReport::whereDate('tanggal', $selectedDate)->where('status', 'proses')->count(),
            'tertunda' => DailyWorkReport::whereDate('tanggal', $selectedDate)->where('status', 'tertunda')->count(),
        ];

        // 2. Data untuk Tab Rekapitulasi Bulanan
        $monthlyBaseQuery = DailyWorkReport::whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth);

        $monthlyStats = [
            'total' => (clone $monthlyBaseQuery)->count(),
            'total_volume' => (clone $monthlyBaseQuery)->sum('volume'),
            'selesai' => (clone $monthlyBaseQuery)->where('status', 'selesai')->count(),
            'proses' => (clone $monthlyBaseQuery)->where('status', 'proses')->count(),
            'tertunda' => (clone $monthlyBaseQuery)->where('status', 'tertunda')->count(),
            'hari_aktif' => (clone $monthlyBaseQuery)->distinct('tanggal')->count('tanggal'),
        ];

        // Distribusi kategori dalam bulan ini
        $kategoriSummary = (clone $monthlyBaseQuery)
            ->selectRaw('kategori, count(*) as count')
            ->groupBy('kategori')
            ->pluck('count', 'kategori')
            ->toArray();

        // Query tabel bulanan (terpaginasi)
        $monthlyTableQuery = (clone $monthlyBaseQuery);
        if ($search) {
            $monthlyTableQuery->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                  ->orWhere('output_hasil', 'like', "%{$search}%")
                  ->orWhere('petugas', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }
        if ($kategori) {
            $monthlyTableQuery->where('kategori', $kategori);
        }
        if ($status) {
            $monthlyTableQuery->where('status', $status);
        }

        $monthlyReports = $monthlyTableQuery->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'asc')
            ->paginate(15, ['*'], 'monthly_page')
            ->withQueryString();

        $kategoriList = self::$kategoriList;

        return view('daily_report.index', compact(
            'tab',
            'selectedDate',
            'selectedMonth',
            'selectedYear',
            'dailyReports',
            'statsToday',
            'monthlyReports',
            'monthlyStats',
            'kategoriSummary',
            'kategoriList'
        ));
    }

    /**
     * Simpan laporan kinerja harian baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|string|max:10',
            'waktu_selesai' => 'nullable|string|max:10',
            'kegiatan' => 'required|string',
            'kategori' => 'required|string|max:100',
            'output_hasil' => 'nullable|string|max:255',
            'volume' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'status' => 'required|in:selesai,proses,tertunda',
            'keterangan' => 'nullable|string',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumentasi')) {
            $filePath = $request->file('file_dokumentasi')->store('daily_reports', 'public');
        }

        $petugasName = Auth::user() ? Auth::user()->name : 'Petugas Pelayanan';

        DailyWorkReport::create([
            'user_id' => Auth::id(),
            'petugas' => $petugasName,
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'kegiatan' => $validated['kegiatan'],
            'kategori' => $validated['kategori'],
            'output_hasil' => $validated['output_hasil'],
            'volume' => $validated['volume'],
            'satuan' => $validated['satuan'],
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
            'file_dokumentasi' => $filePath,
        ]);

        return redirect()->route('daily-reports.index', ['tanggal' => $validated['tanggal']])
            ->with('success', 'Laporan kegiatan kinerja harian berhasil disimpan!');
    }

    /**
     * Hapus laporan kinerja.
     */
    public function destroy(DailyWorkReport $dailyReport)
    {
        if ($dailyReport->file_dokumentasi) {
            Storage::disk('public')->delete($dailyReport->file_dokumentasi);
        }

        $date = $dailyReport->tanggal->format('Y-m-d');
        $dailyReport->delete();

        return redirect()->back()->with('success', 'Data kegiatan kinerja harian berhasil dihapus!');
    }

    /**
     * Ekspor rekapitulasi kinerja bulanan ke CSV / Excel.
     */
    public function exportMonthly(Request $request)
    {
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $reports = DailyWorkReport::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        $filename = "rekap_kinerja_{$namaBulan}_{$tahun}.csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM untuk Microsoft Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No',
                'Tanggal',
                'Waktu Pelaksanaan',
                'Petugas',
                'Kategori',
                'Uraian Kegiatan Kinerja',
                'Output / Hasil',
                'Volume',
                'Satuan',
                'Status',
                'Keterangan / Catatan',
            ]);

            foreach ($reports as $index => $r) {
                $waktu = ($r->waktu_mulai && $r->waktu_selesai) 
                    ? "{$r->waktu_mulai} - {$r->waktu_selesai}" 
                    : ($r->waktu_mulai ?: '-');

                fputcsv($file, [
                    $index + 1,
                    $r->tanggal->format('Y-m-d'),
                    $waktu,
                    $r->petugas ?: '-',
                    $r->kategori,
                    $r->kegiatan,
                    $r->output_hasil ?: '-',
                    $r->volume,
                    $r->satuan,
                    ucfirst($r->status),
                    $r->keterangan ?: '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Tampilan cetak resmi rekapitulasi bulanan (Print View / PDF).
     */
    public function printMonthly(Request $request)
    {
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $reports = DailyWorkReport::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        $stats = [
            'total' => $reports->count(),
            'total_volume' => $reports->sum('volume'),
            'selesai' => $reports->where('status', 'selesai')->count(),
            'proses' => $reports->where('status', 'proses')->count(),
            'tertunda' => $reports->where('status', 'tertunda')->count(),
            'hari_aktif' => $reports->pluck('tanggal')->unique()->count(),
        ];

        $petugasName = Auth::user() ? Auth::user()->name : 'Petugas Pelayanan';

        return view('daily_report.print', compact(
            'reports',
            'bulan',
            'tahun',
            'namaBulan',
            'stats',
            'petugasName'
        ));
    }
}
