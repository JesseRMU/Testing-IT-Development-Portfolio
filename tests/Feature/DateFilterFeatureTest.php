<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Evenement;
use App\Models\User;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Feature tests voor datumfilter. Dit is voor user story 2 - Testing - IT development
class DateFilterFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Login een test gebruiker
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    // Happy path. normale datum selectie
    public function test_datum_filter_happy_path(): void
    {
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Events in maart moeten getoond worden
        Evenement::factory()->count(3)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00',
        ]);

        // Events in januari moeten NIET getoond worden
        Evenement::factory()->count(2)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-01-15 10:00:00',
        ]);

        // Test voor de filter
        $response = $this->get('/evenementen?startDate=2024-03-01&endDate=2024-03-31');

        // Kijk naar het resultaat
        $response->assertStatus(200);
        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(3, $evenementen->count(), 'Moet 3 events uit maart tonen');
    }

    // Unhappy path, geen data in datumbereik
    public function test_datum_filter_unhappy_path(): void
    {
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Alleen events in januari
        Evenement::factory()->count(2)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-01-15 10:00:00',
        ]);

        // Filter op maart (geen data)
        $response = $this->get('/evenementen?startDate=2024-03-01&endDate=2024-03-31');

        // Check resultaat
        $response->assertStatus(200);
        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(0, $evenementen->count(), 'Moet 0 events tonen als er geen data is');
    }

    //  Exception path - verkeerde datum volgorde
    public function test_datum_filter_exception_path(): void
    {
        // Setup testdata
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        Evenement::factory()->count(2)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00',
        ]);

        // Start datum NA eind datum
        $response = $this->get('/evenementen?startDate=2024-03-31&endDate=2024-03-01');

        // Check resultaat
        $response->assertStatus(200);
        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(0, $evenementen->count(), 'Verkeerde datum volgorde moet 0 results geven');
    }
}
