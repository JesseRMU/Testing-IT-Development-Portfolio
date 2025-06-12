<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WaarschuwingService;

class WarningsServiceTest extends TestCase
{
    /** @test */
    // Test om te controleren of waarschuwingen worden weergegeven wanneer er meer evenementen zijn dan steigers - met dummy data
    public function toon_waarschuwingen_wanneer_meer_evenementen_dan_steigers_unit()
    {
        // Maak evenementen aan
        $evenementen = collect([
            ['wachthaven_id' => 1, 'evenement_begin_datum' => '2025-06-10'],
            ['wachthaven_id' => 1, 'evenement_begin_datum' => '2025-06-10'],
            ['wachthaven_id' => 1, 'evenement_begin_datum' => '2025-06-10'],
        ]);

        // Maak steiger aan
        $steigers = collect([
            ['wachthaven_id' => 1],
        ]);

        // Maak wachthaven aan
        $wachthavens = collect([
            1 => ['wachthaven_id' => 1, 'wachthaven_naam' => 'Testhaven']
        ]);

        // Haal waarschuwingen op
        $warnings = WaarschuwingService::getWarnings($evenementen, $steigers, $wachthavens);

        // Check of er waarschuwingen zijn en de inhoud ervan
        $this->assertCount(1, $warnings);
        $this->assertEquals('Testhaven', $warnings[0]['locatie']);
        $this->assertEquals('2025-06-10', $warnings[0]['datum']);
        $this->assertEquals(3, $warnings[0]['evenementen']);
        $this->assertEquals(1, $warnings[0]['steigers']);
        $this->assertEquals(300, $warnings[0]['percentage']);
    }

    /** @test */
    // Test om te controleren of er geen waarschuwingen worden weergegeven wanneer er minder evenementen zijn dan steigers - met dummy data
    public function toon_geen_waarschuwingen_wanneer_minder_evenementen_dan_steigers_unit()
    {

        // Maak wachthavens aan
        $wachthavens = collect([
            2 => ['wachthaven_id' => 2, 'wachthaven_naam' => 'Testhaven']
        ]);

        // Maak steigers aan
        $steigers = collect([
            ['wachthaven_id' => 2],
            ['wachthaven_id' => 2],
        ]);

        // Maak evenementen aan
        $evenementen = collect([
            ['wachthaven_id' => 2, 'evenement_begin_datum' => '2025-06-11'],
        ]);

        // Haal waarschuwingen op
        $warnings = WaarschuwingService::getWarnings($evenementen, $steigers, $wachthavens);

        // Check of er (geen) waarschuwingen zijn
        $this->assertCount(0, $warnings);
    }
}
