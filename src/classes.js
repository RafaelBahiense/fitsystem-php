$(document).ready(() => {
    const WEEK_DAYS = Object.freeze([
        { name: 'Domingo', value: 'Sunday' },
        { name: 'Segunda', value: 'Monday' },
        { name: 'Terça', value: 'Tuesday' },
        { name: 'Quarta', value: 'Wednesday' },
        { name: 'Quinta', value: 'Thursday' },
        { name: 'Sexta', value: 'Friday' },
        { name: 'Sábado', value: 'Saturday' },
    ]);
    const HOURS_OF_DAY = Object.freeze(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00']);

    let classesArray = [];
    let selectedIcon = '';
    let mode = 'POST';

    const classModal = $('#class-modal');
    const openClassModalBtn = $('#add-class');
    const closeClassModalBtn = $('#close-class-modal');
    const closeClassScheduleModalBtn = $('#close-class-schedule-modal');
    const classModalSubmitBtn = $('#submit-class');
    const selectedIconContainer = $('#selected-icon-container');

    initializeEventListeners();
    getIcons();
    fetchClasses();
    renderScheduleTable();

    function initializeEventListeners() {
        openClassModalBtn.click(openClassModal);
        closeClassModalBtn.click(() => classModal.css('display', 'none'));
        closeClassScheduleModalBtn.click(() => $('#class-schedules-modal').css('display', 'none'));
        $(window).click(event => {
            if (event.target === classModal[0]) {
                classModal.css('display', 'none');
            }
        });
        $('#classForm').on('submit', handleFormSubmit);
    }

    function openClassModal() {
        clearForm();
        mode = 'POST';
        classModalSubmitBtn.text('Cadastrar Classe');
        classModal.css('display', 'flex');
    }

    function handleFormSubmit(event) {
        event.preventDefault();

        const data = {
            id: $('#class-id').val(),
            name: $('#name').val(),
            icon: selectedIcon,
            description: $('#description').val(),
            status: true,
        };

        $.ajax({
            url: 'class.php',
            method: mode,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: () => {
                classModal.css('display', 'none');
                clearForm();
                fetchClasses();
            },
            error: error => {
                console.error('Error adding client:', error);
            },
        });
    }

    function clearForm() {
        $('#class-id').val('');
        $('#name').val('');
        $('#description').val('');
        $('#status').val('');
        setSelectIcon('');
    }

    function setSelectIcon(icon, iconContainer = null) {
        removeSelectionBorderFromPreviousIcon();
        if (!icon) {
            selectedIcon = '';
            selectedIconContainer.html('');
            return;
        }

        if (!iconContainer) {
            const nodeList = $(`svg[data-lucide=${icon}]`);
            if (nodeList.length > 0) {
                iconContainer = nodeList.last().parent();
            }
        }

        iconContainer.toggleClass('border border-red-500');

        selectedIcon = icon;

        const iconElement = $('<i>').attr('data-lucide', icon).addClass('m-2');
        selectedIconContainer.html('').append(iconElement);

        lucide.createIcons();
    }

    function removeSelectionBorderFromPreviousIcon() {
        $('div.border.border-red-500').removeClass('border border-red-500');
    }

    function fetchClasses(page = 1, limit = 10) {
        $.ajax({
            url: `class.php?page=${page}&limit=${limit}`,
            method: 'GET',
            success: handleFetchClassesSuccess,
            error: error => {
                console.error('Error fetching clients:', error);
            },
        });
    }

    function handleFetchClassesSuccess(response) {
        if (response.redirect) {
            window.location.href = response.redirect;
            return;
        }

        classesArray = response;

        const tableBody = $('#class-table-body');
        tableBody.html('');

        if (response.length === 0) {
            const noDataCell = $('<td>').attr('colspan', 6).addClass('px-4 py-2 text-center').text('Nenhum cliente cadastrado.');
            const noDataRow = $('<tr>').append(noDataCell);
            tableBody.append(noDataRow);
            return;
        }

        response.forEach(classRow => {
            const row = $('<tr>').addClass('border-b border-gray-200');

            const iconCell = $('<td>').addClass('px-4 py-2 text-left').append($('<i>').attr('data-lucide', classRow.icon));
            const nameCell = $('<td>').addClass('px-4 py-2 text-left').text(classRow.name);
            const scheduleCell = $('<td>').addClass('px-4 py-2 text-left').text('Terça e Quinta 18:00');
            const subscribersCell = $('<td>')
                .addClass('px-4 py-2 text-left')
                .append($('<div>').addClass('flex items-center gap-1 text-green-500').append($('<i>').attr('data-lucide', 'users')).append($('<span>').text('15/20 Inscritos')));
            const statusCell = $('<td>').addClass('px-4 py-2 text-left').text('Ativo');
            const actionsCell = $('<td>').addClass('px-4 py-2 text-left').append($('<div>').addClass('flex items-center gap-2'));

            const buttons = [
                { title: 'Adicionar Horários', icon: 'calendar-plus', action: `openScheduleModal(${classRow.id})` },
                { title: 'Editar', icon: 'file-pen', action: `editClass(${classRow.id})` },
                { title: 'Excluir', icon: 'trash', action: `deleteClass(${classRow.id})` },
            ];

            buttons.forEach(button => {
                const btn = $('<button>')
                    .attr('title', button.title)
                    .attr('onclick', button.action)
                    .addClass('inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer')
                    .append($('<i>').attr('data-lucide', button.icon));
                actionsCell.find('div').append(btn);
            });

            row.append(iconCell, nameCell, scheduleCell, subscribersCell, statusCell, actionsCell);
            tableBody.append(row);
        });

        lucide.createIcons();
    }

    function editClass(id) {
        const classRow = classesArray.find(classRow => classRow.id === id);
        $('#class-id').val(classRow.id);
        $('#name').val(classRow.name);
        $('#description').val(classRow.description);
        setSelectIcon(classRow.icon);

        classModalSubmitBtn.text('Atualizar Classe');
        mode = 'PUT';
        classModal.css('display', 'flex');
    }

    function deleteClass(id) {
        $.ajax({
            url: `class.php`,
            method: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ id }),
            success: () => fetchClasses(),
            error: error => {
                console.error('Error deleting client:', error);
            },
        });
    }

    window.editClass = editClass;
    window.deleteClass = deleteClass;

    function renderIconGrid(iconNames) {
        const iconGrid = $('#iconGrid');

        if (!iconGrid.length) {
            console.error('Icon grid element not found');
            return;
        }

        iconNames.forEach(iconName => {
            const iconContainer = $('<div>');
            const iconElement = $('<i>').attr('data-lucide', iconName).addClass('m-2 cursor-pointer');

            iconContainer.click(e => {
                const target = $(e.target);
                const dataLucide = target.attr('data-lucide') || target.closest('[data-lucide]').attr('data-lucide');
                if (dataLucide) {
                    setSelectIcon(dataLucide, iconContainer);
                }
            });

            iconContainer.append(iconElement);
            iconGrid.append(iconContainer);
        });

        lucide.createIcons();
    }

    function getIcons() {
        $.ajax({
            url: 'svg-icons.json',
            method: 'GET',
            success: response => renderIconGrid(JSON.parse(response)),
            error: error => {
                console.error('Error fetching icons:', error);
            },
        });
    }

    function renderScheduleTable() {
        const tableBody = $('#class-schedules-table-body');
        tableBody.html('');

        WEEK_DAYS.forEach(day => {
            const row = $('<tr>');

            const dayCell = $('<td>').addClass('px-4 py-2 text-left').text(day.name);
            row.append(dayCell);

            HOURS_OF_DAY.forEach(hour => {
                const hourCell = $('<td>').addClass('px-4 py-2 text-left');
                const input = $('<input>').attr('type', 'checkbox').attr('name', `${day.value}-${hour}`);
                hourCell.append(input);
                row.append(hourCell);
            });

            tableBody.append(row);
        });
    }

    window.openScheduleModal = function () {
        $('#class-schedules-modal').css('display', 'flex');
    };
});
