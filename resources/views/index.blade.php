<?php
use App\Http\Controllers\EvenementController;
try {$waarschuwingen = EvenementController::getWarnings();}
catch (Exception $e) {
    $waarschuwingen = null;
    //negeer de error en zet de waarschuwingen op null
}
?>

<x-main>
    <!-- Widgets en nieuwe dropdown -->
    @if ($waarschuwingen != null)
    <div class="flex row gap-5 overflow-x-auto">
        @foreach($waarschuwingen as $waarschuwingnummer=>$waarschuwing)
        <a onClick="togglevisibility('waarschuwing{{ $waarschuwingnummer }}')" class="flex flex-col gap-5 flex-wrap">
            <div class="rounded-2xl waarschuwing border-1 p-5">
                @if(isset($waarschuwing['locatie']))<h2>{{ $waarschuwing['locatie'] }}</h2>@endif
                @if(isset($waarschuwing['datum']))<p>{{ $waarschuwing['datum'] }}</p>@endif
                @if(isset($waarschuwing['percentage']))<h1 class="waarschuwing{{ $waarschuwingnummer }}">Ligplaatsbezetting {{ $percentage = $waarschuwing['percentage'] }}%</h1>@endif
                @if(isset($waarschuwing['evenementen']))<h2 style="display: none" class="waarschuwing{{ $waarschuwingnummer }}">Aantal evenementen {{ $waarschuwing['evenementen'] }}</h2>@endif
                @if(isset($waarschuwing['steigers']))<h2 style="display: none" class="waarschuwing{{ $waarschuwingnummer }}">Aantal steigers {{ $waarschuwing['steigers'] }}</h2>@endif
            </div>
        </a>
        @endforeach
    </div>
    @endif
    <div class="flex flex-row gap-5 flex-wrap">
        <!--<x-widget title="Totaal Ligplaatsen Jaar" bottomText="+20% meer dan 2024" small>
            <p class="text-3xl font-semibold">16,928</p>
        </x-widget>
        <x-widget title="Totaal Ligplaatsen Maand" bottomText="+33% meer dan Februari" small>
            <p class="text-3xl font-semibold">4,405</p>
        </x-widget>
        <x-widget title="Hoi" small>
            <p>Dit is de inhoud van een widget, neem ik aan.</p>
        </x-widget>-->

        <!-- Nieuwe Dropdown Widget -->
        <x-widget title="Groeperen op tijd">
            <form method="POST" action="{{ route('chart.groupByTime') }}">
                @csrf
                <label for="timeGrouping" class="block mb-2 font-semibold">Opties:</label>
                <select id="timeGrouping" name="timeGrouping" class="border-gray-300 rounded p-2 w-full">
                    <option disabled selected>Kies een optie</option>
                    <option value="day_of_week">Dag van de week</option>
                    <option value="hour_of_day">Uur van de dag</option>
                    <option value="week_of_year">Week van het jaar</option>
                    <option value="month_of_year">Maand van het jaar</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full">
                    Toepassen
                </button>
            </form>
        </x-widget>
    </div>

    <x-widget title="Testgrafiek met Graph.js">
        <x-slot name="menuItems">
            <li id="exportChartPNG" tabindex="0">Exporteer naar png</li>
            <li id="exportChartJPG" tabindex="0">Exporteer naar jpg</li>
            <li id="exportChartPDF" tabindex="0">Exporteer naar pdf</li>
        </x-slot>
        <div>
            <canvas id="myChart"></canvas>
        </div>
        <script>var groupByTime = "{{ $timeGrouping ?? "" }}";</script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/script.js"></script>
    </x-widget>
</x-main>
