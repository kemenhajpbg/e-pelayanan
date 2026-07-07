<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Visitor;
use App\Models\NewsReport;
use App\Models\Letter;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Metric Cards
        $totalVisitors = Visitor::count();
        $totalNews = NewsReport::count();
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
        // SQLite compatible format
        $trenKunjungan = Visitor::select(DB::raw("strftime('%Y-%m-%d', created_at) as date"), DB::raw('count(*) as total'))
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

        // 4. Buat file CSV untuk Surat
        $letterHeaders = ['ID', 'Jenis', 'Nomor Surat', 'Tanggal Surat', 'Tanggal Terima/Kirim', 'Pengirim/Penerima', 'Perihal', 'Tanggal Diarsipkan'];
        $letterRows = [];
        foreach (Letter::all() as $l) {
            $letterRows[] = [
                $l->id,
                $l->jenis === 'masuk' ? 'Surat Masuk' : 'Surat Keluar',
                $l->nomor_surat,
                $l->tanggal_surat,
                $l->tanggal_terima_kirim,
                $l->pengirim_penerima,
                $l->perihal,
                $l->created_at->format('Y-m-d H:i:s')
            ];
        }
        $pathLetters = $this->generateCsv('surat_masuk_keluar_report.csv', $letterHeaders, $letterRows);

        // 5. Kirim Email dengan attachments
        $email = 'izzulhaq014@gmail.com';
        $filePaths = [$pathVisitors, $pathNews, $pathLetters];

        try {
            \Illuminate\Support\Facades\Mail::to($email)->send(
                new \App\Mail\LaporanPelayananMail(
                    $filePaths,
                    $totalVisitors,
                    $totalNews,
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
