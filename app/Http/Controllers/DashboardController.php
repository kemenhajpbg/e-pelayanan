<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Visitor;
use App\Models\NewsReport;
use App\Models\Letter;
use App\Models\DailyWorkReport;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Metric Cards
        $totalVisitors = Visitor::count();
        $totalNews = NewsReport::count();
        $totalReportsToday = DailyWorkReport::whereDate('tanggal', date('Y-m-d'))->count();
        $totalReportsThisMonth = DailyWorkReport::whereYear('tanggal', date('Y'))->whereMonth('tanggal', date('m'))->count();
        $totalLettersIn = Letter::where('jenis', 'masuk')->count();
        $totalLettersOut = Letter::where('jenis', 'keluar')->count();
        $avgSatisfaction = round(Visitor::avg('tingkat_kepuasan') ?? 0, 1);

        // 2. Data Grafik Keperluan (Doughnut Chart)
        $keperluanData = Visitor::select('keperluan', DB::raw('count(*) as total'))
            ->groupBy('keperluan')
            ->pluck('total', 'keperluan')
            ->toArray();

        // Standardisasi keys keperluan
        $keperluanLabels = ['pendaftaran', 'konsultasi', 'pelimpahan', 'pembatalan', 'lainnya'];
        $keperluanChart = [];
        foreach ($keperluanLabels as $label) {
            $keperluanChart[$label] = $keperluanData[$label] ?? 0;
        }

        // 3. Data Grafik Kelompok Usia (Bar Chart)
        $usiaData = Visitor::select('kelompok_usia', DB::raw('count(*) as total'))
            ->groupBy('kelompok_usia')
            ->pluck('total', 'kelompok_usia')
            ->toArray();

        $usiaLabels = ['Anak-anak', 'Remaja', 'Dewasa', 'Lansia'];
        $usiaChart = [];
        foreach ($usiaLabels as $label) {
            $usiaChart[$label] = $usiaData[$label] ?? 0;
        }

        // 4. Data Grafik Tingkat Kepuasan (Bar Chart)
        $kepuasanData = Visitor::select('tingkat_kepuasan', DB::raw('count(*) as total'))
            ->groupBy('tingkat_kepuasan')
            ->pluck('total', 'tingkat_kepuasan')
            ->toArray();

        $kepuasanChart = [];
        for ($i = 1; $i <= 5; $i++) {
            $kepuasanChart[$i] = $kepuasanData[$i] ?? 0;
        }

        // 5. Data Grafik Tren Kunjungan (Line Chart) - 7 hari terakhir
        $driver = DB::getDriverName();
        $dateExpr = $driver === 'sqlite' ? "strftime('%Y-%m-%d', created_at)" : "DATE(created_at)";

        $trenKunjungan = Visitor::select(DB::raw("{$dateExpr} as date"), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->limit(7)
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // Isi hari-hari kosong jika datanya sedikit agar chart tetap rapi
        if (empty($trenKunjungan)) {
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $trenKunjungan[$date] = 0;
            }
        } else {
            // Urutkan key tanggal
            ksort($trenKunjungan);
        }

        return view('dashboard', compact(
            'totalVisitors',
            'totalNews',
            'totalReportsToday',
            'totalReportsThisMonth',
            'totalLettersIn',
            'totalLettersOut',
            'avgSatisfaction',
            'keperluanChart',
            'usiaChart',
            'kepuasanChart',
            'trenKunjungan'
        ));
    }

    public function exportExcelEmail(Request $request)
    {
        // 1. Ambil data jumlah
        $totalVisitors = Visitor::count();
        $totalNews = NewsReport::count();
        $totalLettersIn = Letter::where('jenis', 'masuk')->count();
        $totalLettersOut = Letter::where('jenis', 'keluar')->count();

        // 2. Buat file CSV untuk Buku Tamu
        $visitorHeaders = ['ID', 'Nama', 'Alamat', 'No HP', 'Kelompok Usia', 'Keperluan', 'Tingkat Kepuasan', 'Tanggal Dibuat'];
        $visitorRows = [];
        foreach (Visitor::all() as $v) {
            $visitorRows[] = [
                $v->id,
                $v->nama,
                $v->alamat,
                $v->no_hp,
                $v->kelompok_usia,
                $v->keperluan,
                $v->tingkat_kepuasan,
                $v->created_at->format('Y-m-d H:i:s')
            ];
        }
        $pathVisitors = $this->generateCsv('buku_tamu_report.csv', $visitorHeaders, $visitorRows);

        // 3. Buat file CSV untuk Berita Tayang
        $newsHeaders = ['ID', 'Judul', 'Tanggal Tayang', 'Link Berita', 'Kategori', 'Narasi', 'Tanggal Dibuat'];
        $newsRows = [];
        foreach (NewsReport::all() as $n) {
            $newsRows[] = [
                $n->id,
                $n->judul,
                $n->tanggal_tayang,
                $n->link_berita,
                $n->kategori,
                $n->narasi,
                $n->created_at->format('Y-m-d H:i:s')
            ];
        }
        $pathNews = $this->generateCsv('berita_tayang_report.csv', $newsHeaders, $newsRows);

        // 4. Buat file CSV untuk Laporan Kinerja Harian
        $totalReports = DailyWorkReport::count();
        $reportHeaders = ['ID', 'Tanggal', 'Waktu', 'Petugas', 'Kategori', 'Kegiatan', 'Output / Hasil', 'Volume', 'Satuan', 'Status', 'Keterangan'];
        $reportRows = [];
        foreach (DailyWorkReport::all() as $r) {
            $waktu = ($r->waktu_mulai && $r->waktu_selesai) 
                ? "{$r->waktu_mulai} - {$r->waktu_selesai}" 
                : ($r->waktu_mulai ?: '-');

            $reportRows[] = [
                $r->id,
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
            ];
        }
        $pathReports = $this->generateCsv('laporan_kinerja_harian_report.csv', $reportHeaders, $reportRows);

        // 5. Kirim Email dengan attachments
        $email = env('GOOGLE_DRIVE_EMAIL', 'seksiphupbg@gmail.com');
        $filePaths = [$pathVisitors, $pathNews, $pathReports];

        try {
            \Illuminate\Support\Facades\Mail::to($email)->send(
                new \App\Mail\LaporanPelayananMail(
                    $filePaths,
                    $totalVisitors,
                    $totalNews,
                    $totalReports,
                    $totalLettersIn,
                    $totalLettersOut
                )
            );

            // Bersihkan file sementara
            foreach ($filePaths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            return redirect()->back()->with('success', 'Laporan Excel berhasil dikirim ke email ' . $email . '!');
        } catch (\Exception $e) {
            // Bersihkan file jika gagal
            foreach ($filePaths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            return redirect()->back()->withErrors(['error' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    private function generateCsv(string $filename, array $headers, array $rows)
    {
        $path = storage_path('app/' . $filename);
        
        $file = fopen($path, 'w');
        
        // UTF-8 BOM untuk MS Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($file, $headers);
        
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
        
        return $path;
    }
}
