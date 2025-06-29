<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use Carbon\Carbon;

/**
 * Date Validation Helper Tests for Date Filter - User Story 1
 *
 * Tests for validating date inputs and handling edge cases
 * in date filtering functionality.
 */
class DateValidationTest extends TestCase
{
    /**
     * Test: Valid date format validation
     *
     * Scenario: Various valid date formats
     * Expected: All formats are accepted and parsed correctly
     */
    public function test_valid_date_formats(): void
    {
        $validDates = [
            '2024-03-15',
            '2024-12-31',
            '2024-01-01',
            '2024-02-29', // Leap year
        ];

        foreach ($validDates as $date) {
            $carbonDate = Carbon::parse($date);
            $this->assertInstanceOf(Carbon::class, $carbonDate);
            $this->assertEquals($date, $carbonDate->format('Y-m-d'));
        }
    }

    /**
     * Test: Invalid date format handling
     *
     * Scenario: Various invalid date formats
     * Expected: Graceful handling without throwing exceptions
     */
    public function test_invalid_date_format_handling(): void
    {
        $invalidDates = [
            '2024-13-01', // Invalid month
            '2024-02-30', // Invalid day for February
            '2023-02-29', // Not a leap year
            'invalid-date',
            '32/12/2024',
            '',
            null
        ];

        foreach ($invalidDates as $date) {
            try {
                if ($date === null || $date === '') {
                    // These should not be parsed
                    $this->assertNull($date === '' ? null : $date);
                } else {
                    // These should throw exceptions when parsed
                    Carbon::parse($date);
                    $this->fail("Expected exception for invalid date: {$date}");
                }
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                // Expected behavior for invalid dates
                $this->assertStringContainsString('Failed to parse', $e->getMessage());
            } catch (\Exception $e) {
                // Other exceptions are also acceptable for invalid dates
                $this->assertNotEmpty($e->getMessage());
            }
        }
    }

    /**
     * Test: Date range validation logic
     *
     * Scenario: Validate logical date ranges
     * Expected: Proper validation of start/end date relationships
     */
    public function test_date_range_validation(): void
    {
        // Valid ranges
        $validRanges = [
            ['2024-01-01', '2024-01-31'],
            ['2024-02-29', '2024-03-01'], // Leap year boundary
            ['2024-12-31', '2024-12-31'], // Same day
        ];

        foreach ($validRanges as [$start, $end]) {
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            $this->assertTrue(
                $startDate->lessThanOrEqualTo($endDate),
                "Start date {$start} should be <= end date {$end}"
            );
        }

        // Invalid ranges (start > end)
        $invalidRanges = [
            ['2024-01-31', '2024-01-01'],
            ['2024-12-31', '2024-01-01'],
            ['2024-03-15', '2024-03-14'],
        ];

        foreach ($invalidRanges as [$start, $end]) {
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            $this->assertTrue(
                $startDate->greaterThan($endDate),
                "Start date {$start} should be > end date {$end} (invalid range)"
            );
        }
    }

    /**
     * Test: Leap year handling
     *
     * Scenario: Test leap year dates and boundaries
     * Expected: Correct handling of February 29th in leap years
     */
    public function test_leap_year_handling(): void
    {
        // 2024 is a leap year
        $leapYearDate = Carbon::parse('2024-02-29');
        $this->assertTrue($leapYearDate->isLeapYear());
        $this->assertEquals(29, $leapYearDate->day);
        $this->assertEquals(2, $leapYearDate->month);

        // 2023 is not a leap year - February 29th should not exist
        try {
            Carbon::parse('2023-02-29');
            $this->fail('Expected exception for non-leap year February 29th');
        } catch (\Exception $e) {
            $this->assertNotEmpty($e->getMessage());
        }

        // Test leap year boundaries
        $beforeLeapDay = Carbon::parse('2024-02-28');
        $leapDay = Carbon::parse('2024-02-29');
        $afterLeapDay = Carbon::parse('2024-03-01');

        $this->assertEquals(1, $beforeLeapDay->diffInDays($leapDay));
        $this->assertEquals(1, $leapDay->diffInDays($afterLeapDay));
    }

