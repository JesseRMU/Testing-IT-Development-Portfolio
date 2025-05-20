
    <x-main title="Heatmap">
        <div id="map" style="height: 600px;"></div>

        <!-- Leaflet CSS en JS, plus leaflet.heat plugin -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

        <script>
            // Zet de kaart neer op Zeeland
            const map = L.map('map').setView([51.5, 3.9], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            // Haal steiger-coördinaten uit Laravel (komt uit de controller)
            const steigers = @json($steigers);
            console.log(steigers);

            // Zet om naar heatmap formaat: [lat, lng, intensity]
            const heatPoints = steigers.map(steiger => [
                steiger.latitude,
                steiger.longitude,
                1  // vaste intensiteit voor nu
            ]);

            // Voeg heatlayer toe
            L.heatLayer(heatPoints, {
                radius: 25,
                blur: 15,
                maxZoom: 17,
                minOpacity: 0.5,
                maxOpacity: 0.8
            }).addTo(map);
        </script>

        <div class="h-15"></div>
    </x-main>
