const ctx = document.getElementById('myChart');
let chart;

if(groupByTime){
    fetch(`/graphs?group_by_time=${groupByTime}`)
        .then((response) => response.json())
        .then((data) => {
            createChart(data, 'bar');
        });

    function createChart(chartData, type) {
        chart = new Chart(ctx, {
            type: type,
            data: {
                // Horizontaal
                labels: groupByTime
                    ? chartData.labels
                    : chartData.map(row => row.evenement_id),
                datasets: [{
                    label: groupByTime ? 'aantal schepen' : '# ship length',
                    // Verticaal
                    data: groupByTime
                        ? chartData.datasets[0].data
                        : chartData.map(row => row.lengte),
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
    fetch(`/graphs?group_by_time=${groupByTime}`)
        .then((response) => response.json())
        .then((data) => {
            createChart(data, 'bar');
        });

    function createChart(chartData, type) {
        chart = new Chart(ctx, {
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

// Export naar png
document.getElementById('exportChartPNG').addEventListener('click', function () {
    if (chart) {
        const image = chart.toBase64Image();
        const link = document.createElement('a');
        link.href = image;
        link.download = 'chart.png';
        link.click();
    }
});

// Export naar jpg
document.getElementById('exportChartJPG').addEventListener('click', function () {
    const canvas = document.createElement('canvas');
    canvas.width = chart.width;
    canvas.height = chart.height;
    const ctx = canvas.getContext('2d');

    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(chart.canvas, 0, 0);

    const image = canvas.toDataURL('image/jpeg');
    const link = document.createElement('a');
    link.href = image;
    link.download = 'chart.jpg';
    link.click();
});

// Export naar pdf
document.getElementById('exportChartPDF').addEventListener('click', async function () {
    if (chart) {
        const image = chart.toBase64Image();

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.addImage(image, 'PNG', 10, 10, 180, 100);
        doc.save('chart.pdf');
    }
});
