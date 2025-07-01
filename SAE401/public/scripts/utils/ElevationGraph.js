export function setElevationGraph(ctx, elevationDistanceData) {
    const ElevationChart = new Chart(ctx,
        {
            type: 'line',
            pointRadius: 0,

            options: {

                animation: true,

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'nearest',
                        enabled: true,
                        intersect: false
                    }
                },

                scales: {

                    x: {
                        ticks: {
                            callback: function (value, index) {
                                const total = elevationDistanceData.length;
                                const step = Math.floor(total / 10);
                                if (index % step === 0) {
                                    return (this.getLabelForValue(value) / 1000).toFixed(1) + ' km';
                                }
                                return '';
                            },
                            autoSkip: false
                        },
                        grid: {
                            display: false
                        }
                    },

                    y: {
                        display: true
                    }

                }
            },

            data: {

                labels: elevationDistanceData.map(row => row.distance),

                datasets: [
                    {
                        label: 'Elevation',
                        data: elevationDistanceData.map(row => row.ele),
                        backgroundColor: '#63F679',
                        borderColor: '#63F679',
                        pointRadius: 0,
                        fill: true
                    }]
            }
        });

    return ElevationChart
}


export function updateChartData(ElevationChart, elevationDistanceData) {
    ElevationChart.data.labels = elevationDistanceData.map(row => row.distance);
    ElevationChart.data.datasets[0].data = elevationDistanceData.map(row => row.ele);

    ElevationChart.options.scales.y.min = Math.min(...elevationDistanceData.map(row => row.ele));
    ElevationChart.options.scales.y.max = Math.max(...elevationDistanceData.map(row => row.ele)) * 1.01;

    ElevationChart.options.scales.x.ticks.callback = function (value, index) {
        const total = elevationDistanceData.length;
        const step = Math.floor(total / 10);
        if (index % step === 0) {
            return (this.getLabelForValue(value) / 1000).toFixed(1) + ' km';
        }
        return '';
    };

    ElevationChart.update();
}