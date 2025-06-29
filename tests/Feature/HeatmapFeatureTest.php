<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Steiger;
use App\Models\Wachthaven;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Feature tests voor  kaart/heatmap. Dit is voor User Story 15 - IT development portfolio Testing
class HeatmapFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Login een test gebruiker
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    //  Happy path, kijk of de heatmap pagina laadt
    public function test_heatmap_happy_path(): void
    {
        $wachthaven = Wachthaven::factory()->create();

        // Steigers met geldige coördinaten
        Steiger::factory()->count(3)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'latitude' => 51.5074,
            'longitude' => 4.3120,
            'steiger_code' => 'TEST-01'
        ]);

        $response = $this->get('/heatmap');

        // Check of de pagina ook echt succesvol laadt
        $response->assertStatus(200);
        $response->assertViewIs('heatmap.index');

        // Check dat er steigers bestaan in database
        $steigers = Steiger::whereNotNull('latitude')->whereNotNull('longitude')->get();
        $this->assertCount(3, $steigers, 'Moet 3 steigers in database hebben');
    }

    // Unhappy path - heatmap pagina laadt zonder data
    public function test_heatmap_unhappy_path(): void
    {
        $response = $this->get('/heatmap');

        // Kijk resultaat, pagina laadt wel, maar geen data
        $response->assertStatus(200);
        $response->assertViewIs('heatmap.index');

        // Check dat er geen steigers zijn
        $steigers = Steiger::whereNotNull('latitude')->whereNotNull('longitude')->get();
        $this->assertCount(0, $steigers, 'Moet 0 steigers in database hebben');
    }

    // Exception path - steigers zonder geldige coördinaten
    public function test_heatmap_exception_path(): void
    {
        $wachthaven = Wachthaven::factory()->create();

        // Steiger met geldige coordinaten
        Steiger::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'latitude' => 51.5074,
            'longitude' => 4.3120,
            'steiger_code' => 'VALID-01'
        ]);

        // Steigers met NULL coordinaten
        Steiger::factory()->count(2)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'latitude' => null,
            'longitude' => null,
            'steiger_code' => 'INVALID-01'
        ]);

        // Test de heatmap pagina
        $response = $this->get('/heatmap');

        // Kijk of de pagina laadt
        $response->assertStatus(200);

        // Check dat alleen geldige steigers beschikbaar zijn
        $validSteigers = Steiger::whereNotNull('latitude')->whereNotNull('longitude')->get();
        $this->assertCount(1, $validSteigers, 'Moet alleen steigers met geldige coordinaten hebben');

        $validSteiger = $validSteigers->first();
        $this->assertEquals('VALID-01', $validSteiger->steiger_code);
    }
}
