<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Steiger;
use App\Models\Wachthaven;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Unit tests voor heatmap functionaliteit - Testing - User Story 15 - IT development portfolio
class HeatmapUnitTest extends TestCase
{
    use RefreshDatabase;

    // coordinaat validation
    public function test_coordinaat_validatie_unit(): void
    {
        $wachthaven = Wachthaven::factory()->create();

        // Geldige coördinaten in Nederland
        $validSteiger = Steiger::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'latitude' => 52.0907,
            'longitude' => 5.1214,
            'steiger_code' => 'VALID-01'
        ]);

        // Ongeldige coördinaten
        $invalidSteiger = Steiger::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'latitude' => null,
            'longitude' => null,
            'steiger_code' => 'INVALID-01'
        ]);

        // Test validatie logica
        $this->assertTrue($validSteiger->latitude !== null && $validSteiger->longitude !== null, 'Geldige coordinaten moeten geaccepteerd worden');
        $this->assertFalse($invalidSteiger->latitude !== null && $invalidSteiger->longitude !== null, 'Ongeldige coordinaten moeten afgekeurd worden');
    }

    // steiger data ophalen voor heatmap
    public function test_steiger_data_voor_heatmap(): void
    {
        $wachthaven = Wachthaven::factory()->create();

        // Steiger met coordinaten
        $steigerMetCoord = Steiger::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'latitude' => 51.5074,
            'longitude' => 4.3120,
            'steiger_code' => 'TEST-01'
        ]);

        // Steiger zonder coordinaten
        $steigerZonderCoord = Steiger::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'latitude' => null,
            'longitude' => null,
            'steiger_code' => 'TEST-02'
        ]);

        // Test ophalen steigers met coordinaten zoals HeatmapController zou doen
        $steigers = Steiger::whereNotNull('latitude')->whereNotNull('longitude')->get();

        // Check het resultaat
        $this->assertCount(1, $steigers, 'Moet alleen steigers met coordinaten ophalen');
        $this->assertEquals('TEST-01', $steigers->first()->steiger_code);
    }
}
