function renderChart(ctx, x, y) {

        new Chart(ctx, {
        type: 'line',
        data: {
        labels: x,
        datasets: [{
        label: '# of Votes',
        data: y,
        borderWidth: 1
    }]
    }
    });
}