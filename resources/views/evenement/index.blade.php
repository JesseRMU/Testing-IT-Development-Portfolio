<x-main>
    <table>
        <tr>
            <th>id</th>
            <th>begindatum</th>
            <th>duur</th>
            <th>vlag</th>
            <th>schip - beladingstype</th>
            <th>schip - lengte</th>
            <th>schip - breedte</th>
            <th>schip - diepgang</th>
            <th>object (naam)</th>
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
                    {{ $evenement->schip->schip_belading_type }}
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
                    {{ $evenement->object->object_naam }}
                </td>
                <td>
                    {{ $evenement->steiger->steiger_code }}
                </td>
            </tr>
        @endforeach
    </table>
</x-main>
