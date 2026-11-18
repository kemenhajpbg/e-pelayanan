<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak TTB - {{ $tandaTerima->nomor_tanda_terima }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Standar Kertas Thermal 80mm */
        @page {
            size: 80mm auto;
            margin: 2mm 3mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.35;
            color: #000000;
            background-color: #f1f5f9;
            margin: 0;
            padding: 15px 0;
        }

        /* Container Struk Thermal 80mm */
        .thermal-ticket {
            width: 78mm;
            max-width: 78mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 5mm 4mm;
            border-radius: 4px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Tombol Aksi Layar (Disembunyikan saat cetak) */
        .no-print {
            max-width: 78mm;
            margin: 0 auto 12px auto;
            display: flex;
            gap: 8px;
        }

        .btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .btn-print {
            background-color: #0d9488;
            color: #ffffff;
        }

        .btn-close {
            background-color: #e2e8f0;
            color: #334155;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Header / Kop Instansi */
        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .logo-wrap {
            margin-bottom: 4px;
        }

        .logo-wrap img {
            height: 38px;
            width: auto;
            filter: grayscale(100%) contrast(150%);
        }

        .instansi-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 0;
        }

        .instansi-sub {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 1px 0 0 0;
        }

        .doc-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 6px 0 2px 0;
            letter-spacing: 0.5px;
        }

        /* Garis Putus-putus Pemisah Thermal */
        .divider {
            border-top: 1px dashed #000000;
            margin: 6px 0;
        }

        .divider-double {
            border-top: 1px dashed #000000;
            border-bottom: 1px dashed #000000;
            height: 3px;
            margin: 6px 0;
        }

        /* Baris Informasi */
        .info-row {
            margin-bottom: 4px;
        }

        .info-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #111111;
        }

        .info-value {
            font-size: 11px;
            font-weight: 700;
            word-break: break-word;
        }

        .info-value.nomor-ttb {
            font-size: 13px;
            font-weight: 800;
            font-family: monospace;
            letter-spacing: 0.5px;
        }

        /* Daftar Berkas */
        .berkas-list {
            margin: 4px 0 6px 0;
            padding-left: 0;
            list-style: none;
        }

        .berkas-item {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 3px;
            display: flex;
            align-items: flex-start;
            gap: 4px;
        }

        .berkas-check {
            font-weight: 800;
            font-size: 11px;
        }

        /* Tanda Tangan */
        .sig-container {
            text-align: center;
            margin-top: 8px;
            margin-bottom: 6px;
        }

        .sig-image {
            max-height: 48px;
            max-width: 140px;
            object-fit: contain;
            display: block;
            margin: 0 auto 3px auto;
            filter: contrast(180%);
        }

        .sig-name {
            font-size: 10px;
            font-weight: 700;
            text-decoration: underline;
        }

        /* QR Code & Footer */
        .qr-section {
            text-align: center;
            margin-top: 6px;
        }

        .qr-section img {
            width: 72px;
            height: 72px;
            margin: 0 auto;
            display: block;
        }

        .footer-note {
            text-align: center;
            font-size: 9px;
            font-style: italic;
            margin-top: 6px;
            line-height: 1.25;
        }

        /* Rules saat cetak printer fisik */
        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }
            .thermal-ticket {
                box-shadow: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Action Toolbar for Screen Preview -->
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fa-solid fa-print"></i> Cetak Struk
        </button>
        <button onclick="window.close()" class="btn btn-close">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
    </div>

    <!-- Tiket / Struk Thermal 80 mm -->
    <div class="thermal-ticket">

        <!-- KOP KEMENTERIAN HAJI DAN UMRAH -->
        <div class="header">
            <div class="logo-wrap">
                <img src="{{ asset('images/logo-kemenhaj.svg') }}" alt="Logo Kemenhaj">
            </div>
            <h1 class="instansi-title">KEMENTERIAN HAJI DAN UMRAH</h1>
            <h2 class="instansi-sub">KABUPATEN PURBALINGGA</h2>
            <div class="divider"></div>
            <h3 class="doc-title">TANDA TERIMA BERKAS</h3>
        </div>

        <div class="divider"></div>

        <!-- NOMOR TANDA TERIMA -->
        <div class="info-row">
            <div class="info-label">Nomor Tanda Terima:</div>
            <div class="info-value nomor-ttb">{{ $tandaTerima->nomor_tanda_terima }}</div>
        </div>

        <!-- NAMA PENYETOR -->
        <div class="info-row">
            <div class="info-label">Nama Penyetor:</div>
            <div class="info-value">{{ $tandaTerima->nama_penyetor }}</div>
        </div>

        <!-- NOMOR HP -->
        <div class="info-row">
            <div class="info-label">No. HP:</div>
            <div class="info-value">{{ $tandaTerima->no_hp }}</div>
        </div>

        <!-- TANGGAL & WAKTU SETOR -->
        <div class="info-row">
            <div class="info-label">Tanggal:</div>
            <div class="info-value">{{ $tandaTerima->formatted_tanggal }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Waktu:</div>
            <div class="info-value font-mono">{{ $tandaTerima->formatted_waktu }}</div>
        </div>

        <div class="divider"></div>

        <!-- BERKAS YANG DISETORKAN -->
        <div class="info-label">BERKAS YANG DISETORKAN:</div>
        <ul class="berkas-list">
            @foreach($tandaTerima->berkas as $item)
            <li class="berkas-item">
                <span class="berkas-check">✓</span>
                <span>{{ $item->jenis_berkas }}</span>
            </li>
            @endforeach
        </ul>

        @if($tandaTerima->keterangan)
        <div class="info-row" style="margin-top: 4px;">
            <div class="info-label">Keterangan:</div>
            <div class="info-value" style="font-weight: 500; font-size: 10px;">{{ $tandaTerima->keterangan }}</div>
        </div>
        @endif

        <div class="divider"></div>

        <!-- TANDA TANGAN DIGITAL PENYETOR -->
        <div class="sig-container">
            <div class="info-label" style="margin-bottom: 2px;">Tanda Tangan Penyetor:</div>
            @if($tandaTerima->tanda_tangan)
            <img src="{{ $tandaTerima->tanda_tangan }}" alt="Tanda Tangan" class="sig-image">
            @endif
            <div class="sig-name">{{ $tandaTerima->nama_penyetor }}</div>
        </div>

        <div class="divider"></div>

        <!-- PETUGAS PELAYANAN -->
        <div class="info-row" style="text-align: center;">
            <div class="info-label">Petugas:</div>
            <div class="info-value">{{ $tandaTerima->petugas ? $tandaTerima->petugas->name : 'Admin Pelayanan' }}</div>
        </div>

        <!-- QR CODE VERIFIKASI -->
        <div class="qr-section">
            @php
                $qrUrl = route('tanda-terima.show', $tandaTerima->id);
                $qrApi = "https://api.qrserver.com/v1/create-qr-code/?size=90x90&margin=0&data=" . urlencode($qrUrl);
            @endphp
            <img src="{{ $qrApi }}" alt="QR Code TTB" onerror="this.style.display='none'">
            <div style="font-size: 8px; color: #555; margin-top: 2px; font-family: monospace;">{{ $tandaTerima->nomor_tanda_terima }}</div>
        </div>

        <!-- PESAN KETENTUAN HUKUM -->
        <div class="footer-note">
            "Dokumen ini merupakan bukti penerimaan berkas."
        </div>

        <div style="text-align: center; font-size: 8px; color: #777; margin-top: 6px;">
            E-Pelayanan Kemenhaj Purbalingga
        </div>

    </div>

    <script>
        // Otomatis memicu dialog cetak saat halaman dimuat
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
