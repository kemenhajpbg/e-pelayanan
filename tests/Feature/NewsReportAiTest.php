<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\NewsReport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsReportAiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_generate_ai_news(): void
    {
        $response = $this->post('/news/generate-ai', [
            'kegiatan' => 'Sosialisasi Haji',
            'tempat_waktu' => 'Purbalingga, 9 Sep 2026',
            'garis_besar' => 'Materi pendaftaran',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_generate_ai_news_validation_fails_if_fields_missing(): void
    {
        $response = $this->actingAs($this->user)->postJson('/news/generate-ai', []);

        $response->assertStatus(422);
        $this->assertArrayHasKey('kegiatan', $response->json('errors'));
        $this->assertArrayHasKey('tempat_waktu', $response->json('errors'));
        $this->assertArrayHasKey('garis_besar', $response->json('errors'));
    }

    public function test_generate_ai_news_succeeds_with_valid_inputs(): void
    {
        $response = $this->actingAs($this->user)->postJson('/news/generate-ai', [
            'kegiatan' => 'Sosialisasi Sertifikasi Halal Gratis bagi Pelaku Usaha Kuliner',
            'tempat_waktu' => 'Aula Kemenhaj Purbalingga, Rabu 9 September 2026',
            'garis_besar' => 'Diikuti 50 pelaku UMKM, pemaparan syarat sertifikasi halal Sehati, penggunaan aplikasi SiHalal, dan komitmen percepatan izin halal.',
            'provider' => 'auto',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data['judul']);
        $this->assertNotEmpty($data['narasi']);
        $this->assertNotEmpty($data['kategori']);
        $this->assertStringContainsString('Kemenhaj Purbalingga', $data['judul']);
        $this->assertStringContainsString('Aula Kemenhaj Purbalingga', $data['narasi']);
    }

    public function test_store_news_report_with_ai_data(): void
    {
        $response = $this->actingAs($this->user)->post('/news', [
            'judul' => 'Kemenhaj Purbalingga Gelar Sosialisasi Sertifikasi Halal Gratis',
            'tanggal_tayang' => date('Y-m-d'),
            'kategori' => 'Edukasi',
            'narasi' => 'Kantor Kementerian Haji dan Umrah Kabupaten Purbalingga sukses menyelenggarakan agenda sosialisasi sertifikasi halal.',
            'link_berita' => 'https://purbalingga.kemenhaj.go.id/berita/halal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('news_reports', [
            'judul' => 'Kemenhaj Purbalingga Gelar Sosialisasi Sertifikasi Halal Gratis',
            'kategori' => 'Edukasi',
        ]);
    }
}
