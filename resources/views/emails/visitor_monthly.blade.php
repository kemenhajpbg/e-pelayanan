<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Bulanan Buku Tamu</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc;">
    <div style="max-width: 620px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
        
        <!-- Header Banner -->
        <div style="background-color: #0f172a; padding: 32px; text-align: center; border-bottom: 4px solid #14b8a6;">
            <h1 style="color: #ffffff; font-size: 22px; font-weight: bold; margin: 0; letter-spacing: 0.5px;">KEMENTERIAN HAJI DAN UMRAH</h1>
            <p style="color: #14b8a6; font-size: 14px; margin: 8px 0 0 0; font-weight: 600;">Kantor Kabupaten Purbalingga - Seksi PHU</p>
            <p style="color: #94a3b8; font-size: 12px; margin: 4px 0 0 0;">Front Office Pelayanan Terpadu Satu Pintu (PTSP)</p>
        </div>
        
        <!-- Body Content -->
        <div style="padding: 32px;">
            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 16px; margin-bottom: 24px;">
                <p style="font-size: 14px; color: #166534; font-weight: bold; margin: 0;">
                    📁 Rekapitulasi Data Buku Tamu Bulan: {{ $namaBulan }} {{ $tahun }}
                </p>
            </div>

            <p style="font-size: 14px; color: #475569; margin: 0 0 20px 0; line-height: 1.8;">
                Berikut terlampir file spreadsheet rekapitulasi data kunjungan buku tamu pada <strong>Bulan {{ $namaBulan }} {{ $tahun }}</strong> dari sistem <strong>E-Pelayanan PTSP Kemenhaj Purbalingga</strong>.
            </p>
            
            <!-- Summary Table -->
            <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                <h3 style="color: #0f172a; font-size: 14px; margin: 0 0 12px 0; border-bottom: 2px solid #cbd5e1; padding-bottom: 8px;">Ringkasan Data Kunjungan:</h3>
                
                <table style="width: 100%; font-size: 13px; color: #475569; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Total Pengunjung Bulan Ini</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ $stats['total'] }} Tamu</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Rata-Rata Tingkat Kepuasan</td>
                        <td style="text-align: right; font-weight: 700; color: #d97706;">{{ $stats['avg_satisfaction'] }} / 5.0 &#9733;</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Jumlah Hari Pelayanan Aktif</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ $stats['hari_aktif'] }} Hari</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Keperluan Paling Dominan</td>
                        <td style="text-align: right; font-weight: 700; color: #0d9488; text-transform: capitalize;">{{ $stats['top_keperluan'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Petugas Pelapor / Operator</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ $petugasName }}</td>
                    </tr>
                </table>
            </div>

            <!-- Google Drive Notice -->
            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 14px 16px; margin-bottom: 24px; border-radius: 0 10px 10px 0;">
                <p style="font-size: 13px; color: #1e40af; margin: 0; font-weight: 600;">
                    💡 Cara Simpan Otomatis ke Google Drive:
                </p>
                <p style="font-size: 12px; color: #3b82f6; margin: 4px 0 0 0; line-height: 1.6;">
                    Arahkan kursor pada lampiran file CSV/Spreadsheet di bagian bawah email ini, lalu klik ikon <strong>"Simpan ke Drive" (Google Drive icon)</strong> untuk menyimpannya langsung ke Google Drive akun <code>seksiphupbg@gmail.com</code>.
                </p>
            </div>

            <p style="font-size: 13px; color: #475569; margin: 0 0 6px 0;">Terima kasih,</p>
            <p style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">Seksi Penyelenggaraan Haji dan Umrah (PHU)</p>
            <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">Kantor Kementerian Haji dan Umrah Kabupaten Purbalingga</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 18px 32px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #94a3b8;">
            <p style="margin: 0;">Email resmi ini dikirim secara otomatis oleh Sistem E-Pelayanan & Pengarsipan Digital.</p>
            <p style="margin: 4px 0 0 0;">&copy; {{ date('Y') }} Kemenhaj Purbalingga | seksiphupbg@gmail.com</p>
        </div>
    </div>
</body>
</html>
