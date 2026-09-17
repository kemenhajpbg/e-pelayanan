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
            ['email' => 'admin@kemenhaj.pelayanan'],
            [
                'name' => 'Admin Kemenhaj',
                'password' => bcrypt('kemenhajapiik'),
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

        // 4. Seeders untuk Laporan Kinerja Harian
        if (\App\Models\DailyWorkReport::count() === 0) {
            $user = \App\Models\User::first();
            $userId = $user ? $user->id : null;
            $petugasName = $user ? $user->name : 'Admin Kemenhaj';

            $sampleReports = [
                // Hari Ini
                [
                    'tanggal' => date('Y-m-d'),
                    'waktu_mulai' => '08:00',
                    'waktu_selesai' => '09:30',
                    'kegiatan' => 'Melakukan verifikasi dan validasi kelengkapan berkas fisik pendaftaran haji reguler tahun 2026',
                    'kategori' => 'Verifikasi & Validasi Dokumen',
                    'output_hasil' => 'Berkas pendaftaran tervalidasi',
                    'volume' => 6,
                    'satuan' => 'Berkas',
                    'status' => 'selesai',
                    'keterangan' => 'Semua berkas lengkap sesuai SOP dan terinput di SISKOHAT',
                ],
                [
                    'tanggal' => date('Y-m-d'),
                    'waktu_mulai' => '09:30',
                    'waktu_selesai' => '11:45',
                    'kegiatan' => 'Pelayanan konsultasi tatap muka calon jamaah di loket Front Office terkait mekanisme pelimpahan porsi lansia dan wafat',
                    'kategori' => 'Pelayanan Front Office',
                    'output_hasil' => 'Konsultasi jamaah terlayani',
                    'volume' => 4,
                    'satuan' => 'Orang',
                    'status' => 'selesai',
                    'keterangan' => 'Diberikan lembar persyaratan dan formulir permohonan pelimpahan',
                ],
                [
                    'tanggal' => date('Y-m-d'),
                    'waktu_mulai' => '13:00',
                    'waktu_selesai' => '14:30',
                    'kegiatan' => 'Input dan sinkronisasi data Surat Pendaftaran Haji (SPH) ke aplikasi SISKOHAT Pusat',
                    'kategori' => 'Administrasi & Pengarsipan',
                    'output_hasil' => 'Nomor porsi terbit',
                    'volume' => 5,
                    'satuan' => 'Data',
                    'status' => 'selesai',
                    'keterangan' => 'Koneksi server SISKOHAT lancar',
                ],
                [
                    'tanggal' => date('Y-m-d'),
                    'waktu_mulai' => '14:30',
                    'waktu_selesai' => '16:00',
                    'kegiatan' => 'Penyusunan rekapitulasi buku tamu dan arsip berkas pelayanan front office harian',
                    'kategori' => 'Administrasi & Pengarsipan',
                    'output_hasil' => 'Rekapitulasi pelayanan',
                    'volume' => 1,
                    'satuan' => 'Laporan',
                    'status' => 'proses',
                    'keterangan' => 'Dalam proses finalisasi dan arsip dokumen',
                ],

                // 1 Hari lalu
                [
                    'tanggal' => date('Y-m-d', strtotime('-1 day')),
                    'waktu_mulai' => '08:15',
                    'waktu_selesai' => '10:00',
                    'kegiatan' => 'Pelayanan penerimaan berkas permohonan pembatalan porsi haji reguler karena wafat',
                    'kategori' => 'Pelayanan Front Office',
                    'output_hasil' => 'Berkas pembatalan diproses',
                    'volume' => 2,
                    'satuan' => 'Berkas',
                    'status' => 'selesai',
                    'keterangan' => 'Surat kematian dan penetapan ahli waris lengkap',
                ],
                [
                    'tanggal' => date('Y-m-d', strtotime('-1 day')),
                    'waktu_mulai' => '10:00',
                    'waktu_selesai' => '12:00',
                    'kegiatan' => 'Rapat koordinasi teknis persiapan bimbingan manasik haji tingkat kabupaten',
                    'kategori' => 'Rapat / Koordinasi',
                    'output_hasil' => 'Notula rapat & jadwal manasik',
                    'volume' => 1,
                    'satuan' => 'Kegiatan',
                    'status' => 'selesai',
                    'keterangan' => 'Dihadiri seluruh Kasi dan Kepala KUA se-Kabupaten Purbalingga',
                ],
                [
                    'tanggal' => date('Y-m-d', strtotime('-1 day')),
                    'waktu_mulai' => '13:30',
                    'waktu_selesai' => '15:30',
                    'kegiatan' => 'Pengecekan dan validasi data biometrik paspor calon jamaah haji reguler',
                    'kategori' => 'Verifikasi & Validasi Dokumen',
                    'output_hasil' => 'Paspor tervalidasi',
                    'volume' => 12,
                    'satuan' => 'Paspor',
                    'status' => 'selesai',
                    'keterangan' => 'Data sesuai dengan kartu keluarga dan KTP',
                ],

                // 2 Hari lalu
                [
                    'tanggal' => date('Y-m-d', strtotime('-2 days')),
                    'waktu_mulai' => '08:30',
                    'waktu_selesai' => '11:00',
                    'kegiatan' => 'Pelayanan informasi dan pengaduan masyarakat seputar estimasi tahun keberangkatan haji',
                    'kategori' => 'Konsultasi & Pengaduan',
                    'output_hasil' => 'Masyarakat terinformasikan',
                    'volume' => 7,
                    'satuan' => 'Orang',
                    'status' => 'selesai',
                    'keterangan' => 'Masyarakat puas dengan penjelasan alur antrean SISKOHAT',
                ],
                [
                    'tanggal' => date('Y-m-d', strtotime('-2 days')),
                    'waktu_mulai' => '13:00',
                    'waktu_selesai' => '15:00',
                    'kegiatan' => 'Pengarsipan dokumen surat keputusan bimbingan manasik dan data pembimbing haji',
                    'kategori' => 'Administrasi & Pengarsipan',
                    'output_hasil' => 'Arsip tertata',
                    'volume' => 3,
                    'satuan' => 'Bandel',
                    'status' => 'selesai',
                    'keterangan' => 'Tersimpan dalam lemari arsip digital dan fisik',
                ],

                // 4 Hari lalu
                [
                    'tanggal' => date('Y-m-d', strtotime('-4 days')),
                    'waktu_mulai' => '09:00',
                    'waktu_selesai' => '11:30',
                    'kegiatan' => 'Melakukan pendampingan pembuatan akun SISKOPATUH bagi Kelompok Bimbingan Ibadah Haji dan Umrah (KBIHU)',
                    'kategori' => 'Pelayanan Front Office',
                    'output_hasil' => 'Akun KBIHU aktif',
                    'volume' => 2,
                    'satuan' => 'Lembaga',
                    'status' => 'selesai',
                    'keterangan' => 'Proses registrasi berhasil tanpa kendala',
                ],

                // 6 Hari lalu
                [
                    'tanggal' => date('Y-m-d', strtotime('-6 days')),
                    'waktu_mulai' => '08:00',
                    'waktu_selesai' => '10:30',
                    'kegiatan' => 'Verifikasi berkas usulan pendaftaran izin operasional Penyelenggara Perjalanan Ibadah Umrah (PPIU)',
                    'kategori' => 'Verifikasi & Validasi Dokumen',
                    'output_hasil' => 'Berkas rekomendasi',
                    'volume' => 1,
                    'satuan' => 'Berkas',
                    'status' => 'selesai',
                    'keterangan' => 'Surat rekomendasi Kanwil diterbitkan',
                ],
            ];

            foreach ($sampleReports as $rep) {
                \App\Models\DailyWorkReport::create(array_merge($rep, [
                    'user_id' => $userId,
                    'petugas' => $petugasName,
                ]));
            }
        }
    }
}
