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
                </tr>
            @endforeach
        </table>
        {{ $evenementen->links() }}
    @endif
</x-main>
