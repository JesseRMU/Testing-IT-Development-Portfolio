<x-main>
    <form id="filters" method="GET"  class="flex gap-6 items-start max-w-full">

        <x-widget title="Filteren op veld" class="w-[400px] overflow-visible">
            <div>
                <!--
                    checkbox niet ingevuld:
                        evenement_vaarrichting
                        schip_lading_marpol
                -->
                <x-filter table="evenementen" name="schip_laadvermogen" type="number" />
                <x-filter table="evenementen" name="lengte" type="number" />
                <x-filter table="evenementen" name="breedte" type="number" />
                <x-filter table="evenementen" name="diepgang" type="number" />
                <x-filter table="evenementen" name="schip_vervoerd_gewicht" type="number" />
                <x-filter table="evenementen" name="schip_aantal_passagiers" type="number" />
                <x-filter table="evenementen" name="schip_containers_aantal" type="number" />
                <x-filter table="evenementen" titletable="schip_types" titles="schip_type_naam" name="schip_type" type="checkbox" />
                <x-filter table="evenementen" titletable="wachthavens" name="wachthaven_id" titles="wachthaven_naam" type="checkbox" />
                <x-filter table="wachthavens" titletable="rws_objecten" name="object_id" titles="object_naam" type="checkbox" />
                {{--<x-filter table="evenementen" name="evenement_vaarrichting" type="checkbox" />--}}
                <x-filter table="evenementen" name="vlag_code" type="checkbox" />
                <x-filter table="evenementen" name="schip_onderdeel_code" type="checkbox" />
                <x-filter table="evenementen" name="schip_beladingscode" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_system_code" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_nstr" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_reserve" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_vn_nummer" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_klasse" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_code" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_1e_etiket" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_2e_etiket" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_3e_etiket" type="checkbox" />
                <x-filter table="evenementen" name="schip_lading_verpakkingsgroep" type="checkbox" />
                {{--<x-filter table="evenementen" name="schip_lading_marpol" type="checkbox" />--}}
                <x-filter table="evenementen" name="schip_lading_seinvoering_kegel" type="checkbox" />
                <x-filter table="evenementen" name="schip_avv_klasse" type="checkbox" />
                <x-filter table="evenementen" name="schip_containers" type="checkbox" />
                <x-filter table="evenementen" name="schip_containers_type" type="checkbox" />
                <x-filter table="evenementen" name="schip_containers_teus" type="checkbox" />
            </div>
        </x-widget>

        {{--Datum filter--}}
        <x-widget title="Filteren op datum" class="w-[280px]">
            <input type="text" id="startDate" name="startDate" value="{{ request('startDate') }}" placeholder="Startdatum" class="border border-black rounded px-2 py-1 w-full mb-2">
            <input type="text" id="endDate" name="endDate" value="{{ request('endDate') }}" placeholder="Einddatum" class="border border-black rounded px-2 py-1 w-full">
        </x-widget>
        <x-widget title="Filteren op tijd" class="w-[280px]">
            <input type="text" id="startTime" name="startTime" value="{{ request('startTime') }}" placeholder="Starttijd" class="border border-black rounded px-2 py-1 w-full mb-2">
            <input type="text" id="endTime" name="endTime" value="{{ request('endTime') }}" placeholder="Eindtijd" class="border border-black rounded px-2 py-1 w-full">
        </x-widget>
        <x-widget title="Filter op dag" class="w-[280px]">
            <select name="weekday" class="border border-black rounded px-2 py-1 w-full">
                <option value="">-- Kies een dag --</option>
                <option value="0" {{ request('weekday') === '0' ? 'selected' : '' }}>Maandag</option>
                <option value="1" {{ request('weekday') === '1' ? 'selected' : '' }}>Dinsdag</option>
                <option value="2" {{ request('weekday') === '2' ? 'selected' : '' }}>Woensdag</option>
                <option value="3" {{ request('weekday') === '3' ? 'selected' : '' }}>Donderdag</option>
                <option value="4" {{ request('weekday') === '4' ? 'selected' : '' }}>Vrijdag</option>
                <option value="5" {{ request('weekday') === '5' ? 'selected' : '' }}>Zaterdag</option>
                <option value="6" {{ request('weekday') === '6' ? 'selected' : '' }}>Zondag</option>
            </select>
        </x-widget>
    </form>

    <script type="application/javascript">
        const links = [... document.getElementById("links").querySelectorAll("a")];
        const filters = document.getElementById("filters");
        filters.addEventListener("input", () => {
            if(filters.reportValidity()){
                const formData = new FormData(filters);
                const params = new URLSearchParams(formData);
                const url = new URL(window.location.href);
                url.search = "?" + params.toString();
                window.history.replaceState(null, undefined, url.href);
                for (const link of links) {
                    const url = new URL(link.href);
                    url.search = "?" + params.toString();
                    link.href = url.href;
                }
            }
        });
    </script>
</x-main>
