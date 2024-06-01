$(document).ready(() => {
    const WEEK_DAYS = Object.freeze([
        { name: 'Domingo', value: 'Sunday', letter: 'D' },
        { name: 'Segunda', value: 'Monday', letter: 'S' },
        { name: 'Terça', value: 'Tuesday', letter: 'T' },
        { name: 'Quarta', value: 'Wednesday', letter: 'Q' },
        { name: 'Quinta', value: 'Thursday', letter: 'Q' },
        { name: 'Sexta', value: 'Friday', letter: 'S' },
        { name: 'Sábado', value: 'Saturday', letter: 'S' },
    ]);
    const HOURS_OF_DAY = Object.freeze(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00']);

    let classesArray = [];
    let selectedIcon = '';
    let mode = 'POST';

    const classModal = $('#class-modal');
    const openClassModalBtn = $('#add-class');
    const closeClassModalBtn = $('#close-class-modal');
    const classModalSubmitBtn = $('#submit-class');

    const closeClassScheduleModalBtn = $('#close-class-schedule-modal');
    const closeClassSubscriptionModalBtn = $('#close-class-subscription-modal');

    const selectedIconContainer = $('#selected-icon-container');

    initializeEventListeners();
    getIcons();
    fetchClasses();

    function initializeEventListeners() {
        openClassModalBtn.click(openClassModal);
        closeClassModalBtn.click(() => classModal.css('display', 'none'));
        closeClassScheduleModalBtn.click(() => $('#class-schedules-modal').css('display', 'none'));
        closeClassSubscriptionModalBtn.click(() => $('#class-subscription-modal').css('display', 'none'));
        $(window).click(event => {
            if (event.target === classModal[0]) {
                classModal.css('display', 'none');
            }
        });
        $('#classForm').on('submit', handleFormSubmit);
        $('#classSchedulesForm').on('submit', handleScheduleSubmit);
        $('#classSubscriptionsForm').on('submit', handleSubscriptionSubmit);
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

    function handleScheduleSubmit(event) {
        event.preventDefault();

        const data = {
            classId: $('#class-id-to-schedule').val(),
            schedules: classesArray.find(classRow => classRow.id == $('#class-id-to-schedule').val()).schedules,
        };

        $.ajax({
            url: 'class_schedule.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: _ => {
                $('#class-schedules-modal').css('display', 'none');
                fetchClasses();
            },
            error: error => {
                console.error('Error adding class_schedule:', error);
            },
        });
    }

    function handleSubscriptionSubmit(event) {
        event.preventDefault();

        const data = {
            classId: $('#class-id-to-subscribe').val(),
            subscriptions: classesArray.find(classRow => classRow.id == $('#class-id-to-subscribe').val()).subscriptions,
        };

        $.ajax({
            url: 'class_subscription.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: _ => {
                $('#class-subscription-modal').css('display', 'none');
                fetchClasses();
            },
            error: error => {
                console.error('Error adding class_subscription:', error);
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
            const scheduleCell = $('<td>').addClass('px-4 py-2 text-left');

            const subscriptionsCount = classRow.subscriptions.filter(subscription => subscription.subscription_id).length;

            WEEK_DAYS.forEach(day => {
                const dayIndicator = $('<span>').text(day.letter).addClass('text-md font-bold rounded-full px-2 py-1 mr-1');
                const hasDay = classRow.schedules.some(schedule => schedule.weekday === day.value);
                if (hasDay) {
                    dayIndicator.addClass('bg-green-500 text-white');
                } else {
                    dayIndicator.addClass('bg-gray-200 text-gray-500');
                }
                scheduleCell.append(dayIndicator);
            });

            const subscribersCell = $('<td>')
                .addClass('px-4 py-2 text-left')
                .append(
                    $('<div>')
                        .addClass('flex items-center gap-1')
                        .append($('<i>').attr('data-lucide', 'users'))
                        .append($('<span>').text(`${subscriptionsCount} Inscritos`))
                );
            const statusCell = $('<td>').addClass('px-4 py-2 text-left').text('Ativo');
            const actionsCell = $('<td>').addClass('px-4 py-2 text-left').append($('<div>').addClass('flex items-center gap-2'));

            const buttons = [
                { title: 'Inscritos', icon: 'users', action: `openSubscribersModal(${classRow.id})` },
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

    function renderScheduleTable(classId) {
        const tableBody = $('#class-schedules-table-body');
        const classRow = classesArray.find(classRow => classRow.id === classId);
        tableBody.html('');

        WEEK_DAYS.forEach(day => {
            const row = $('<tr>');

            const dayCell = $('<td>').addClass('px-4 py-2 text-left').text(day.name);
            row.append(dayCell);

            HOURS_OF_DAY.forEach(hour => {
                const hourCell = $('<td>').addClass('px-4 py-2 text-left');
                const input = $('<input>').attr('type', 'checkbox').attr('name', `${day.value}-${hour}`);
                const checked = classRow.schedules.find(schedule => schedule.weekday === day.value && schedule.hour === hour);
                if (checked) {
                    input.attr('checked', 'checked');
                }
                input.attr('value', `${day.value}-${hour}`);
                input.attr('onclick', `toggleSchedule('${classRow.id}-${day.value}-${hour}')`);
                hourCell.append(input);
                row.append(hourCell);
            });

            tableBody.append(row);
        });
    }

    function renderSubscribersTable(classId) {
        const tableBody = $('#class-subscription-table-body');
        const classRow = classesArray.find(classRow => classRow.id === classId);
        tableBody.html('');

        classRow.subscriptions.forEach(subscription => {
            const row = $('<tr>');

            const nameCell = $('<td>').addClass('px-4 py-2 text-left').text(subscription.client_name);
            const subscriptionCheckbox = $('<input>').attr('type', 'checkbox').attr('name', `subscription-${subscription.client_id}`);
            if (subscription.subscription_id) {
                subscriptionCheckbox.attr('checked', 'checked');
            }
            subscriptionCheckbox.attr('value', subscription.client_id);
            subscriptionCheckbox.attr('onclick', `toggleSubscription('${classRow.id}-${subscription.client_id}')`);
            const subscriptionCell = $('<td>').addClass('px-4 py-2 text-left').append(subscriptionCheckbox);

            row.append(nameCell, subscriptionCell);
            tableBody.append(row);
        });
    }

    window.toggleSchedule = function (scheduleStr) {
        const [classId, day, hour] = scheduleStr.split('-');

        const classRow = classesArray.find(classRow => classRow.id == classId);
        const schedule = classRow.schedules.find(schedule => schedule.weekday === day && schedule.hour === hour);
        if (schedule) {
            if (schedule.id) schedule.markedForDeletion = true;
            else classRow.schedules = classRow.schedules.filter(schedule => !(schedule.weekday === day && schedule.hour === hour));
        } else {
            classRow.schedules.push({ weekday: day, hour });
        }
    };

    window.toggleSubscription = function (subscriptionStr) {
        const [classId, clientId] = subscriptionStr.split('-');

        const classRow = classesArray.find(classRow => classRow.id == classId);
        const subscription = classRow.subscriptions.find(subscription => subscription.client_id == clientId);
        if (subscription) {
            if (subscription.markedForInsertion) {
                delete subscription.markedForInsertion;
            } else if (subscription.subscription_id) {
                subscription.markedForDeletion = true;
            } else {
                subscription.markedForInsertion = true;
            }
        }
        console.log(classesArray);
    };

    window.openScheduleModal = function (classId) {
        $('#class-id-to-schedule').val(classId);
        renderScheduleTable(classId);
        $('#class-schedules-modal').css('display', 'flex');
    };

    window.openSubscribersModal = function (classId) {
        $('#class-id-to-subscribe').val(classId);
        renderSubscribersTable(classId);
        $('#class-subscription-modal').css('display', 'flex');
    };
});
