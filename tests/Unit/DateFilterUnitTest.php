<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\EvenementController;
use App\Models\Evenement;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

// Unit tests voor datumfilter. Dit is voor User Story 2 - IT development portfolio Testing
class DateFilterUnitTest extends TestCase
{
    use RefreshDatabase;

    // Datumvalidatie functie
    public function test_datum_validatie_unit(): void
    {
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        $eventInRange = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00'
        ]);

        $eventOutside = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-01-15 10:00:00'
        ]);

        // Test de applyDateFilter functie
        $request = Request::create('/test', 'GET', [
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-31'
        ]);
        app()->instance('request', $request);

        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Checkt het resultaat
        $this->assertCount(1, $results, 'Moet 1 event binnen bereik teruggeven');
        $this->assertEquals($eventInRange->evenement_id, $results->first()->evenement_id);
    }

    // Filter functie met lege parameters
    public function test_filter_zonder_parameters(): void
    {
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        Evenement::factory()->count(3)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Geen filter parameters
        $request = Request::create('/test', 'GET', []);
        app()->instance('request', $request);

        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Moet alle events teruggeven
        $this->assertCount(3, $results, 'Zonder filter moet alle data getoond worden');
    }
}
