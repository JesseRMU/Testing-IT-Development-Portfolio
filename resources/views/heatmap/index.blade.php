<x-main>
    <x-main title="Heatmap">
        <div id="map" style="height: 600px;"></div>

        <!-- Leaflet en leaflet.heat alleen hier -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

        <script>
            const map = L.map('map').setView([51.5, 3.9], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            function generateRandomZeelandPoints(amount) {
                const points = [];
                for (let i = 0; i < amount; i++) {
                    const lat = 51.4 + Math.random() * 0.2;
                    const lng = 3.55 + Math.random() * 0.4;
                    const intensity = Math.random();
                    points.push([lat, lng, intensity]);
                }
                return points;
            }

            const dummyData = generateRandomZeelandPoints(50);

            const heat = L.heatLayer(dummyData, {
                radius: 25,         // of 20 voor een compactere vlek
                blur: 15,           // meer blur = zachtere vlekken
                maxZoom: 17,
                minOpacity: 0.5,
                maxOpacity: 0.8     // verhoog dit naar 0.8 of 1
            }).addTo(map);
        </script>

        <div class="h-15"></div> <!-- geeft beetje ruimte onder de kaart -->
    </x-main>

</x-main>
