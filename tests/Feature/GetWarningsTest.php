<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Evenement;
use App\Models\Wachthaven;
use App\Models\Steiger;
use App\Services\WaarschuwingService;
use App\Http\Controllers\EvenementController;

class GetWarningsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // Test om te controleren of waarschuwingen worden weergegeven wanneer er
    // meer evenementen zijn dan steigers - met factories en database
    public function toon_waarschuwingen_wanneer_meer_evenementen_dan_steigers_feature_met_database()
    {

        // Maak wachthaven aan
        $wachthaven = Wachthaven::factory()->create(['wachthaven_id' => 1]);

        // maak een user account aan
        $this->actingAs(User::factory()->create());

        // Maak steigers aan
        Steiger::factory()->count(2)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
        ]);

        // Maak evenementen aan - meer evenementen dan steigers
        Evenement::factory()->count(4)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'evenement_begin_datum' => '2025-06-10',
        ]);

        // Haal waarschuwingen op
        $warnings = EvenementController::getWarnings();

        // Check of er waarschuwingen zijn en of de inhoud correct is
        $this->assertCount(1, $warnings);
        $this->assertEquals($wachthaven->wachthaven_naam, $warnings->first()['locatie']);
        $this->assertEquals('2025-06-10', $warnings->first()['datum']);
        $this->assertEquals(4, $warnings->first()['evenementen']);
        $this->assertEquals(2, $warnings->first()['steigers']);
        $this->assertEquals(200, $warnings->first()['percentage']);

        $view = $this->blade(
            '<x-widget title="Testgrafiek met Graph.js">Test Content</x-widget>'
        );

        $view->assertSee('id="exportChartPNG"', false);
        $view->assertSee('id="exportChartJPG"', false);
        $view->assertSee('id="exportChartPDF"', false);
    }

    /** @test */
    // Test om te controleren of er geen waarschuwingen worden weergegeven wanneer er
    // minder evenementen zijn dan steigers - met factories en database
    public function toon_geen_waarschuwingen_wanneer_minder_evenementen_dan_steigers_feature_met_database()
    {
        // Maak wachthaven aan
        $wachthaven = Wachthaven::factory()->create(['wachthaven_id' => 2]);

        // Maak steigers aan
        Steiger::factory()->count(5)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
        ]);

        // Maak evenementen aan - minder dan steigers
        Evenement::factory()->count(3)->create([
            'wachthaven_id' => $wachthaven->wachthaven_id,
            'evenement_begin_datum' => '2025-06-10',
        ]);

        // Haal waarschuwingen op
        $warnings = EvenementController::getWarnings();

        // Check of er (geen) waarschuwingen zijn
        $this->assertCount(0, $warnings);
    }
}
