<x-main title="Heatmap">
    <div id="map" style="height: 600px;"></div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <script>
        // Zet de kaart op Zeeland
        const map = L.map('map').setView([51.5, 3.9], 11);

        // Voeg OpenStreetMap toe
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Zet de coordinaten en intensity om naar json
        const coordinates = @json($coordinates);

        // Voeg heatlayer toe
        L.heatLayer(coordinates, {
            radius: 25,
            blur: 15,
            maxZoom: 17,
            minOpacity: 0.2,
            maxOpacity: 10
        }).addTo(map);
    </script>

    <div class="h-15"></div>
</x-main>
