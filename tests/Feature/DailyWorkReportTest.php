<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\DailyWorkReport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DailyWorkReportTest extends TestCase
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
        $response = $this->get('/daily-reports');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_daily_reports_page(): void
    {
        $response = $this->actingAs($this->user)->get('/daily-reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan Kinerja Harian');
        $response->assertSee('Rekapitulasi Bulanan');
    }

    public function test_authenticated_user_can_view_monthly_tab(): void
    {
        $response = $this->actingAs($this->user)->get('/daily-reports?tab=monthly&bulan=9&tahun=2026');
        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Kinerja Bulanan');
        $response->assertSee('Cetak Rekap (PDF)');
        $response->assertSee('Unduh CSV/Excel');
    }

    public function test_authenticated_user_can_store_daily_work_report(): void
    {
        $data = [
            'tanggal' => date('Y-m-d'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '10:30',
            'kegiatan' => 'Uji coba pelayanan pendaftaran haji',
            'kategori' => 'Pelayanan Front Office',
            'output_hasil' => '1 Berkas tervalidasi',
            'volume' => 1,
            'satuan' => 'Berkas',
            'status' => 'selesai',
            'keterangan' => 'Uji coba otomatis',
        ];

        $response = $this->actingAs($this->user)->post('/daily-reports', $data);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('daily_work_reports', [
            'kegiatan' => 'Uji coba pelayanan pendaftaran haji',
            'kategori' => 'Pelayanan Front Office',
        ]);
    }

    public function test_authenticated_user_can_view_print_monthly_page(): void
    {
        $response = $this->actingAs($this->user)->get('/daily-reports/print-monthly?bulan=9&tahun=2026');
        $response->assertStatus(200);
        $response->assertSee('Kementerian Haji dan Umrah Republik Indonesia');
        $response->assertSee('Laporan Rekapitulasi Capaian Kinerja Harian');
    }

    public function test_authenticated_user_can_export_monthly_csv(): void
    {
        $response = $this->actingAs($this->user)->get('/daily-reports/export-monthly?bulan=9&tahun=2026');
        $response->assertStatus(200);
        $this->assertTrue($response->headers->get('content-type') === 'text/csv; charset=UTF-8');
    }

    public function test_letters_url_redirects_to_daily_reports(): void
    {
        $response = $this->actingAs($this->user)->get('/letters');
        $response->assertRedirect(route('daily-reports.index'));
    }

    public function test_dashboard_displays_daily_work_report_metrics(): void
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Kinerja Hari Ini');
        $response->assertSee('Kinerja Bulan Ini');
    }
}
