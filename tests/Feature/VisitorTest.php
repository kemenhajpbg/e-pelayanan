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
}
