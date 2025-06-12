<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Tests\TestCase;

class GraphExportBladeTest extends TestCase
{
    use InteractsWithViews;

    /** @test */
    // Test of de exporteer opties gerendered worden in de grafiek widget
    public function toon_export_opties_in_test_graph_widget()
    {
        $view = $this->blade(
            '<x-widget title="Testgrafiek met Graph.js">Test Content</x-widget>'
        );

        $view->assertSee('id="exportChartPNG"', false);
        $view->assertSee('id="exportChartJPG"', false);
        $view->assertSee('id="exportChartPDF"', false);
    }

    /** @test */
    // Test of de exporteer opties gerendered worden in niet-grafiek widgets
    public function toon_geen_export_opties_in_niet_test_graph_widgets()
    {
        $view = $this->blade(
            '<x-widget title="Andere titel">Test Content</x-widget>'
        );

        $view->assertDontSee('id="exportChartPNG"', false);
        $view->assertDontSee('id="exportChartJPG"', false);
        $view->assertDontSee('id="exportChartPDF"', false);
    }
}
