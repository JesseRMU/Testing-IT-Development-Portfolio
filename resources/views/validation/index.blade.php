<x-main>
    <h2 class="text-xl font-bold mb-4">Validatieresultaten</h2>

    <!-- Statistieken en Overzicht -->
    <div class="mb-4">
        <p><strong>Totaal aantal rijen:</strong> {{ count($data) }}</p>
        <p><strong>Aantal foutieve rijen:</strong> {{ count($errors) }}</p>
<p><strong>Percentage fouten:</strong>
    {{ count($data) > 0 ? round((count($errors) / count($data)) * 100, 2) . '%' : 'Geen data beschikbaar' }}
</p>
    </div>

<div class="mb-4">
    <h3 class="text-lg font-bold">Validatieregels</h3>
    <ul>
        <li>- Geen lege verplichte velden (zoals rijnummer of steiger_id)</li>
        <li>- Geen dubbele rijen (bijv. meerdere rijen met hetzelfde evenement_id)</li>
        <li>- Datumformaten moeten geldig zijn (bijv. 2025-01-01 of 01/01/2025)</li>
    </ul>
</div>

<!-- Zoekformulier -->
<form method="GET" action="{{ route('validate-data') }}" class="mb-4 flex">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Zoek op rijnummer, veld of waarde..."
        class="border border-gray-300 rounded px-4 py-2 flex-grow"
    >
    <button
        type="submit"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded ml-2"
    >
        Zoeken
    </button>
</form>

    <div class="mt-3">
        {{ $data->links() }}
    </div>

    <!-- Tabelvalidatie -->
    <form action="{{ route('validate-data.remove-invalid') }}" method="POST" onsubmit="return confirm('Weet u zeker dat u de geselecteerde foutieve data wilt verwijderen?')">
        @csrf
        @method('DELETE')

        <table id="validationTable" class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-center">Selectie</th>
                    <th class="border border-gray-300 px-4 py-2">Rijnummer</th>
                    <th class="border border-gray-300 px-4 py-2">Gegevens</th>
                    <th class="border border-gray-300 px-4 py-2">Foutmeldingen</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Acties</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $rowIndex => $row)
                    <tr class="row-{{ array_key_exists($rowIndex, $errors) ? 'invalid bg-red-100' : 'valid bg-green-100' }}">
                        <!-- Checkbox voor selectie -->
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <input type="checkbox" name="selected_rows[]" value="{{ $rowIndex }}" @if(array_key_exists($rowIndex, $errors)) @endif>
                        </td>

                        <!-- Rijnummer -->
                        <td class="border border-gray-300 px-4 py-2">{{ $row->regelnummer_in_bron }}</td>

                        <!-- Gegevens -->
                        <td class="border border-gray-300 px-4 py-2">
                            @foreach($row as $field => $value)
                                <strong>{{ ucfirst($field) }}:</strong> {{ $value ?? 'N/A' }}<br>
                            @endforeach
                        </td>

                        <!-- Foutmeldingen tonen -->
                        <td class="border border-gray-300 px-4 py-2">
                            @if(array_key_exists($rowIndex, $errors))
                                <ul class="list-disc list-inside text-red-500">
                                    @foreach($errors[$rowIndex] as $error)
                                        <li>
                                            @if(str_contains($error, 'Ongeldige datum'))
                                                <span class="bg-yellow-200 px-2 rounded">⚠ {{ $error }}</span>
                                            @else
                                                {{ $error }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-green-500">✅ Geen fouten</span>
                            @endif
                        </td>

                        <!-- Acties -->
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            @if(array_key_exists($rowIndex, $errors))
                                <!-- Altijd zichtbare knop -->
                                <form action="{{ route('validate-data.remove-row', ['rowIndex' => $rowIndex]) }}" method="POST" onsubmit="return confirm('Weet u zeker dat u deze rij wilt verwijderen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                        Verwijderen
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Verwijder geselecteerde rijen -->
        <div class="mt-4">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                Verwijder geselecteerde foutieve rijen
            </button>
        </div>
    </form>
</div>

<div class="mt-3">
    {{ $data->links() }}
</div>

@if (count($data) === 0)
    <div class="alert alert-info">
        Geen resultaten gevonden voor de toegepaste filter.
    </div>
@endif


</x-main>

<script>
    // Javascript voor filtering
    function filterRows(filter) {
        let rows = document.querySelectorAll('#validationTable tbody tr');
        rows.forEach(row => {
            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'valid' && row.classList.contains('valid')) {
                row.style.display = '';
            } else if (filter === 'invalid' && row.classList.contains('invalid')) {
                row.style.display = 'none';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

<style>
@keyframes glow {
    0% {
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.4);
    }
    50% {
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.7);
    }
    100% {
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.4);
    }
}

.glow-effect {
    animation: glow 1s infinite;
    border: 2px solid #ff0000;
    border-radius: 4px;
}
</style>
