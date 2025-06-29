<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Evenement;
use App\Models\User;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

/**
 * Feature tests for date filter functionality - User Story 1
 *
 * Tests complete HTTP requests to filter endpoints to ensure
 * the date filtering works correctly from a user perspective.
 */
class DateFilterFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user for protected routes
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test: Happy path - valid date range returns filtered data
     *
     * Scenario: User provides valid start and end date
     * Expected: Only events within date range are returned
     */
    public function test_valid_date_range_returns_filtered_events(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Events within filter range (March 2024) - should be returned
        $eventsInRange = Evenement::factory()->count(3)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00',
        ]);

        // Events outside filter range - should NOT be returned
        $eventsOutsideRange = Evenement::factory()->count(2)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-01-15 10:00:00',
        ]);

        $filterParams = [
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-31'
        ];

        // Act
        $response = $this->get('/evenementen?' . http_build_query($filterParams));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('evenement.index');
        $response->assertViewHas('evenementen');

        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(3, $evenementen->count(), 'Should return exactly 3 events in March 2024');

        // Verify all returned events are within date range
        foreach ($evenementen as $evenement) {
            $eventDate = Carbon::parse($evenement->evenement_begin_datum);
            $this->assertTrue(
                $eventDate->greaterThanOrEqualTo(Carbon::parse('2024-03-01')),
                'Event date should be >= start date'
            );
            $this->assertTrue(
                $eventDate->lessThanOrEqualTo(Carbon::parse('2024-03-31')),
                'Event date should be <= end date'
            );
        }
    }

    /**
     * Test: Unhappy path - no data exists in date range
     *
     * Scenario: User provides valid date range but no events exist in that period
     * Expected: Empty result set with successful response
     */
    public function test_no_data_in_date_range_returns_empty_result(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Create events only in January 2024 - outside our filter range
        Evenement::factory()->count(3)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-01-15 10:00:00',
        ]);

        $filterParams = [
            'startDate' => '2024-03-01',  // No events exist in March
            'endDate' => '2024-03-31'
        ];

        // Act
        $response = $this->get('/evenementen?' . http_build_query($filterParams));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('evenement.index');
        $response->assertViewHas('evenementen');

        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(0, $evenementen->count(), 'Should return no events when none exist in date range');
    }

    /**
     * Test: Exception path - start date after end date
     *
     * Scenario: User provides invalid date range (begin > end)
     * Expected: No results returned (logical handling)
     */
    public function test_invalid_date_range_start_after_end(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Create test events that would normally match
        Evenement::factory()->count(2)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00',
        ]);

        $invalidFilterParams = [
            'startDate' => '2024-03-31',  // Start AFTER end date
            'endDate' => '2024-03-01'     // End BEFORE start date
        ];

        // Act
        $response = $this->get('/evenementen?' . http_build_query($invalidFilterParams));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('evenement.index');

        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(0, $evenementen->count(), 'Invalid date range should return no results');
    }

    /**
     * Test: Happy path - date and time combination filtering
     *
     * Scenario: User provides both date and time filters
     * Expected: Events filtered by both date and time precisely
     */
    public function test_date_and_time_combination_filtering(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event within time range - should be returned
        $eventInRange = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 14:30:00'
        ]);

        // Event outside time range - should NOT be returned
        $eventOutsideRange = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 08:00:00'
        ]);

        $filterParams = [
            'startDate' => '2024-03-15',
            'startTime' => '12:00',
            'endDate' => '2024-03-15',
            'endTime' => '18:00'
        ];

        // Act
        $response = $this->get('/evenementen?' . http_build_query($filterParams));

        // Assert
        $response->assertStatus(200);
        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(1, $evenementen->count(), 'Should return only 1 event within time range');
        $this->assertEquals($eventInRange->evenement_id, $evenementen->first()->evenement_id);
    }

    /**
     * Test: Happy path - weekday filtering functionality
     *
     * Scenario: User filters events by specific weekday
     * Expected: Only events on specified weekday are returned
     */
    public function test_weekday_filtering(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Monday event (2024-03-04 is a Monday) - should be returned
        $mondayEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-04 10:00:00'
        ]);

        // Friday event (2024-03-08 is a Friday) - should NOT be returned
        $fridayEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-08 10:00:00'
        ]);

        $filterParams = ['weekday' => 1]; // Monday in JavaScript format

        // Act
        $response = $this->get('/evenementen?' . http_build_query($filterParams));

        // Assert
        $response->assertStatus(200);
        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(1, $evenementen->count(), 'Should return only Monday events');
        $this->assertEquals($mondayEvent->evenement_id, $evenementen->first()->evenement_id);
    }

    /**
     * Test: Happy path - available dates API endpoint
     *
     * Scenario: User requests available dates for calendar/picker
     * Expected: JSON response with all dates that have events
     */
    public function test_available_dates_endpoint(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        $testDates = ['2024-03-01', '2024-03-15', '2024-03-30'];

        foreach ($testDates as $date) {
            Evenement::factory()->create([
                'wachthaven_id' => $wachthaven->wachthaven_id,
                'steiger_id' => $steiger->steiger_id,
                'evenement_begin_datum' => $date . ' 10:00:00'
            ]);
        }

        // Act
        $response = $this->get('/api/evenementen/dates');

        // Assert
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $availableDates = $response->json();
        $this->assertIsArray($availableDates, 'Response should be an array');
        $this->assertCount(3, $availableDates, 'Should return 3 available dates');

        foreach ($testDates as $date) {
            $this->assertContains($date, $availableDates, "Should contain date: {$date}");
        }
    }

    /**
     * Test: Performance with large dataset
     *
     * Scenario: Filter large number of events (50 records as specified)
     * Expected: Response within reasonable time and correct results
     */
    public function test_large_dataset_filtering_performance(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // 40 events in 2024 - should be returned
        Evenement::factory()->count(40)->year2024()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // 10 events outside 2024 - should NOT be returned
        Evenement::factory()->count(10)->outsideYear2024()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        $filterParams = [
            'startDate' => '2024-01-01',
            'endDate' => '2024-12-31'
        ];

        // Act
        $startTime = microtime(true);
        $response = $this->get('/evenementen?' . http_build_query($filterParams));
        $endTime = microtime(true);

        $executionTime = $endTime - $startTime;

        // Assert
        $response->assertStatus(200);
        $this->assertLessThan(2.0, $executionTime, 'Filter should complete within 2 seconds');

        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(25, $evenementen->count(), 'Should return 25 events (first page of pagination)');
        $this->assertEquals(40, $evenementen->total(), 'Should have 40 total events from 2024');

        // Verify all returned events are from 2024
        foreach ($evenementen as $evenement) {
            $eventYear = Carbon::parse($evenement->evenement_begin_datum)->year;
            $this->assertEquals(2024, $eventYear, 'All events should be from 2024');
        }
    }

    /**
     * Test: Exception path - handling NULL date values
     *
     * Scenario: Database contains events with NULL begin dates
     * Expected: NULL events are excluded from filtered results
     */
    public function test_null_date_handling_in_filtering(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event with valid date - should be returned
        $validEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00'
        ]);

        // Event with NULL date - should NOT be returned
        $nullEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => null
        ]);

        $filterParams = [
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-31'
        ];

        // Act
        $response = $this->get('/evenementen?' . http_build_query($filterParams));

        // Assert
        $response->assertStatus(200);
        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(1, $evenementen->count(), 'Should return only event with valid date');
        $this->assertEquals($validEvent->evenement_id, $evenementen->first()->evenement_id);
    }

    /**
     * Test: Exception path - leap year date filtering
     *
     * Scenario: Filter includes February 29th in leap year 2024
     * Expected: Leap year date is handled correctly
     */
    public function test_leap_year_date_filtering(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event on leap year date
        $leapYearEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-29 12:00:00'
        ]);

        // Regular event
        $regularEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-28 12:00:00'
        ]);

        $filterParams = [
            'startDate' => '2024-02-29',
            'endDate' => '2024-02-29'
        ];

        // Act
        $response = $this->get('/evenementen?' . http_build_query($filterParams));

        // Assert
        $response->assertStatus(200);
        $evenementen = $response->viewData('evenementen');
        $this->assertEquals(1, $evenementen->count(), 'Should return only leap year event');
        $this->assertEquals($leapYearEvent->evenement_id, $evenementen->first()->evenement_id);
    }

    /**
     * Test: Happy path - no filters applied returns all events
     *
     * Scenario: User accesses evenementen without any date filters
     * Expected: All events are displayed (paginated)
     */
    public function test_no_filters_returns_all_events(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        $totalEvents = 5;
        Evenement::factory()->count($totalEvents)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Act - no filter parameters
        $response = $this->get('/evenementen');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('evenement.index');

        $evenementen = $response->viewData('evenementen');
        $this->assertEquals($totalEvents, $evenementen->count(), 'Should return all events when no filters applied');
    }
}
