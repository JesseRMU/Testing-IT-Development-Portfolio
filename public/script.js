const ctx = document.getElementById('myChart');

fetch("/graphs")
    .then((response) => response.json())
    .then((data) => {
        createChart(data, 'bar');
    });

function createChart(chartData, type) {
    new Chart(ctx, {
        type: type,
        data: {
            // Horizontaal
            labels: chartData.map(row => row.evenement_id),
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
