$(document).ready(() => {
    fetchClientProgressData()
        .then(clients => {
            renderClientProgress(clients);
            lucide.createIcons();
        })
        .catch(error => {
            console.error('Error fetching client data:', error);
        });
});

function fetchClientProgressData() {
    return $.ajax({
        url: 'progress.php',
        method: 'GET',
        contentType: 'application/json',
        dataType: 'json',
    });
}

function renderClientProgress(clients) {
    const weekLabels = generateLastSixWeeks();

    clients.forEach(client => {
        const clientCard = $('<div>').addClass('rounded-lg border bg-card text-card-foreground shadow-sm');

        const cardContent = $('<div>').addClass('flex flex-col space-y-1.5 p-6');
        const clientInfo = $('<div>').addClass('flex items-center gap-2');

        const avatar = $('<img>')
            .attr('src', client.photo ?? '/fitsystem/placeholder.webp')
            .attr('width', '40')
            .attr('height', '40')
            .addClass('rounded-full')
            .attr('alt', 'Client Avatar')
            .css('aspect-ratio', '40/40')
            .css('object-fit', 'cover');

        const clientDetails = $('<div>');
        const clientName = $('<h3>').addClass('font-medium').text(client.name);
        const clientStatus = $('<p>').addClass('text-gray-500 text-sm').text('Ativo');

        clientDetails.append(clientName, clientStatus);
        clientInfo.append(avatar, clientDetails);
        cardContent.append(clientInfo);

        clientCard.append(cardContent);

        const chartContainer = $('<div>').addClass('p-6');
        const aspectDiv = $('<div>').addClass('aspect-[4/3]');
        const canvasDiv = $('<div>').css({ width: '100%', height: '100%' });
        const canvas = $('<canvas>').attr('id', `user-chart-${client.client_id}`);

        canvasDiv.append(canvas);
        aspectDiv.append(canvasDiv);
        chartContainer.append(aspectDiv);

        clientCard.append(chartContainer);

        const weeklyProgress = client.weekly_progress.reduce((acc, progress) => {
            acc[progress.week] = progress.classes_attended;
            return acc;
        }, {});

        const labels = weekLabels.map(week => week.label);
        const data = weekLabels.map(week => weeklyProgress[week.week] || 0);

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Aulas comparecidas',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0,
                        suggestedMax: 10,
                    },
                },
            },
        });

        $('#client-progress-container').append(clientCard);
    });
}

function generateLastSixWeeks() {
    const weeks = [];
    const currentDate = new Date();
    for (let i = 5; i >= 0; i--) {
        const date = new Date(currentDate);
        date.setDate(date.getDate() - i * 7);
        const weekNumber = getWeekNumber(date);
        weeks.push({
            week: `${date.getFullYear()}${weekNumber}`,
            label: `Semana ${weekNumber} (${formatDate(date)})`,
        });
    }
    return weeks;
}

function getWeekNumber(date) {
    const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
    const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
    return String(Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7)).padStart(2, '0');
}

function formatDate(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
