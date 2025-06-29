<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\EvenementController;
use App\Models\Evenement;
use App\Models\Wachthaven;
use App\Models\Steiger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Unit tests for date filter functionality - User Story 1
 *
 * Tests individual date validation and filter methods in isolation
 * to ensure core filtering logic works correctly.
 */
class DateFilterUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: applyDateFilter method with start date only
     *
     * Scenario: Only start date provided in request
     * Expected: Query filtered to events >= start date
     */
    public function test_apply_date_filter_with_start_date_only(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event before filter date - should NOT be returned
        $eventBefore = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-28 10:00:00'
        ]);

        // Event on filter date - should be returned
        $eventOnDate = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-01 10:00:00'
        ]);

        // Event after filter date - should be returned
        $eventAfter = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00'
        ]);

        $request = Request::create('/test', 'GET', ['startDate' => '2024-03-01']);
        app()->instance('request', $request);

        // Act
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert
        $this->assertCount(2, $results, 'Should return 2 events >= start date');

        $resultIds = $results->pluck('evenement_id')->toArray();
        $this->assertContains($eventOnDate->evenement_id, $resultIds);
        $this->assertContains($eventAfter->evenement_id, $resultIds);
        $this->assertNotContains($eventBefore->evenement_id, $resultIds);
    }

    /**
     * Test: applyDateFilter method with end date only
     *
     * Scenario: Only end date provided in request
     * Expected: Query filtered to events <= end date
     */
    public function test_apply_date_filter_with_end_date_only(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event before filter date - should be returned
        $eventBefore = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-28 10:00:00'
        ]);

        // Event on filter date - should be returned
        $eventOnDate = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-01 10:00:00'
        ]);

        // Event after filter date - should NOT be returned
        $eventAfter = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00'
        ]);

        $request = Request::create('/test', 'GET', ['endDate' => '2024-03-01']);
        app()->instance('request', $request);

        // Act
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert
        $this->assertCount(2, $results, 'Should return 2 events <= end date');

        $resultIds = $results->pluck('evenement_id')->toArray();
        $this->assertContains($eventBefore->evenement_id, $resultIds);
        $this->assertContains($eventOnDate->evenement_id, $resultIds);
        $this->assertNotContains($eventAfter->evenement_id, $resultIds);
    }

    /**
     * Test: applyDateFilter method with both start and end dates
     *
     * Scenario: Both start and end dates provided in request
     * Expected: Query filtered to events within date range (inclusive)
     */
    public function test_apply_date_filter_with_date_range(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event before range - should NOT be returned
        $eventBefore = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-28 10:00:00'
        ]);

        // Event within range - should be returned
        $eventWithin = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00'
        ]);

        // Event on start boundary - should be returned
        $eventOnStart = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-01 10:00:00'
        ]);

        // Event on end boundary - should be returned
        $eventOnEnd = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-31 10:00:00'
        ]);

        // Event after range - should NOT be returned
        $eventAfter = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-04-15 10:00:00'
        ]);

        $request = Request::create('/test', 'GET', [
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-31'
        ]);
        app()->instance('request', $request);

        // Act
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert
        $this->assertCount(3, $results, 'Should return 3 events within date range');

        $resultIds = $results->pluck('evenement_id')->toArray();
        $this->assertContains($eventWithin->evenement_id, $resultIds);
        $this->assertContains($eventOnStart->evenement_id, $resultIds);
        $this->assertContains($eventOnEnd->evenement_id, $resultIds);
        $this->assertNotContains($eventBefore->evenement_id, $resultIds);
        $this->assertNotContains($eventAfter->evenement_id, $resultIds);
    }

    /**
     * Test: applyDateFilter method with date and time combination
     *
     * Scenario: Start/end date with specific times
     * Expected: Query filtered with precise datetime filtering
     */
    public function test_apply_date_filter_with_datetime_precision(): void
    {
        // Arrange: Create events at different times on same date
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event before time range
        $eventBeforeTime = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 08:00:00'
        ]);

        // Event within time range
        $eventWithinTime = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 14:30:00'
        ]);

        // Event after time range
        $eventAfterTime = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 20:00:00'
        ]);

        // Mock request with date and time
        $request = Request::create('/test', 'GET', [
            'startDate' => '2024-03-15',
            'startTime' => '10:00',
            'endDate' => '2024-03-15',
            'endTime' => '18:00'
        ]);
        app()->instance('request', $request);

        // Act: Apply date filter
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert: Only event within time range is returned
        $this->assertCount(1, $results);
        $this->assertEquals($eventWithinTime->evenement_id, $results->first()->evenement_id);
    }

    /**
     * Test: applyDateFilter method with weekday filtering
     *
     * Scenario: Filter events by specific weekday (JavaScript format)
     * Expected: Only events on specified weekday are returned
     */
    public function test_apply_date_filter_with_weekday(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Monday event (2024-03-04 is a Monday) - weekday 1 in JS = Monday
        $mondayEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-04 10:00:00'  // Monday
        ]);

        // Tuesday event (2024-03-05 is a Tuesday) - weekday 2 in JS = Tuesday
        $tuesdayEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-05 10:00:00'  // Tuesday
        ]);

        // Friday event (2024-03-08 is a Friday) - weekday 5 in JS = Friday
        $fridayEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-08 10:00:00'  // Friday
        ]);

        // Sunday event (2024-03-10 is a Sunday) - weekday 0 in JS = Sunday
        $sundayEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-10 10:00:00'  // Sunday
        ]);

        $request = Request::create('/test', 'GET', ['weekday' => 1]); // Monday in JS format
        app()->instance('request', $request);

        // Act
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert
        $this->assertCount(1, $results, 'Should return only Monday events');
        $this->assertEquals($mondayEvent->evenement_id, $results->first()->evenement_id);

        // Verify the returned event is actually on a Monday
        $eventDate = Carbon::parse($results->first()->evenement_begin_datum);
        $this->assertEquals(1, $eventDate->dayOfWeek, 'Event should be on Monday (Carbon dayOfWeek = 1)');
    }

    /**
     * Test: applyDateFilter method with no parameters
     *
     * Scenario: No date filters provided
     * Expected: All events returned (no filtering applied)
     */
    public function test_apply_date_filter_with_no_parameters(): void
    {
        // Arrange: Create test data
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        $events = Evenement::factory()->count(5)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
        ]);

        // Mock empty request
        $request = Request::create('/test', 'GET', []);
        app()->instance('request', $request);

        // Act: Apply date filter
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert: All events are returned
        $this->assertCount(5, $results);
    }

    /**
     * Test: applyDateFilter method with custom column
     *
     * Scenario: Apply date filter to different date column
     * Expected: Filter applied to specified column instead of default
     */
    public function test_apply_date_filter_with_custom_column(): void
    {
        // Arrange: Create test data with different end dates
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event with end date before filter
        $eventEndBefore = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-15 10:00:00',
            'evenement_eind_datum' => '2024-02-28 10:00:00'
        ]);

        // Event with end date after filter
        $eventEndAfter = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-02-15 10:00:00',
            'evenement_eind_datum' => '2024-03-15 10:00:00'
        ]);

        // Mock request with start date
        $request = Request::create('/test', 'GET', ['startDate' => '2024-03-01']);
        app()->instance('request', $request);

        // Act: Apply date filter to end date column
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query, 'evenement_eind_datum');
        $results = $filteredQuery->get();

        // Assert: Only event with end date >= start date is returned
        $this->assertCount(1, $results);
        $this->assertEquals($eventEndAfter->evenement_id, $results->first()->evenement_id);
    }

    /**
     * Test: Date format validation and edge cases
     *
     * Scenario: Various date formats and edge cases
     * Expected: Graceful handling of different input formats
     */
    public function test_date_filter_edge_cases(): void
    {
        // Arrange: Create test data
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        $event = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00'
        ]);

        // Test with leap year date
        $request = Request::create('/test', 'GET', [
            'startDate' => '2024-02-29',  // Leap year date
            'endDate' => '2024-12-31'
        ]);
        app()->instance('request', $request);

        // Act: Apply date filter
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert: Date is handled correctly
        $this->assertCount(1, $results);
        $this->assertEquals($event->evenement_id, $results->first()->evenement_id);
    }

    /**
     * Test: applyDateFilter method with NULL date values
     *
     * Scenario: Database contains events with NULL begin dates
     * Expected: NULL values are properly excluded from date filtering
     */
    public function test_date_filter_with_null_values(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Event with valid date within filter range - should be returned
        $validEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-15 10:00:00'
        ]);

        // Event with NULL date - behavior depends on SQL handling
        $nullEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => null
        ]);

        // Event with valid date outside filter range - should NOT be returned
        $outsideEvent = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-01-15 10:00:00'
        ]);

        $request = Request::create('/test', 'GET', [
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-31'
        ]);
        app()->instance('request', $request);

        // Act
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert
        $this->assertEquals(1, $results->count(), 'Should return only event with valid date in range');
        $this->assertEquals($validEvent->evenement_id, $results->first()->evenement_id);

        // Verify NULL event is not included
        $resultIds = $results->pluck('evenement_id')->toArray();
        $this->assertNotContains($nullEvent->evenement_id, $resultIds, 'NULL date event should not be included');
        $this->assertNotContains($outsideEvent->evenement_id, $resultIds, 'Outside range event should not be included');
    }

    /**
     * Test: applyDateFilter method with combined date and weekday filters
     *
     * Scenario: Apply both date range and weekday filtering simultaneously
     * Expected: Events must match BOTH criteria to be returned
     */
    public function test_apply_date_filter_with_combined_filters(): void
    {
        // Arrange
        $wachthaven = Wachthaven::factory()->create();
        $steiger = Steiger::factory()->create(['wachthaven_id' => $wachthaven->wachthaven_id]);

        // Monday in March 2024 (matches both date and weekday) - should be returned
        $mondayInMarch = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-04 10:00:00'  // Monday in March
        ]);

        // Monday outside March (matches weekday but not date) - should NOT be returned
        $mondayOutsideMarch = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-04-01 10:00:00'  // Monday in April
        ]);

        // Friday in March (matches date but not weekday) - should NOT be returned
        $fridayInMarch = Evenement::factory()->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'steiger_id' => $steiger->steiger_id,
            'evenement_begin_datum' => '2024-03-08 10:00:00'  // Friday in March
        ]);

        $request = Request::create('/test', 'GET', [
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-31',
            'weekday' => 1  // Monday
        ]);
        app()->instance('request', $request);

        // Act
        $query = Evenement::query();
        $filteredQuery = EvenementController::applyDateFilter($query);
        $results = $filteredQuery->get();

        // Assert
        $this->assertCount(1, $results, 'Should return only event matching both date and weekday');
        $this->assertEquals($mondayInMarch->evenement_id, $results->first()->evenement_id);

        // Verify the returned event meets both criteria
        $eventDate = Carbon::parse($results->first()->evenement_begin_datum);
        $this->assertEquals(1, $eventDate->dayOfWeek, 'Event should be on Monday');
        $this->assertEquals(3, $eventDate->month, 'Event should be in March');
    }
}
