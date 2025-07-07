<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengajuan_store_requires_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/pengajuan', [
            'jumlah' => 50000, // kurang dari min:100000
            'alasan' => 'Pendek' // kurang dari min:10 karakter
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['jumlah', 'alasan']);
    }

    public function test_pengajuan_store_successful()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/pengajuan', [
            'jumlah' => 2000000,
            'alasan' => 'Modal untuk memperluas usaha gorengan',
             'bank'   => 'Bank BCA',
              'logo_url' => 'https://example.com/logo.png',
              'tenor'     => 12
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('pengajuans', [
            'user_id' => $user->id,
            'jumlah' => 2000000
        ]);
    }
}
