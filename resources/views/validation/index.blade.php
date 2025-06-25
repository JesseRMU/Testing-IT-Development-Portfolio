<x-main>
    <h2 class="text-xl font-bold mb-4">Validatieresultaten</h2>

    @if(empty($errors))
        <p>Alle rijen zijn gevalideerd, geen fouten gevonden.</p>
    @else
        <table class="w-full border-collapse border border-gray-300 ">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Rijnummer</th>
                    <th class="border border-gray-300 px-4 py-2">Fouten</th>
                </tr>
            </thead>
            <tbody>
                @foreach($errors as $rowIndex => $error)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $rowIndex }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ implode(', ', $error) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-main>
