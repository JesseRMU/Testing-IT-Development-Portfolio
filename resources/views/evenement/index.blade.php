<x-main>
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
                    {{ ( $evenement->evenement_eind_datum - $evenement->evenement_begin_datum ) / 60 }}
                </td>
                <td>
                    {{ $evenement->schip->vlag_code }}
                </td>
                <td>
                    {{ $evenement->schip->schip_beladingscode }}
                </td>
                <td>
                    {{ $evenement->schip->lengte }}
                </td>
                <td>
                    {{ $evenement->schip->breedte }}
                </td>
                <td>
                    {{ $evenement->schip->diepgang }}
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
</x-main>
