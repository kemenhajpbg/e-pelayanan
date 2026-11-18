<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Tanda Terima Berkas - {{ $labelPeriode }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <style>
        body {
            font-family: 'Instrument Sans', Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
            font-size: 10pt;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            border-bottom: 3px double #0f172a;
            padding-bottom: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2 {
            font-size: 13pt;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }

        .kop-text h3 {
            font-size: 12pt;
            font-weight: 700;
            margin: 2px 0 0 0;
            text-transform: uppercase;
            color: #0f172a;
        }

        .kop-text p {
            font-size: 8.5pt;
            margin: 3px 0 0 0;
            color: #475569;
        }

        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h1 {
            font-size: 13pt;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }

        .report-title p {
            font-size: 9.5pt;
            font-weight: 600;
            margin: 4px 0 0 0;
            color: #0d9488;
        }

        /* Kotak Ringkasan */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            margin-bottom: 18px;
        }

        .stat-card {
            border: 1px solid #cbd5e1;
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            background: #f8fafc;
        }

        .stat-label {
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
        }

        .stat-val {
            font-size: 13pt;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2px;
        }

        /* Tabel Rekap Berkas */
        .rekap-berkas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 8.5pt;
        }

        .rekap-berkas-table th, .rekap-berkas-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
        }

        .rekap-berkas-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: left;
        }

        /* Tabel Data Transaksi */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 8.5pt;
        }

        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
        }

        .data-table th {
            background-color: #e2e8f0;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            font-size: 8pt;
        }

        /* Area Tanda Tangan */
        .ttd-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 250px;
            text-align: center;
            font-size: 9pt;
        }

        .ttd-space {
            height: 65px;
        }

        .ttd-name {
            font-weight: 700;
            text-decoration: underline;
        }

        /* Tombol Aksi */
        .action-bar {
            max-width: 960px;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-print {
            background: #0d9488;
            color: #ffffff;
        }

        .btn-close {
            background: #cbd5e1;
            color: #1e293b;
        }

        @media print {
            body {
                background: none;
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

    <!-- Tombol Aksi Layar -->
    <div class="action-bar">
        <button onclick="window.print()" class="btn btn-print">Cetak Laporan / Simpan PDF</button>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>

    <div class="container">

        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-kemenhaj.svg') }}" alt="Logo Kemenhaj" class="kop-logo">
            <div class="kop-text">
                <h2>KEMENTERIAN HAJI DAN UMRAH REPUBLIK INDONESIA</h2>
                <h3>KANTOR KABUPATEN PURBALINGGA</h3>
                <p>Jl. Mayjen Sungkono No. 12, Selabaya, Kec. Kalimanah, Kabupaten Purbalingga, Jawa Tengah 53371</p>
                <p>Layanan Informasi & Administrasi Penyelenggaraan Ibadah Haji dan Umrah Terpadu</p>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title">
            <h1>LAPORAN REKAPITULASI PENERIMAAN BERKAS PELAYANAN</h1>
            <p>Periode: {{ $labelPeriode }}</p>
        </div>

        <!-- Ringkasan Statistik Status -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total</div>
                <div class="stat-val">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Diterima</div>
                <div class="stat-val">{{ $stats['diterima'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Diproses</div>
                <div class="stat-val">{{ $stats['diproses'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Selesai</div>
                <div class="stat-val">{{ $stats['selesai'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Kembali</div>
                <div class="stat-val">{{ $stats['dikembalikan'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Batal</div>
                <div class="stat-val">{{ $stats['dibatalkan'] }}</div>
            </div>
        </div>

        <!-- Rincian Jumlah per Jenis Berkas -->
        <h4 style="font-size: 9pt; margin: 0 0 6px 0; text-transform: uppercase;">A. Rekapitulasi Berdasarkan Kategori Berkas:</h4>
        <table class="rekap-berkas-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Kategori / Jenis Berkas</th>
                    <th style="width: 120px; text-align: center;">Jumlah Berkas</th>
                    <th style="width: 100px; text-align: center;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalBerkas = array_sum($jenisBerkasCounts);
                    $no = 1;
                @endphp
                @foreach($jenisBerkasCounts as $jenis => $count)
                @php
                    $pct = $grandTotalBerkas > 0 ? round(($count / $grandTotalBerkas) * 100, 1) : 0;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td><strong>{{ $jenis }}</strong></td>
                    <td style="text-align: center; font-weight: 700;">{{ $count }}</td>
                    <td style="text-align: center;">{{ $pct }}%</td>
                </tr>
                @endforeach
                <tr style="background-color: #f8fafc; font-weight: 800;">
                    <td colspan="2" style="text-align: right;">TOTAL BERKAS:</td>
                    <td style="text-align: center;">{{ $grandTotalBerkas }}</td>
                    <td style="text-align: center;">100%</td>
                </tr>
            </tbody>
        </table>

        <!-- Tabel Detail Transaksi -->
        <h4 style="font-size: 9pt; margin: 15px 0 6px 0; text-transform: uppercase;">B. Daftar Rincian Tanda Terima:</h4>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25px; text-align: center;">No</th>
                    <th style="width: 110px;">Nomor TTB</th>
                    <th style="width: 120px;">Waktu Setor</th>
                    <th>Nama Penyetor</th>
                    <th style="width: 85px;">No. HP</th>
                    <th>Berkas Yang Disetorkan</th>
                    <th style="width: 70px; text-align: center;">Status</th>
                    <th style="width: 80px;">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $idx => $r)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-family: monospace; font-weight: 700;">{{ $r->nomor_tanda_terima }}</td>
                    <td>{{ $r->formatted_short_date }}<br><span style="font-size: 7.5pt; color: #64748b;">{{ $r->formatted_short_time }}</span></td>
                    <td><strong>{{ $r->nama_penyetor }}</strong></td>
                    <td>{{ $r->no_hp }}</td>
                    <td>
                        {{ $r->berkas->pluck('jenis_berkas')->implode(', ') }}
                    </td>
                    <td style="text-align: center; font-weight: 700;">{{ $r->status }}</td>
                    <td>{{ $r->petugas ? $r->petugas->name : 'Petugas' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px; color: #64748b;">
                        Tidak ada transaksi tanda terima pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Area Pengesahan Tanda Tangan -->
        <div class="ttd-section">
            <div class="ttd-box">
                <p>Mengetahui,<br>Kepala Seksi Penyelenggaraan Haji & Umrah</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">H. Kemenhaj Purbalingga, S.Ag.</p>
                <p style="font-size: 8pt; color: #64748b; margin-top: 2px;">NIP. 19780512 200501 1 003</p>
            </div>

            <div class="ttd-box">
                <p>Purbalingga, {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}<br>Petugas Pelayanan Front Office,</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $petugasName }}</p>
                <p style="font-size: 8pt; color: #64748b; margin-top: 2px;">Petugas Pelayanan E-Kemenhaj</p>
            </div>
        </div>

    </div>

    <script>
        window.addEventListener('load', function () {
            // Uncomment jika ingin auto-print saat halaman laporan dibuka
            // window.print();
        });
    </script>
</body>
</html>
