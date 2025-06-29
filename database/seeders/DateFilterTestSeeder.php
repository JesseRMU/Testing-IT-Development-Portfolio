<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evenement;
use App\Models\Wachthaven;
use App\Models\Steiger;

/**
 * Test Data Seeder for Date Filter Testing
 *
 * Creates 50 test records spread across 2024 with some outside 2024 for edge cases
 * This seeder supports the User Story 1 date filter testing requirements
 */
class DateFilterTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have test wachthavens and steigers
        $wachthaven = Wachthaven::factory()->create([
            'wachthaven_naam' => 'Test Wachthaven voor Datumfilter'
        ]);

        $steiger = Steiger::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_code' => 'TEST-STEIGER-01'
        ]);

        // Create 40 events spread across 2024
        echo "Creating 40 events for 2024...\n";

        // Q1 2024 - 10 events
        Evenement::factory()->count(10)->betweenDates('2024-01-01', '2024-03-31')->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Q2 2024 - 10 events
        Evenement::factory()->count(10)->betweenDates('2024-04-01', '2024-06-30')->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Q3 2024 - 10 events
        Evenement::factory()->count(10)->betweenDates('2024-07-01', '2024-09-30')->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Q4 2024 - 10 events
        Evenement::factory()->count(10)->betweenDates('2024-10-01', '2024-12-31')->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Create 5 events outside 2024 for edge case testing
        echo "Creating 5 events outside 2024 for edge cases...\n";

        // 3 events in 2023
        Evenement::factory()->count(3)->betweenDates('2023-01-01', '2023-12-31')->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // 2 events in 2025
        Evenement::factory()->count(2)->betweenDates('2025-01-01', '2025-12-31')->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Create 5 additional events with specific scenarios for testing
        echo "Creating 5 specific scenario events...\n";

        // Event on leap year day (2024-02-29)
        Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-29 12:00:00',
            'evenement_eind_datum' => '2024-03-01 12:00:00',
            'naam_ivs90_bestand' => 'leap_year_test.txt',
        ]);

        // Event at year boundary (New Year's Eve -> New Year)
        Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-12-31 23:30:00',
            'evenement_eind_datum' => '2025-01-01 01:30:00',
            'naam_ivs90_bestand' => 'year_boundary_test.txt',
        ]);

        // Event with same start and end date (same day)
        Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-06-15 08:00:00',
            'evenement_eind_datum' => '2024-06-15 18:00:00',
            'naam_ivs90_bestand' => 'same_day_test.txt',
        ]);

        // Event with very long duration (multiple weeks)
        Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-08-01 10:00:00',
            'evenement_eind_datum' => '2024-08-21 16:00:00',
            'naam_ivs90_bestand' => 'long_duration_test.txt',
        ]);

        // Event with NULL end date (ongoing event)
        Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-11-15 09:00:00',
            'evenement_eind_datum' => null,
            'naam_ivs90_bestand' => 'ongoing_event_test.txt',
        ]);

        echo "DateFilterTestSeeder completed successfully!\n";
        echo "Created 50 total events:\n";
        echo "- 40 events in 2024 (spread across all quarters)\n";
        echo "- 5 events outside 2024 (3 in 2023, 2 in 2025)\n";
        echo "- 5 special scenario events for edge case testing\n";
        echo "All events are linked to test wachthaven: {$wachthaven->wachthaven_naam}\n";
    }
}
