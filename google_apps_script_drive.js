/**
 * ==============================================================================
 * GOOGLE APPS SCRIPT: SINKRONISASI BUKU TAMU KE GOOGLE DRIVE & GOOGLE SPREADSHEET
 * UNTUK AKUN: seksiphupbg@gmail.com
 * Kantor Kementerian Haji dan Umrah Kabupaten Purbalingga
 * ==============================================================================
 * 
 * CARA MEMASANG DI AKUN GOOGLE (seksiphupbg@gmail.com):
 * 1. Login ke akun Google: seksiphupbg@gmail.com
 * 2. Buka https://drive.google.com atau buat Spreadsheet baru dengan judul:
 *    "Buku Tamu PTSP Kemenhaj Purbalingga"
 * 3. Di Google Spreadsheet tersebut, klik menu:
 *    Extensions (Ekstensi) > Apps Script
 * 4. Hapus seluruh kode default yang ada di editor Apps Script, lalu salin dan tempel
 *    (copy-paste) seluruh isi skrip ini.
 * 5. Klik tombol "Simpan" (ikon disket).
 * 6. Klik tombol "Deploy" (Terapkan) > "New deployment" (Penerapan baru).
 * 7. Pilih tipe: "Web app" (Aplikasi web).
 * 8. Konfigurasi Deployment:
 *    - Description: Webhook Buku Tamu E-Pelayanan
 *    - Execute as: Me (seksiphupbg@gmail.com)
 *    - Who has access: Anyone (Siapa saja, bahkan anonim) -> AGAR APLIKASI WEB BISA MENGIRIM DATA
 * 9. Klik "Deploy". Berikan izin (Authorize access) pada akun Google Anda.
 * 10. Salin "Web app URL" (contoh: https://script.google.com/macros/s/.../exec)
 * 11. Masukkan URL tersebut ke file .env aplikasi Anda:
 *     GOOGLE_SHEET_WEBHOOK_URL="https://script.google.com/macros/s/.../exec"
 */

function doPost(e) {
  try {
    var ss = SpreadsheetApp.getActiveSpreadsheet();
    var sheet = ss.getSheetByName("Buku Tamu");

    // Jika tab "Buku Tamu" belum ada, buat otomatis beserta headernya
    if (!sheet) {
      sheet = ss.insertSheet("Buku Tamu");
      sheet.appendRow([
        "Timestamp",
        "Nama Pengunjung",
        "Alamat Lengkap",
        "No. HP / WA",
        "Kelompok Usia",
        "Keperluan",
        "Tingkat Kepuasan",
        "URL Foto Pelayanan"
      ]);
      sheet.getRange(1, 1, 1, 8).setFontWeight("bold").setBackground("#0d9488").setFontColor("#ffffff");
      sheet.setFrozenRows(1);
    }

    var data = JSON.parse(e.postData.contents);

    // Tambahkan baris baru
    sheet.appendRow([
      data.timestamp || new Date().toISOString(),
      data.nama || "-",
      data.alamat || "-",
      "'" + (data.no_hp || "-"), // Beri tanda petik satu di depan agar format nomor telepon tidak terpotong
      data.kelompok_usia || "-",
      data.keperluan || "-",
      data.tingkat_kepuasan || "-",
      data.foto_url || "-"
    ]);

    return ContentService.createTextOutput(JSON.stringify({
      status: "success",
      message: "Data pengunjung berhasil dicatat di Google Drive / Spreadsheet seksiphupbg@gmail.com"
    })).setMimeType(ContentService.MimeType.JSON);

  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({
      status: "error",
      message: error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

function doGet(e) {
  return ContentService.createTextOutput("Webhook Buku Tamu Google Drive seksiphupbg@gmail.com aktif dan siap menerima data!");
}
