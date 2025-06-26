<x-main>
    <div class="mb-4">
        <!-- Voeg 'Valideer Data' knop toe -->
        <a href="{{ route('validate-data') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            Valideer Data
        </a>
    </div>

    @if($evenementen->isEmpty())
        <h1>Je hebt met deze filters geen data geselecteerd.</h1>
    @else
        {{ $evenementen->links() }}
        <table>
            <tr>
                <th>id</th>
                <th>begindatum</th>
                <th>duur</th>
                <th>vlag</th>
                <th>schip - beladingscode</th>
                <th>schip - lengte</th>
                <th>schip - breedte</th>
                <th>schip - diepgang</th>
                <th>wachthaven (naam)</th>
                <th>steiger (code)</th>
                <th>Acties</th>
            </tr>
            @foreach($evenementen as $evenement)
                <tr>
                    <td>{{ $evenement->evenement_id }}</td>
                    <td>{{ $evenement->evenement_begin_datum }}</td>
                    <td>{{ $evenement->duur }}</td>
                    <td>{{ $evenement->vlag_code }}</td>
                    <td>{{ $evenement->schip_beladingscode }}</td>
                    <td>{{ $evenement->lengte }}</td>
                    <td>{{ $evenement->breedte }}</td>
                    <td>{{ $evenement->diepgang }}</td>
                    <td>{{ $evenement->wachthaven->wachthaven_naam }}</td>
                    <td>{{ $evenement->steiger->steiger_code }}</td>
                    <td>
                        <form action="{{ route('evenementen.destroy', $evenement->evenement_id) }}" method="POST" onsubmit="return confirm('Weet u zeker dat u deze data wilt verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Verwijderen
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>999999</td> <!-- Niet-bestaande ID -->
                <td colspan="10">Foutieve data om testen te forceren</td>
                <td>
                    <form action="{{ route('evenementen.destroy', 999999) }}" method="POST" onsubmit="return confirm('Weet u zeker dat u deze data wilt verwijderen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Verwijderen
                        </button>
                    </form>
                </td>
            </tr>
        </table>
        {{ $evenementen->links() }}
    @endif
</x-main>
