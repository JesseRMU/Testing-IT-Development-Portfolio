<x-main>
    <!-- Widgets en nieuwe dropdown -->
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

        <!-- Nieuwe Dropdown Widget -->
        <x-widget title="Groeperen op tijd" small>
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
        <div>
            <canvas id="myChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="script.js"></script>
    </x-widget>
</x-main>
