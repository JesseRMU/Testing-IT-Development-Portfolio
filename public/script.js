const ctx = document.getElementById('myChart');

fetch("/schepen") // This is the Laravel route now
    .then((response) => response.json())
    .then((data) => {
        createChart(data, 'bar');
    });

function createChart(chartData, type) {
    new Chart(ctx, {
        type: type,
        data: {
            // Horizontaal
            labels: chartData.map(row => row.schip_id),
            datasets: [{
                label: '# ship length',
                // Verticaal
                data: chartData.map(row => row.lengte),
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