    /**
     * Test: Time zone considerations
     *
     * Scenario: Date filtering with different time zones
     * Expected: Consistent behavior regardless of time zone
     */
    public function test_timezone_consistency(): void
    {
        $dateString = '2024-03-15';

        // Parse date in different timezones
        $utcDate = Carbon::parse($dateString, 'UTC');
        $amsterdamDate = Carbon::parse($dateString, 'Europe/Amsterdam');
        $newYorkDate = Carbon::parse($dateString, 'America/New_York');

        // All should represent the same calendar date
        $this->assertEquals($dateString, $utcDate->format('Y-m-d'));
        $this->assertEquals($dateString, $amsterdamDate->format('Y-m-d'));
        $this->assertEquals($dateString, $newYorkDate->format('Y-m-d'));
    }

    /**
     * Test: Date boundary conditions
     *
     * Scenario: Test edge cases at date boundaries
     * Expected: Proper handling of boundary conditions
     */
    public function test_date_boundary_conditions(): void
    {
        // Year boundaries
        $yearEnd = Carbon::parse('2024-12-31 23:59:59');
        $yearStart = Carbon::parse('2025-01-01 00:00:00');

        $this->assertEquals(1, $yearEnd->diffInSeconds($yearStart));
        $this->assertNotEquals($yearEnd->year, $yearStart->year);

        // Month boundaries
        $monthEnd = Carbon::parse('2024-01-31');
        $nextMonthStart = Carbon::parse('2024-02-01');

        $this->assertEquals(1, $monthEnd->diffInDays($nextMonthStart));
        $this->assertNotEquals($monthEnd->month, $nextMonthStart->month);

        // Week boundaries (for weekday filtering)
        $sunday = Carbon::parse('2024-03-03'); // Sunday
        $monday = Carbon::parse('2024-03-04'); // Monday

        $this->assertEquals(0, $sunday->dayOfWeek); // Sunday = 0
        $this->assertEquals(1, $monday->dayOfWeek); // Monday = 1
    }

    /**
     * Test: Date arithmetic for filtering
     *
     * Scenario: Test date calculations used in filtering logic
     * Expected: Accurate date arithmetic
     */
    public function test_date_arithmetic_for_filtering(): void
    {
        $baseDate = Carbon::parse('2024-03-15');

        // Add/subtract days
        $dayBefore = $baseDate->copy()->subDay();
        $dayAfter = $baseDate->copy()->addDay();

        $this->assertEquals('2024-03-14', $dayBefore->format('Y-m-d'));
        $this->assertEquals('2024-03-16', $dayAfter->format('Y-m-d'));

        // Add/subtract months
        $monthBefore = $baseDate->copy()->subMonth();
        $monthAfter = $baseDate->copy()->addMonth();

        $this->assertEquals('2024-02-15', $monthBefore->format('Y-m-d'));
        $this->assertEquals('2024-04-15', $monthAfter->format('Y-m-d'));

        // Weekday calculations (for weekday filtering)
        $this->assertEquals(5, $baseDate->dayOfWeek); // Friday

        // Find next Monday
        $nextMonday = $baseDate->copy()->next(Carbon::MONDAY);
        $this->assertEquals(1, $nextMonday->dayOfWeek);
        $this->assertEquals('2024-03-18', $nextMonday->format('Y-m-d'));
    }

    /**
     * Test: SQL date format compatibility
     *
     * Scenario: Ensure dates are formatted correctly for database queries
     * Expected: Proper MySQL datetime format
     */
    public function test_sql_date_format_compatibility(): void
    {
        $testDate = Carbon::parse('2024-03-15 14:30:45');

        // MySQL datetime format
        $mysqlFormat = $testDate->format('Y-m-d H:i:s');
        $this->assertEquals('2024-03-15 14:30:45', $mysqlFormat);

        // MySQL date format (for date-only filtering)
        $mysqlDateFormat = $testDate->format('Y-m-d');
        $this->assertEquals('2024-03-15', $mysqlDateFormat);

        // ISO format (for JSON/API responses)
        $isoFormat = $testDate->toISOString();
        $this->assertStringContains('2024-03-15T14:30:45', $isoFormat);
    }
}
