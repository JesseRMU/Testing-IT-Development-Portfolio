const ctx = document.getElementById('myChart');

fetch("script.php")
    .then((response) => {
     return response.json();
    })
    .then((data) => {
        createChart(data, 'bar')
    })

function createChart(chartData, type) {
    new Chart(ctx, {
        type: type,
        data: {
            labels: chartData.map(row => row.schip_id),
            datasets: [{
                label: '# ship length',
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
