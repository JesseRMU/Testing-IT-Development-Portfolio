const ctx = document.getElementById('myChart');
if(groupByTime){
    fetch(`/graphs${window.location.search || "?"}&group_by_time=${groupByTime}`)
        .then((response) => response.json())
        .then((data) => {
            createChart(data, 'bar');
        });

    function createChart(chartData, type) {
        new Chart(ctx, {
            type: type,
            data: {
                // Horizontaal
                labels: chartData.labels,
                datasets: [{
                    label: 'aantal schepen',
                    // Verticaal
                    data: chartData.datasets[0].data,
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

}
else {
    fetch(`/graphs${window.location.search}`)
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
}
