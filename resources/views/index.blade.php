<?php use App\Http\Controllers\EvenementController;
$waarschuwingen = EvenementController::getWarnings(); ?>

<script>
    function togglevisibility(id) {
        Array.from(document.getElementsByClassName(id)).forEach(e => {
            e.style.display = (e.style.display === 'none') ? 'block' : 'none'
        })
    }
</script>

<x-main>
    @if ($waarschuwingen != null)
    <div class="flex row gap-5 flex-wrap">
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
        <x-widget title="Totaal Ligplaatsen Jaar" bottomText="+20% meer dan 2024" small>
            <p class="text-3xl font-semibold">16,928</p>
        </x-widget>
        <x-widget title="Totaal Ligplaatsen Maand" bottomText="+33% meer dan Februari" small>
            <p class="text-3xl font-semibold">4,405</p>
        </x-widget>
        <x-widget title="Hoi" small>
            <p>Dit is de inhoud van een widget, neem ik aan.</p>
        </x-widget>
    </div>

    <x-widget title="Testgrafiek met Graph.js">
        <div>
            <canvas id="myChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="script.js"></script>
    </x-widget>
</x-main>
