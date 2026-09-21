<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Harian - {{ $formattedDate }}</title>
    
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
            font-size: 10pt;
        }

        .container {
            max-width: 960px;
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
            font-size: 9.5pt;
        }

        .meta-table td {
            padding: 3px 0;
        }

        .meta-label {
            width: 170px;
            font-weight: 600;
            color: #475569;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 9pt;
        }

        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
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
            margin-bottom: 24px;
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
            justify-content: flex-end;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 260px;
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
            max-width: 960px;
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

    <!-- Action Bar (Hanya tampil di layar browser) -->
    <div class="action-bar">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <button onclick="window.close()" class="btn btn-close">
                &larr; Tutup Jendela
            </button>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 9.5pt; color: #334155; font-weight: 600;">
                <label for="petugasSelect">&#128100; Petugas Piket / FO:</label>
                <select id="petugasSelect" onchange="updatePetugas(this.value)" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 9pt; font-weight: 600; cursor: pointer; background: #fff;">
                    <option value="M. Ainul Fikri" {{ $petugasName == 'M. Ainul Fikri' ? 'selected' : '' }}>1. M. Ainul Fikri</option>
                    <option value="M Haidar Izzul haq" {{ $petugasName == 'M Haidar Izzul haq' ? 'selected' : '' }}>2. M Haidar Izzul haq</option>
                </select>
            </div>
        </div>
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
                <p>Telp: +62 822-2502-0837 | Email: kemenhajum.pbg@gmail.com</p>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title">
            <h1>Buku Register Pengunjung Pelayanan Terpadu (PTSP)</h1>
            <p>Hari / Tanggal: {{ $formattedDate }}</p>
        </div>

        <!-- Meta Data Petugas & Lokasi -->
        <table class="meta-table">
            <tr>
                <td class="meta-label">Unit Kerja / Layanan</td>
                <td>: Front Office Pelayanan Terpadu Satu Pintu (PTSP) Kemenhaj Purbalingga</td>
            </tr>
            <tr>
                <td class="meta-label">Petugas Piket / FO</td>
                <td>: <strong class="petugas-name-display">{{ $petugasName }}</strong></td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Pelayanan</td>
                <td>: {{ $formattedDate }}</td>
            </tr>
        </table>

        <!-- Ringkasan Statistik Harian -->
        <div class="summary-box">
            <div class="summary-item">
                <span>Total Kunjungan</span>
                <strong>{{ $stats['total'] }} Tamu</strong>
            </div>
            <div class="summary-item">
                <span>Rata-Rata Kepuasan</span>
                <strong style="color: #d97706;">{{ $stats['avg_satisfaction'] }} / 5.0 &#9733;</strong>
            </div>
            <div class="summary-item">
                <span>Kelompok Usia Terbanyak</span>
                <strong>
                    @php
                        $topUsia = '-';
                        if (!empty($stats['usia']) && count($stats['usia']) > 0) {
                            $topUsia = $stats['usia']->sortDesc()->keys()->first();
                        }
                    @endphp
                    {{ $topUsia }}
                </strong>
            </div>
            <div class="summary-item">
                <span>Keperluan Terbanyak</span>
                <strong>
                    @php
                        $topKeperluan = '-';
                        if (!empty($stats['keperluan']) && count($stats['keperluan']) > 0) {
                            $topKeperluan = ucfirst($stats['keperluan']->sortDesc()->keys()->first());
                        }
                    @endphp
                    {{ $topKeperluan }}
                </strong>
            </div>
        </div>

        <!-- Tabel Register Tamu Harian -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 60px;">Jam</th>
                    <th style="width: 140px;">Nama Tamu</th>
                    <th>Alamat Lengkap</th>
                    <th style="width: 100px;">No. HP/WA</th>
                    <th style="width: 80px;">Usia</th>
                    <th style="width: 110px;">Keperluan</th>
                    <th style="width: 70px;">Kepuasan</th>
                    <th style="width: 90px;">Paraf Tamu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $index => $v)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $v->created_at->format('H:i') }} WIB</td>
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
                            {{ $v->tingkat_kepuasan }} / 5 &#9733;
                        </span>
                    </td>
                    <td style="height: 35px; border-bottom: 1px dashed #cbd5e1;"></td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 24px; color: #94a3b8;">
                        Tidak ada data pengunjung yang tercatat pada tanggal {{ $formattedDate }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan Resmi -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Purbalingga, {{ $formattedDate }}<br>Petugas Pelapor Front Office,</p>
                <div class="signature-space"></div>
                <p class="signature-name petugas-name-display">{{ $petugasName }}</p>
                <p style="font-size: 8.5pt; color: #64748b;">Petugas Layanan Informasi & Tamu</p>
            </div>
        </div>
    </div>

    <script>
        function updatePetugas(name) {
            document.querySelectorAll('.petugas-name-display').forEach(function(el) {
                el.textContent = name;
            });
        }
    </script>
</body>
</html>
