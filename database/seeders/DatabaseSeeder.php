<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed User Admin Default
        User::updateOrCreate(
            ['email' => 'adminpelayanan@kemenhaj.com'],
            [
                'name' => 'Admin Kemenhaj',
                'password' => bcrypt('@1124D'),
            ]
        );

        // 1. Seeders untuk Buku Tamu (Visitors)
        if (\App\Models\Visitor::count() === 0) {
            $keperluanOptions = ['pendaftaran', 'konsultasi', 'pelimpahan', 'pembatalan', 'lainnya'];
            $usiaOptions = ['Anak-anak', 'Remaja', 'Dewasa', 'Lansia'];
            
            $names = [
                'Budi Santoso', 'Siti Rahma', 'Joko Susilo', 'Dewi Lestari', 'Ahmad Yani',
                'Rina Wijaya', 'Hendra Wijaya', 'Sri Utami', 'Bambang Hermawan', 'Megawati',
                'Rudi Hartono', 'Kartini', 'Indra Lesmana', 'Yuni Shara', 'Gus Dur'
            ];

            $alamatList = [
                'Purbalingga Lor', 'Kalimanah', 'Bukateja', 'Bobotsari', 'Mrebet',
                'Kutasari', 'Karangreja', 'Karanganyar', 'Kemangkon', 'Padamara'
            ];

            for ($i = 0; $i < 20; $i++) {
                $keperluan = $keperluanOptions[array_rand($keperluanOptions)];
                // Distribusi usia (lebih banyak dewasa)
                $usiaRand = rand(1, 10);
                $usia = $usiaRand <= 6 ? 'Dewasa' : ($usiaRand <= 8 ? 'Remaja' : ($usiaRand == 9 ? 'Lansia' : 'Anak-anak'));
                
                // Tanggal menyebar dalam 7 hari terakhir
                $date = date('Y-m-d H:i:s', strtotime('-' . rand(0, 6) . ' days -' . rand(1, 12) . ' hours'));

                \App\Models\Visitor::create([
                    'nama' => $names[array_rand($names)],
                    'alamat' => $alamatList[array_rand($alamatList)] . ', Kab. Purbalingga',
                    'no_hp' => '081' . rand(10000000, 99999999),
                    'kelompok_usia' => $usia,
                    'keperluan' => $keperluan,
                    'tingkat_kepuasan' => rand(3, 5),
                    'foto_pelayanan' => null, // default tanpa foto
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        // 2. Seeders untuk Berita Tayang
        if (\App\Models\NewsReport::count() === 0) {
            $kategoriNews = ['Kegiatan', 'Pengumuman', 'Edukasi', 'Lainnya'];
            $newsTitles = [
                'Kemenhaj Purbalingga Adakan Sosialisasi Sertifikasi Halal Gratis',
                'Pengumuman Jadwal Manasik Haji Tingkat Kabupaten Purbalingga 2026',
                'Pentingnya Pendidikan Karakter Sejak Dini di Madrasah',
                'Kemenhaj Berikan Penghargaan Kepada KUA Berprestasi',
                'Pembukaan Pendaftaran Penyuluh Agama Non-PNS Tahun 2026'
            ];

            foreach ($newsTitles as $index => $title) {
                $cat = $kategoriNews[$index % count($kategoriNews)];
                $date = date('Y-m-d', strtotime('-' . ($index * 2) . ' days'));
                \App\Models\NewsReport::create([
                    'judul' => $title,
                    'tanggal_tayang' => $date,
                    'link_berita' => 'https://purbalingga.kemenhaj.go.id/berita/' . ($index + 1),
                    'kategori' => $cat,
                    'narasi' => 'Ini adalah deskripsi singkat mengenai berita "' . $title . '" yang dirilis di portal resmi Kantor Kementerian Haji dan Umrah Kabupaten Purbalingga.',
                    'file_dokumen' => null,
                ]);
            }
        }

        // 3. Seeders untuk Surat Masuk dan Keluar
        if (\App\Models\Letter::count() === 0) {
            $suratMasuk = [
                ['nomor_surat' => '025/KEMENHAJ/VI/2026', 'pengirim_penerima' => 'Kanwil Kemenhaj Prov. Jawa Tengah', 'perihal' => 'Undangan Rapat Koordinasi Program Haji Tahun 2026'],
                ['nomor_surat' => '110/KUA-PBG/2026', 'pengirim_penerima' => 'KUA Kecamatan Bobotsari', 'perihal' => 'Laporan Bulanan Peristiwa Nikah Bulan Juni 2026'],
                ['nomor_surat' => 'B-340/PEMDA-PBG/2026', 'pengirim_penerima' => 'Sekretariat Daerah Purbalingga', 'perihal' => 'Permohonan Delegasi Doa HUT Kabupaten Purbalingga']
            ];

            foreach ($suratMasuk as $index => $s) {
                $date = date('Y-m-d', strtotime('-' . ($index + 1) . ' days'));
                \App\Models\Letter::create([
                    'jenis' => 'masuk',
                    'nomor_surat' => $s['nomor_surat'],
                    'tanggal_surat' => $date,
                    'tanggal_terima_kirim' => $date,
                    'pengirim_penerima' => $s['pengirim_penerima'],
                    'perihal' => $s['perihal'],
                    'file_surat' => null,
                ]);
            }

            $suratKeluar = [
                ['nomor_surat' => 'B-702/Kk.11.03/1/HM.00/2026', 'pengirim_penerima' => 'Seluruh Kepala Madrasah se-Purbalingga', 'perihal' => 'Edaran Kebersihan dan Keamanan Sekolah Menjelang Ajaran Baru'],
                ['nomor_surat' => 'B-715/Kk.11.03/2/PP.00/2026', 'pengirim_penerima' => 'Kanwil Kemenhaj Prov. Jawa Tengah', 'perihal' => 'Pengiriman Data Usulan Guru Madrasah Penerima Insentif']
            ];

            foreach ($suratKeluar as $index => $s) {
                $date = date('Y-m-d', strtotime('-' . ($index + 1) . ' days'));
                \App\Models\Letter::create([
                    'jenis' => 'keluar',
                    'nomor_surat' => $s['nomor_surat'],
                    'tanggal_surat' => $date,
                    'tanggal_terima_kirim' => $date,
                    'pengirim_penerima' => $s['pengirim_penerima'],
                    'perihal' => $s['perihal'],
                    'file_surat' => null,
                ]);
            }
        }
    }
}
