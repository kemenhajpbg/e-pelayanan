<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Buku Tamu Bulanan - {{ $namaBulan }} {{ $tahun }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <style>
        body {
            font-family: 'Instrument Sans', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
            font-size: 9.5pt;
        }

        .container {
            max-width: 1020px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            border-bottom: 3px double #0f172a;
            padding-bottom: 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2 {
            font-size: 14pt;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }

        .kop-text h3 {
            font-size: 12.5pt;
            font-weight: 700;
            margin: 2px 0 0 0;
            text-transform: uppercase;
            color: #0f172a;
        }

        .kop-text p {
            font-size: 9pt;
            margin: 4px 0 0 0;
            color: #475569;
        }

        .report-title {
            text-align: center;
            margin-bottom: 22px;
        }

        .report-title h1 {
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            text-decoration: underline;
            color: #0f172a;
        }

        .report-title p {
            font-size: 10.5pt;
            font-weight: 700;
            margin: 4px 0 0 0;
            color: #0f766e;
        }

        /* Metadata Info */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }

        .meta-table td {
            padding: 3px 0;
        }

        .meta-label {
            width: 190px;
            font-weight: 600;
            color: #475569;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 8.5pt;
        }

        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 8px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 8pt;
            color: #334155;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Summary Badges */
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-around;
            font-size: 9pt;
        }

        .summary-item {
            text-align: center;
        }

        .summary-item span {
            display: block;
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }

        .summary-item strong {
            font-size: 12pt;
            color: #0f172a;
        }

        /* Signatures */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 36px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 270px;
            text-align: center;
            font-size: 9.5pt;
        }

        .signature-space {
            height: 70px;
        }

        .signature-name {
            font-weight: 700;
            text-decoration: underline;
        }

        /* Action Buttons */
        .action-bar {
            max-width: 1020px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 9.5pt;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background-color: #0d9488;
            color: #ffffff;
        }

        .btn-print:hover {
            background-color: #0f766e;
        }

        .btn-close {
            background-color: #e2e8f0;
            color: #475569;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .action-bar {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.2cm 1.5cm;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hanya tampil di layar browser, tersembunyi saat dicetak) -->
    <div class="action-bar">
        <button onclick="window.close()" class="btn btn-close">
            &larr; Tutup Jendela
        </button>
        <button onclick="window.print()" class="btn btn-print">
            &#128438; Cetak / Simpan PDF
        </button>
    </div>

    <div class="container">
        <!-- Kop Surat Resmi -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-kemenhaj.svg') }}" alt="Logo Kemenhaj" class="kop-logo">
            <div class="kop-text">
                <h2>Kementerian Haji dan Umrah Republik Indonesia</h2>
                <h3>Kantor Kabupaten Purbalingga</h3>
                <p>Jl. Mayjen Sungkono No. 45, Kabupaten Purbalingga, Jawa Tengah 53311</p>
                <p>Telp: (0281) 891234 | Email: seksiphupbg@gmail.com / pelayanan@kemenhaj.go.id</p>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title">
            <h1>Laporan Rekapitulasi Register Pengunjung PTSP</h1>
            <p>Periode: Bulan {{ $namaBulan }} Tahun {{ $tahun }}</p>
        </div>

        <!-- Meta Data Unit Kerja & Periode -->
        <table class="meta-table">
            <tr>
                <td class="meta-label">Unit Kerja / Layanan</td>
                <td>: Seksi Penyelenggaraan Haji dan Umrah (PHU) - Front Office PTSP Purbalingga</td>
            </tr>
            <tr>
                <td class="meta-label">Periode Rekapitulasi</td>
                <td>: Bulan {{ $namaBulan }} {{ $tahun }}</td>
            </tr>
            <tr>
                <td class="meta-label">Petugas Piket / FO</td>
                <td>: <strong>{{ $petugasName }}</strong></td>
            </tr>
            <tr>
                <td class="meta-label">Waktu Cetak Laporan</td>
                <td>: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB</td>
            </tr>
        </table>

        <!-- Ringkasan Statistik Bulanan -->
        <div class="summary-box">
            <div class="summary-item">
                <span>Total Kunjungan</span>
                <strong>{{ $stats['total'] }} Pengunjung</strong>
            </div>
            <div class="summary-item">
                <span>Rata-Rata Kepuasan</span>
                <strong style="color: #d97706;">{{ $stats['avg_satisfaction'] }} / 5.0 &#9733;</strong>
            </div>
            <div class="summary-item">
                <span>Hari Layanan Aktif</span>
                <strong>{{ $stats['hari_aktif'] }} Hari</strong>
            </div>
            <div class="summary-item">
                <span>Keperluan Terbanyak</span>
                <strong style="text-transform: capitalize;">{{ $stats['top_keperluan'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Kelompok Usia Terbanyak</span>
                <strong>{{ $stats['top_usia'] }}</strong>
            </div>
        </div>

        <!-- Tabel Register Tamu Bulanan -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 80px;">Tgl & Jam</th>
                    <th style="width: 140px;">Nama Pengunjung</th>
                    <th>Alamat Lengkap</th>
                    <th style="width: 95px;">No. HP/WA</th>
                    <th style="width: 75px;">Usia</th>
                    <th style="width: 100px;">Keperluan</th>
                    <th style="width: 65px;">Rating</th>
                    <th style="width: 80px;">Paraf</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $index => $v)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        <div>{{ $v->created_at->format('d/m/Y') }}</div>
                        <span style="font-size: 7.5pt; color: #64748b;">{{ $v->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td><strong>{{ $v->nama }}</strong></td>
                    <td>{{ $v->alamat }}</td>
                    <td>{{ $v->no_hp }}</td>
                    <td class="text-center">{{ $v->kelompok_usia }}</td>
                    <td>
                        <span style="font-weight: 600; text-transform: capitalize;">
                            {{ $v->keperluan }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span style="font-weight: 700; color: #d97706;">
                            {{ $v->tingkat_kepuasan }} &#9733;
                        </span>
                    </td>
                    <td style="height: 32px; border-bottom: 1px dashed #cbd5e1;"></td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 24px; color: #94a3b8;">
                        Tidak ada data pengunjung yang tercatat pada bulan {{ $namaBulan }} {{ $tahun }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan Resmi -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,<br>Kepala Seksi Penyelenggaraan Haji dan Umrah</p>
                <div class="signature-space"></div>
                <p class="signature-name">H. Ahmad Fauzi, S.Ag., M.S.I.</p>
                <p style="font-size: 8.5pt; color: #64748b;">NIP. 19780512 200501 1 004</p>
            </div>

            <div class="signature-box">
                <p>Purbalingga, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Petugas Pelapor Front Office PTSP,</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ $petugasName }}</p>
                <p style="font-size: 8.5pt; color: #64748b;">Petugas Layanan Informasi & Tamu</p>
            </div>
        </div>
    </div>

</body>
</html>
