<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Kinerja Bulanan - {{ $namaBulan }} {{ $tahun }}</title>
    
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
            font-size: 11pt;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            border-bottom: 3px double #0f172a;
            padding-bottom: 16px;
            margin-bottom: 24px;
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
            font-size: 13pt;
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
            margin-bottom: 24px;
        }

        .report-title h1 {
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            text-decoration: underline;
        }

        .report-title p {
            font-size: 10pt;
            font-weight: 600;
            margin: 4px 0 0 0;
            color: #0f766e;
        }

        /* Metadata Info */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .meta-table td {
            padding: 3px 0;
        }

        .meta-label {
            width: 160px;
            font-weight: 600;
            color: #475569;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 9.5pt;
        }

        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 8.5pt;
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
            margin-bottom: 30px;
            display: flex;
            justify-content: space-around;
            font-size: 9.5pt;
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
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 250px;
            text-align: center;
            font-size: 10pt;
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
            max-width: 900px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 9.5pt;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
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
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hanya tampil di browser) -->
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
                <p>Telp: (0281) 891234 | Email: pelayanan@kemenhaj.go.id</p>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title">
            <h1>Laporan Rekapitulasi Capaian Kinerja Harian</h1>
            <p>Periode: Bulan {{ $namaBulan }} Tahun {{ $tahun }}</p>
        </div>

        <!-- Meta Data Petugas & Periode -->
        <table class="meta-table">
            <tr>
                <td class="meta-label">Unit Kerja / Seksi</td>
                <td>: Pelayanan Terpadu Front Office (PTSP) Kemenhaj Purbalingga</td>
            </tr>
            <tr>
                <td class="meta-label">Petugas Pelapor</td>
                <td>: <strong>{{ $petugasName }}</strong></td>
            </tr>
            <tr>
                <td class="meta-label">Bulan / Tahun</td>
                <td>: {{ $namaBulan }} {{ $tahun }}</td>
            </tr>
        </table>

        <!-- Ringkasan Statistik -->
        <div class="summary-box">
            <div class="summary-item">
                <span>Total Aktivitas</span>
                <strong>{{ $stats['total'] }} Kegiatan</strong>
            </div>
            <div class="summary-item">
                <span>Hari Kerja Aktif</span>
                <strong>{{ $stats['hari_aktif'] }} Hari</strong>
            </div>
            <div class="summary-item">
                <span>Total Output Capaian</span>
                <strong>{{ $stats['total_volume'] }} Berkas/Item</strong>
            </div>
            <div class="summary-item">
                <span>Tingkat Selesai</span>
                <strong style="color: #0d9488;">{{ $stats['total'] > 0 ? round(($stats['selesai'] / $stats['total']) * 100) : 0 }}%</strong>
            </div>
        </div>

        <!-- Tabel Rekapitulasi -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 80px;">Tanggal</th>
                    <th style="width: 85px;">Waktu</th>
                    <th>Uraian Kegiatan Tugas</th>
                    <th style="width: 120px;">Kategori</th>
                    <th style="width: 95px;">Output Capaian</th>
                    <th style="width: 65px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $index => $r)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center whitespace-nowrap">{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td class="text-center whitespace-nowrap">
                        {{ $r->waktu_mulai ? $r->waktu_mulai . ($r->waktu_selesai ? ' - ' . $r->waktu_selesai : '') : '-' }}
                    </td>
                    <td>
                        <strong>{{ $r->kegiatan }}</strong>
                        @if($r->keterangan)
                        <div style="font-size: 8pt; color: #64748b; margin-top: 2px;">
                            <em>Catatan: {{ $r->keterangan }}</em>
                        </div>
                        @endif
                    </td>
                    <td>{{ $r->kategori }}</td>
                    <td class="text-center">
                        <strong>{{ $r->volume }} {{ $r->satuan }}</strong>
                        @if($r->output_hasil)
                        <div style="font-size: 8pt; color: #475569;">{{ $r->output_hasil }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span style="font-weight: 700; color: {{ $r->status === 'selesai' ? '#059669' : ($r->status === 'proses' ? '#d97706' : '#e11d48') }};">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 24px; color: #94a3b8;">
                        Tidak ada data kinerja yang tercatat pada bulan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan Resmi -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,<br>Kepala Seksi / Koordinator</p>
                <div class="signature-space"></div>
                <p class="signature-name">H. Ahmad Fauzi, S.Ag., M.S.I.</p>
                <p style="font-size: 9pt; color: #64748b;">NIP. 19780512 200501 1 004</p>
            </div>

            <div class="signature-box">
                <p>Purbalingga, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Petugas Pelapor,</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ $petugasName }}</p>
                <p style="font-size: 9pt; color: #64748b;">Petugas Front Office PTSP</p>
            </div>
        </div>
    </div>

</body>
</html>
