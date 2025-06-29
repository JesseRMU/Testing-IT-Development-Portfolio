# Date Filter Tests - User Story 1

Dit document beschrijft de PHPUnit tests voor de datumfilter functionaliteit van het ligplaats dashboard.

## Overzicht

De tests zijn opgedeeld in drie categorieën:
- **Feature Tests**: Testen complete HTTP requests naar filter endpoints
- **Unit Tests**: Testen individuele datumvalidatie en filter methods
- **Helper Tests**: Testen datumvalidatie en edge cases

## Test Scenarios

### 1. Happy Path Scenarios
- ✅ Geldige begin/einddatum → gefilterde data terug
- ✅ Datums met tijd combinatie → precisie filtering
- ✅ Weekdag filtering → alleen events op specifieke weekdag
- ✅ Beschikbare datums ophalen → JSON response met event datums

### 2. Edge Case Scenarios  
- ✅ Datums zonder data → lege resultaten
- ✅ Schrikkeljaar datums (29 februari 2024)
- ✅ Jaargrens events (31 dec → 1 jan)
- ✅ NULL waarden in database
- ✅ Zeer lange event durations

### 3. Exception Scenarios
- ✅ Begindatum > einddatum → logische afhandeling
- ✅ Ongeldige datumformaten → graceful error handling
- ✅ Performance test met grote datasets (50+ records)

## Test Data

### Factory Setup
De `EvenementFactory` is uitgebreid met:
- `year2024()` - Events specifiek in 2024
- `outsideYear2024()` - Events buiten 2024 voor edge cases  
- `betweenDates($start, $end)` - Events in specifieke periode

### Test Seeder
`DateFilterTestSeeder` creëert 50 test records:
- 40 events verspreid over 2024 (per kwartaal)
- 5 events buiten 2024 (2023 en 2025)
- 5 speciale scenario events voor edge cases

## Tests Uitvoeren

### Alle datumfilter tests
```bash
php artisan test --configuration=phpunit-datefilter.xml
```

### Specifieke test categorieën
```bash
# Feature tests (HTTP endpoints)
php artisan test tests/Feature/DateFilterFeatureTest.php

# Unit tests (individuele methods)
php artisan test tests/Unit/DateFilterUnitTest.php

# Helper tests (datumvalidatie)
php artisan test tests/Unit/Helpers/DateValidationTest.php
```

### Database setup voor tests
```bash
# Migraties uitvoeren (test database)
php artisan migrate --env=testing

# Test data seeden
php artisan db:seed --class=DateFilterTestSeeder --env=testing
```

## Test Coverage

### Feature Tests (`DateFilterFeatureTest.php`)
- `test_valid_date_range_returns_filtered_events()` - Happy path filtering
- `test_no_data_in_date_range_returns_empty_result()` - Lege resultaten
- `test_invalid_date_range_start_after_end()` - Ongeldige range
- `test_date_and_time_combination_filtering()` - Datum + tijd filtering
- `test_weekday_filtering()` - Weekdag filtering
- `test_available_dates_endpoint()` - API endpoint test
- `test_large_dataset_filtering_performance()` - Performance test

### Unit Tests (`DateFilterUnitTest.php`)
- `test_apply_date_filter_with_start_date_only()` - Alleen startdatum
- `test_apply_date_filter_with_end_date_only()` - Alleen einddatum
- `test_apply_date_filter_with_date_range()` - Complete range
- `test_apply_date_filter_with_datetime_precision()` - Tijd precisie
- `test_apply_date_filter_with_weekday()` - Weekdag filtering
- `test_apply_date_filter_with_no_parameters()` - Geen filters
- `test_apply_date_filter_with_custom_column()` - Custom kolom
- `test_date_filter_edge_cases()` - Edge cases
- `test_date_filter_with_null_values()` - NULL waarden

### Helper Tests (`DateValidationTest.php`)
- `test_valid_date_formats()` - Geldige datumformaten
- `test_invalid_date_format_handling()` - Ongeldige formaten
- `test_date_range_validation()` - Range validatie
- `test_leap_year_handling()` - Schrikkeljaar handling
- `test_timezone_consistency()` - Timezone consistentie
- `test_date_boundary_conditions()` - Grenswaarden
- `test_date_arithmetic_for_filtering()` - Datum berekeningen
- `test_sql_date_format_compatibility()` - SQL format compatibility

## Existing Controller Methods Tested

### `EvenementController::applyDateFilter()`
- Datum range filtering (start/end)
- Tijd precisie filtering (startTime/endTime)
- Weekdag filtering
- Custom kolom ondersteuning

### `EvenementController::getAvailableDates()`
- Beschikbare datums ophalen
- JSON response formatting
- Filter integration

## Database Schema

Tests werken met de volgende relevante velden:
```sql
evenementen.evenement_begin_datum (datetime)
evenementen.evenement_eind_datum (datetime)
evenementen.steiger_id (foreign key)
evenementen.wachthaven_id (foreign key)
```

## Environment Configuration

Tests gebruiken de testing environment (`.env.testing`):
```env
DB_DATABASE=ligplaats_dashboard_test
DB_CONNECTION=mysql
APP_ENV=testing
```

## Expected Results

Bij succesvolle tests:
- ✅ Alle 25+ test methods slagen
- ✅ Performance test < 2 seconden
- ✅ Geen memory leaks bij grote datasets
- ✅ Correcte JSON responses van API endpoints
- ✅ Database integriteit behouden na tests

## Troubleshooting

### Common Issues
1. **Database not found**: Zorg dat test database bestaat
2. **Factory errors**: Check dat Wachthaven/Steiger factories bestaan  
3. **Route errors**: Controleer dat routes correct gedefinieerd zijn
4. **Authentication errors**: Tests gebruiken actingAs() voor protected routes

### Debug Tips
```bash
# Verbose output
php artisan test --verbose

# Specifieke test
php artisan test --filter="test_valid_date_range"

# Database queries bekijken
DB_LOG_QUERIES=true php artisan test
```

## Next Steps

Na het slagen van deze tests:
1. Integreer in CI/CD pipeline
2. Voeg toe aan reguliere test suite
3. Monitor performance in productie
4. Breid uit voor nieuwe filter requirements
