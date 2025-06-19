<x-main>
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
                    <td>
                        {{ $evenement->evenement_id }}
                    </td>
                    <td>
                        {{ $evenement->evenement_begin_datum }}
                    </td>
                    <td>
                        {{ "" }}
                    </td>
                    <td>
                        {{ $evenement->vlag_code }}
                    </td>
                    <td>
                        {{ $evenement->schip_beladingscode }}
                    </td>
                    <td>
                        {{ $evenement->lengte }}
                    </td>
                    <td>
                        {{ $evenement->breedte }}
                    </td>
                    <td>
                        {{ $evenement->diepgang }}
                    </td>
                    <td>
                        {{ $evenement->wachthaven->wachthaven_naam }}
                    </td>
                    <td>
                        {{ $evenement->steiger->steiger_code }}
                    </td>
                    <td>
                        <form action="{{ route('evenementen.destroy', $evenement->evenement_id) }}" method="POST" onsubmit="return confirm('Weet u zeker dat u deze data wilt verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <!-- Plak het aangepaste button element hier -->
                            <button type="submit" class="btn btn-danger" style="background-color: #dc3545; color: white; padding: 8px 16px; font-size: 14px; font-weight: 600; border: none; border-radius: 4px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease, transform 0.2s ease;" onmouseover="this.style.backgroundColor='#c82333'; this.style.transform='scale(1.05)'" onmouseout="this.style.backgroundColor='#dc3545'; this.style.transform='scale(1)'">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $evenementen->links() }}
    @endif
</x-main>
