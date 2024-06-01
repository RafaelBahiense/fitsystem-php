$(document).ready(() => {
    const data = [
        { year: 2010, count: 10 },
        { year: 2011, count: 20 },
        { year: 2012, count: 30 },
        { year: 2013, count: 40 },
        { year: 2014, count: 50 },
        { year: 2015, count: 60 },
        { year: 2016, count: 70 },
        { year: 2017, count: 80 },
        { year: 2018, count: 90 },
        { year: 2019, count: 100 },
    ];

    const chart1 = new Chart(document.getElementById('user-chart'), {
        type: 'bar',
        data: {
            labels: data.map(row => row.year),
            datasets: [
                {
                    label: 'Teste de gráfico de barras',
                    data: data.map(row => row.count),
                },
            ],
        },
    });

    const chart2 = new Chart(document.getElementById('user-chart-2'), {
        type: 'line',
        data: {
            labels: data.map(row => row.year),
            datasets: [
                {
                    label: 'Teste de gráfico de linhas',
                    data: data.map(row => row.count),
                },
            ],
        },
    });

    lucide.createIcons();
});
