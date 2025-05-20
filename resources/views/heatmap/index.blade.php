<x-main title="Heatmap">
    <div id="map" style="height: 600px;"></div>

    <!-- Leaflet CSS en JS, plus leaflet.heat plugin -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <script>
        // Zet de kaart op Zeeland
        const map = L.map('map').setView([51.5, 3.9], 11);

        // Voeg OpenStreetMap tiles toe
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Haal coördinaten met intensiteit uit Laravel
        const coordinates = @json($coordinates);
        console.log(coordinates); // check of alles klopt

        // Voeg heatlayer toe
        L.heatLayer(coordinates, {
            radius: 25,
            blur: 15,
            maxZoom: 17,
            minOpacity: 0.2,
            maxOpacity: 1
        }).addTo(map);
    </script>

    <div class="h-15"></div>
</x-main>
