<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class GraphDataTest extends TestCase
{
    // Migrate voor de test
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    // Kijk of de grafieken data van de database kan gebruiken
    public function graph_heeft_juiste_data_structuur()
    {
        // Arrange
        $this->actingAs(User::factory()->create());
        // Vraag de grafiek data op met een geldige group_by_time parameter
        $response = $this->getJson('/graphs?group_by_time=day');

        // Verwacht een 200 OK status code en controleer de JSON structuur
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'labels',
                    'datasets' => [
                        ['label', 'data', 'backgroundColor', 'borderColor', 'borderWidth']
                    ],
                ]);
    }

    /** @test */
    // Kijk of er een foutmelding getoond wordt met foute data
    public function graph_geeft_error_met_foute_data_structuur()
    {
        // Geen arrange nodig

        // Probeer een ongeldige waarde voor group_by_time
        $response = $this->getJson('/graphs?group_by_time=invalid_value');

        // Verwacht een 422 Unprocessable Entity status code
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['group_by_time']);
    }
}
