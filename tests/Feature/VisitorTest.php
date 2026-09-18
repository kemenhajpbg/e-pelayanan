<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VisitorTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/visitors');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_visitor_page(): void
    {
        $response = $this->actingAs($this->user)->get('/visitors');
        $response->assertStatus(200);
        $response->assertSee('Buku Tamu / Pengunjung');
        $response->assertSee('Cetak Harian (PDF)');
    }

    public function test_authenticated_user_can_filter_visitor_by_date(): void
    {
        $todayVisitor = Visitor::create([
            'nama' => 'Tamu Hari Ini',
            'alamat' => 'Purbalingga',
            'no_hp' => '08123456789',
            'kelompok_usia' => 'Dewasa',
            'keperluan' => 'pendaftaran',
            'tingkat_kepuasan' => 5,
        ]);

        $response = $this->actingAs($this->user)->get('/visitors?tanggal=' . date('Y-m-d'));
        $response->assertStatus(200);
        $response->assertSee('Tamu Hari Ini');
    }

    public function test_authenticated_user_can_view_print_daily_page(): void
    {
        Visitor::create([
            'nama' => 'Budi Santoso',
            'alamat' => 'Kalimanah, Purbalingga',
            'no_hp' => '08129876543',
            'kelompok_usia' => 'Dewasa',
            'keperluan' => 'konsultasi',
            'tingkat_kepuasan' => 5,
        ]);

        $response = $this->actingAs($this->user)->get('/visitors/print-daily?tanggal=' . date('Y-m-d'));
        $response->assertStatus(200);
        $response->assertSee('Buku Register Pengunjung Pelayanan Terpadu');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Paraf Tamu');
    }

    public function test_authenticated_user_can_store_visitor(): void
    {
        $data = [
            'nama' => 'Siti Nurhaliza',
            'alamat' => 'Bobotsari, Purbalingga',
            'no_hp' => '08134567890',
            'kelompok_usia' => 'Remaja',
            'keperluan' => 'pelimpahan',
            'tingkat_kepuasan' => 4,
        ];

        $response = $this->actingAs($this->user)->post('/visitors', $data);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('visitors', [
            'nama' => 'Siti Nurhaliza',
            'keperluan' => 'pelimpahan',
        ]);
    }

    public function test_authenticated_user_can_view_monthly_recap_tab(): void
    {
        Visitor::create([
            'nama' => 'Ahmad Bulanan',
            'alamat' => 'Kutasari, Purbalingga',
            'no_hp' => '085211223344',
            'kelompok_usia' => 'Dewasa',
            'keperluan' => 'pendaftaran',
            'tingkat_kepuasan' => 5,
        ]);

        $response = $this->actingAs($this->user)->get('/visitors?tab=monthly&bulan=' . date('m') . '&tahun=' . date('Y'));
        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Bulanan Buku Tamu');
        $response->assertSee('Ahmad Bulanan');
        $response->assertSee('Cetak Rekap (PDF)');
        $response->assertSee('Unduh Spreadsheet');
        $response->assertSee('seksiphupbg@gmail.com');
    }

    public function test_authenticated_user_can_view_print_monthly_page(): void
    {
        Visitor::create([
            'nama' => 'Hj. Aminah',
            'alamat' => 'Bukateja, Purbalingga',
            'no_hp' => '087712345678',
            'kelompok_usia' => 'Lansia',
            'keperluan' => 'pelimpahan',
            'tingkat_kepuasan' => 5,
        ]);

        $response = $this->actingAs($this->user)->get('/visitors/print-monthly?bulan=' . date('m') . '&tahun=' . date('Y'));
        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi Register Pengunjung PTSP');
        $response->assertSee('Hj. Aminah');
        $response->assertSee('Kepala Seksi Penyelenggaraan Haji dan Umrah');
    }

    public function test_authenticated_user_can_export_monthly_spreadsheet(): void
    {
        Visitor::create([
            'nama' => 'Zaenal Arifin',
            'alamat' => 'Padamara, Purbalingga',
            'no_hp' => '081399887766',
            'kelompok_usia' => 'Dewasa',
            'keperluan' => 'konsultasi',
            'tingkat_kepuasan' => 5,
        ]);

        $response = $this->actingAs($this->user)->get('/visitors/export-monthly?bulan=' . date('m') . '&tahun=' . date('Y'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'rekap_buku_tamu_'));
    }

    public function test_authenticated_user_can_sync_monthly_to_google_drive_email(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        Visitor::create([
            'nama' => 'Farhan Hakim',
            'alamat' => 'Kejobong, Purbalingga',
            'no_hp' => '082133445566',
            'kelompok_usia' => 'Remaja',
            'keperluan' => 'pendaftaran',
            'tingkat_kepuasan' => 5,
        ]);

        $response = $this->actingAs($this->user)->post('/visitors/sync-drive-monthly', [
            'bulan' => date('m'),
            'tahun' => date('Y'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\VisitorMonthlyReportMail::class, function ($mail) {
            return $mail->hasTo('seksiphupbg@gmail.com');
        });
    }
}

