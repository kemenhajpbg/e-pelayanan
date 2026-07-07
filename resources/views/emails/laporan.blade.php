<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan E-Pelayanan Kemenhaj</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc;">
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
        
        <!-- Header Banner -->
        <div style="background-color: #0f172a; padding: 32px; text-align: center; border-bottom: 4px solid #14b8a6;">
            <h1 style="color: #ffffff; font-size: 24px; font-weight: bold; margin: 0; tracking: tight;">E-Pelayanan Kemenhaj</h1>
            <p style="color: #14b8a6; font-size: 14px; margin: 8px 0 0 0; font-weight: 600;">Laporan Rekapitulasi Data Pelayanan Terpadu</p>
        </div>
        
        <!-- Body Content -->
        <div style="padding: 32px;">
            <p style="font-size: 16px; margin: 0 0 16px 0;">Halo,</p>
            <p style="font-size: 14px; color: #475569; margin: 0 0 24px 0; line-height: 1.8;">
                Berikut terlampir laporan rekapitulasi data dari aplikasi **E-Pelayanan & Pengarsipan Digital Kantor Kementerian Haji dan Umrah Kabupaten Purbalingga**. Data telah diekspor ke dalam format **Excel / Spreadsheet (CSV)** untuk diolah lebih lanjut.
            </p>
            
            <!-- Summary Info -->
            <div style="background-color: #f1f5f9; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
                <h3 style="color: #0f172a; font-size: 14px; margin: 0 0 12px 0; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">Ringkasan Data Laporan:</h3>
                
                <table style="width: 100%; font-size: 13px; color: #475569; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Buku Tamu / Pengunjung</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ $totalVisitors }} Data</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Laporan Berita Tayang</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ $totalNews }} Berita</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Surat Masuk</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ $totalLettersIn }} Surat</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: 500;">Surat Keluar</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ $totalLettersOut }} Surat</td>
                    </tr>
                </table>
            </div>

            <!-- Notice box -->
            <div style="border-left: 4px solid #14b8a6; padding-left: 16px; margin-bottom: 28px; font-size: 13px; color: #64748b; font-style: italic;">
                Semua data terlampir dipisahkan menjadi file CSV tersendiri (Buku Tamu, Berita Tayang, dan Surat Masuk/Keluar) yang dapat dibuka secara langsung menggunakan Microsoft Excel, Google Sheets, atau aplikasi spreadsheet lainnya.
            </div>

            <p style="font-size: 14px; color: #475569; margin: 0 0 8px 0;">Terima kasih,</p>
            <p style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">Sistem E-Pelayanan Kemenhaj</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px 32px; border-t: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #94a3b8;">
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh Sistem E-Pelayanan.</p>
            <p style="margin: 4px 0 0 0;">&copy; {{ date('Y') }} Kemenhaj Purbalingga</p>
        </div>
    </div>
</body>
</html>
