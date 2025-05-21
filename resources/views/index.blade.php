<x-main>
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
