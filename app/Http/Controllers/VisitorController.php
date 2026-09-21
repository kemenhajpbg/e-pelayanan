<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Visitor;
use App\Mail\VisitorMonthlyReportMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'daily'); // 'daily' atau 'monthly'

        // Tanggal & waktu filter harian
        $selectedDate = $request->input('tanggal', date('Y-m-d'));

        // Filter bulanan
        $selectedMonth = (int) $request->input('bulan', (int) date('m'));
        $selectedYear = (int) $request->input('tahun', (int) date('Y'));

        // Query 1: Pengunjung Harian / Register Umum
        $dailyQuery = Visitor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $dailyQuery->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $dailyQuery->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('kelompok_usia')) {
            $dailyQuery->where('kelompok_usia', $request->kelompok_usia);
        }

        if ($request->filled('keperluan')) {
            $dailyQuery->where('keperluan', $request->keperluan);
        }

        if ($request->filled('tingkat_kepuasan')) {
            $dailyQuery->where('tingkat_kepuasan', $request->tingkat_kepuasan);
        }

        $visitors = $dailyQuery->latest()->paginate(10, ['*'], 'daily_page')->withQueryString();

        // Query 2: Data Rekapitulasi Bulanan
        $monthlyBaseQuery = Visitor::whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth);

        $allMonthlyVisitors = (clone $monthlyBaseQuery)->get();
        $monthlyTotal = $allMonthlyVisitors->count();

        $keperluanGroups = $allMonthlyVisitors->groupBy('keperluan')->map->count();
        $usiaGroups = $allMonthlyVisitors->groupBy('kelompok_usia')->map->count();

        $monthlyStats = [
            'total' => $monthlyTotal,
            'avg_satisfaction' => round($allMonthlyVisitors->avg('tingkat_kepuasan') ?? 0, 1),
            'hari_aktif' => $allMonthlyVisitors->map(fn($v) => $v->created_at->format('Y-m-d'))->unique()->count(),
            'top_keperluan' => $keperluanGroups->sortDesc()->keys()->first() ?? '-',
            'top_usia' => $usiaGroups->sortDesc()->keys()->first() ?? '-',
            'keperluan' => $keperluanGroups,
            'usia' => $usiaGroups,
        ];

        // Tabel Pengunjung Bulanan Terfilter
        $monthlyTableQuery = (clone $monthlyBaseQuery);
        if ($request->filled('search_monthly')) {
            $searchM = $request->search_monthly;
            $monthlyTableQuery->where(function($q) use ($searchM) {
                $q->where('nama', 'like', "%{$searchM}%")
                  ->orWhere('alamat', 'like', "%{$searchM}%")
                  ->orWhere('no_hp', 'like', "%{$searchM}%")
                  ->orWhere('keperluan', 'like', "%{$searchM}%");
            });
        }
        if ($request->filled('keperluan_monthly')) {
            $monthlyTableQuery->where('keperluan', $request->keperluan_monthly);
        }
        if ($request->filled('kelompok_usia_monthly')) {
            $monthlyTableQuery->where('kelompok_usia', $request->kelompok_usia_monthly);
        }
        if ($request->filled('tingkat_kepuasan_monthly')) {
            $monthlyTableQuery->where('tingkat_kepuasan', $request->tingkat_kepuasan_monthly);
        }

        $monthlyVisitors = $monthlyTableQuery->latest()->paginate(15, ['*'], 'monthly_page')->withQueryString();

        return view('visitor.index', compact(
            'tab',
            'selectedDate',
            'selectedMonth',
            'selectedYear',
            'visitors',
            'monthlyVisitors',
            'monthlyStats'
        ));
    }

    /**
     * Tampilan cetak PDF / print register tamu harian.
     */
    public function printDaily(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $formattedDate = Carbon::parse($tanggal)->translatedFormat('l, d F Y');

        $visitors = Visitor::whereDate('created_at', $tanggal)
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'total' => $visitors->count(),
            'avg_satisfaction' => round($visitors->avg('tingkat_kepuasan') ?? 0, 1),
            'keperluan' => $visitors->groupBy('keperluan')->map->count(),
            'usia' => $visitors->groupBy('kelompok_usia')->map->count(),
        ];

        $petugasName = $request->input('petugas', 'M. Ainul Fikri');

        return view('visitor.print', compact('visitors', 'tanggal', 'formattedDate', 'stats', 'petugasName'));
    }

    /**
     * Tampilan cetak resmi rekapitulasi buku tamu bulanan (PDF).
     */
    public function printMonthly(Request $request)
    {
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $visitors = Visitor::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'asc')
            ->get();

        $keperluanGroups = $visitors->groupBy('keperluan')->map->count();
        $usiaGroups = $visitors->groupBy('kelompok_usia')->map->count();

        $stats = [
            'total' => $visitors->count(),
            'avg_satisfaction' => round($visitors->avg('tingkat_kepuasan') ?? 0, 1),
            'hari_aktif' => $visitors->map(fn($v) => $v->created_at->format('Y-m-d'))->unique()->count(),
            'top_keperluan' => $keperluanGroups->sortDesc()->keys()->first() ?? '-',
            'top_usia' => $usiaGroups->sortDesc()->keys()->first() ?? '-',
            'keperluan' => $keperluanGroups,
            'usia' => $usiaGroups,
        ];

        $petugasName = $request->input('petugas', 'M. Ainul Fikri');

        return view('visitor.print_monthly', compact(
            'visitors',
            'bulan',
            'tahun',
            'namaBulan',
            'stats',
            'petugasName'
        ));
    }

    /**
     * Ekspor data rekapitulasi buku tamu bulanan ke file CSV / Excel Spreadsheet.
     */
    public function exportMonthly(Request $request)
    {
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $visitors = Visitor::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'asc')
            ->get();

        $filename = "rekap_buku_tamu_{$namaBulan}_{$tahun}.csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($visitors) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar terbaca sempurna di Microsoft Excel & Google Sheets
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No',
                'Tanggal Kunjungan',
                'Waktu (WIB)',
                'Nama Lengkap Pengunjung',
                'Alamat Lengkap',
                'Nomor HP / WhatsApp',
                'Kelompok Usia',
                'Keperluan Pelayanan',
                'Tingkat Kepuasan (1-5)',
                'Waktu Input Sistem',
            ]);

            foreach ($visitors as $index => $v) {
                fputcsv($file, [
                    $index + 1,
                    $v->created_at->format('Y-m-d'),
                    $v->created_at->format('H:i'),
                    $v->nama,
                    $v->alamat,
                    $v->no_hp,
                    $v->kelompok_usia,
                    ucfirst($v->keperluan),
                    $v->tingkat_kepuasan,
                    $v->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Kirim file spreadsheet rekapitulasi buku tamu bulanan ke Google Drive / Email seksiphupbg@gmail.com.
     */
    public function syncDriveMonthly(Request $request)
    {
        $bulan = (int) $request->input('bulan', (int) date('m'));
        $tahun = (int) $request->input('tahun', (int) date('Y'));

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $visitors = Visitor::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'asc')
            ->get();

        $keperluanGroups = $visitors->groupBy('keperluan')->map->count();
        $usiaGroups = $visitors->groupBy('kelompok_usia')->map->count();

        $stats = [
            'total' => $visitors->count(),
            'avg_satisfaction' => round($visitors->avg('tingkat_kepuasan') ?? 0, 1),
            'hari_aktif' => $visitors->map(fn($v) => $v->created_at->format('Y-m-d'))->unique()->count(),
            'top_keperluan' => $keperluanGroups->sortDesc()->keys()->first() ?? '-',
            'top_usia' => $usiaGroups->sortDesc()->keys()->first() ?? '-',
        ];

        $petugasName = Auth::user() ? Auth::user()->name : 'Petugas Front Office';

        // Buat file CSV sementara di storage
        $filename = "rekap_buku_tamu_{$namaBulan}_{$tahun}.csv";
        $tempPath = storage_path('app/public/' . $filename);

        if (!file_exists(storage_path('app/public'))) {
            mkdir(storage_path('app/public'), 0755, true);
        }

        $file = fopen($tempPath, 'w');
        // UTF-8 BOM
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($file, [
            'No',
            'Tanggal Kunjungan',
            'Waktu (WIB)',
            'Nama Lengkap Pengunjung',
            'Alamat Lengkap',
            'Nomor HP / WhatsApp',
            'Kelompok Usia',
            'Keperluan Pelayanan',
            'Tingkat Kepuasan (1-5)',
            'Waktu Input Sistem',
        ]);

        foreach ($visitors as $index => $v) {
            fputcsv($file, [
                $index + 1,
                $v->created_at->format('Y-m-d'),
                $v->created_at->format('H:i'),
                $v->nama,
                $v->alamat,
                $v->no_hp,
                $v->kelompok_usia,
                ucfirst($v->keperluan),
                $v->tingkat_kepuasan,
                $v->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        fclose($file);

        $targetEmail = env('GOOGLE_DRIVE_EMAIL', 'seksiphupbg@gmail.com');

        try {
            Mail::to($targetEmail)->send(
                new VisitorMonthlyReportMail(
                    $tempPath,
                    $namaBulan,
                    $tahun,
                    $stats,
                    $petugasName
                )
            );

            // Bersihkan file sementara
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            return redirect()->back()->with('success', "Rekapitulasi spreadsheet bulan {$namaBulan} {$tahun} berhasil dikirim ke {$targetEmail}! Anda dapat menyimpannya langsung ke Google Drive.");
        } catch (\Exception $e) {
            // Bersihkan file sementara jika error
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            Log::error('Gagal kirim rekap bulanan ke Google Drive/Email: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Gagal mengirim email rekapitulasi: ' . $e->getMessage() . '. Pastikan pengaturan SMTP di Hostinger (.env) sudah aktif.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'kelompok_usia' => 'required|string',
            'keperluan' => 'required|string',
            'tingkat_kepuasan' => 'required|integer|between:1,5',
            'foto_file' => 'nullable|image|max:5120', // Maksimal 5MB
            'foto_captured' => 'nullable|string', // String base64 dari webcam
        ]);

        $fotoPath = null;

        // Tangani tangkapan foto dari webcam (base64)
        if ($request->filled('foto_captured')) {
            $imageData = $request->foto_captured;
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);

                if (in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $imageData = base64_decode($imageData);

                    if ($imageData !== false) {
                        $fileName = 'visitor_' . time() . '_' . Str::random(10) . '.' . $type;
                        Storage::disk('public')->put('visitors/' . $fileName, $imageData);
                        $fotoPath = 'visitors/' . $fileName;
                    }
                }
            }
        }

        // Jika tidak ada foto webcam, cek jika ada file diunggah manual
        if (!$fotoPath && $request->hasFile('foto_file')) {
            $fotoPath = $request->file('foto_file')->store('visitors', 'public');
        }

        $visitor = Visitor::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'kelompok_usia' => $request->kelompok_usia,
            'keperluan' => $request->keperluan,
            'tingkat_kepuasan' => $request->tingkat_kepuasan,
            'foto_pelayanan' => $fotoPath,
        ]);

        // Kirim data ke Google Sheets Webhook jika dikonfigurasi
        $sheetWebhook = env('GOOGLE_SHEET_WEBHOOK_URL');
        if ($sheetWebhook) {
            try {
                Http::timeout(5)->post($sheetWebhook, [
                    'timestamp' => $visitor->created_at->format('Y-m-d H:i:s'),
                    'nama' => $visitor->nama,
                    'alamat' => $visitor->alamat,
                    'no_hp' => $visitor->no_hp,
                    'kelompok_usia' => $visitor->kelompok_usia,
                    'keperluan' => $visitor->keperluan,
                    'tingkat_kepuasan' => $visitor->tingkat_kepuasan,
                    'foto_url' => $fotoPath ? asset('storage/' . $fotoPath) : null,
                ]);
            } catch (\Exception $e) {
                // Log kegagalan webhook agar tidak menghalangi proses simpan data utama
                Log::warning('Google Sheet Webhook Failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Data pengunjung berhasil disimpan!');
    }

    public function destroy(Visitor $visitor)
    {
        if ($visitor->foto_pelayanan) {
            Storage::disk('public')->delete($visitor->foto_pelayanan);
        }
        $visitor->delete();
        return redirect()->back()->with('success', 'Data pengunjung berhasil dihapus!');
    }
}
