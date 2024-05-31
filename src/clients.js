$(document).ready(() => {
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB
    const PHONE_MASKS = Object.freeze(['(00) 00000-0000', '(00) 0000-0000']);

    let clientsArray = [];
    let mode = 'POST';

    const modal = $('#client-modal');
    const openModalBtn = $('#add-client');
    const closeModalBtn = $('#close-client-modal');
    const photoInput = $('#photo');
    const photoPreview = $('#photo-preview');
    const submitBtn = $('#submit-client');

    $('#phone').mask(PHONE_MASKS[1], {
        onKeyPress: function (val, _, field, options) {
            field.mask(val.charAt(5) === '9' ? PHONE_MASKS[0] : PHONE_MASKS[1], options);
        },
    });

    initializeEventListeners();
    fetchClients();

    function initializeEventListeners() {
        openModalBtn.click(openModal);
        closeModalBtn.click(closeModal);
        $(window).click(event => {
            if (event.target === modal[0]) {
                closeModal();
            }
        });
        photoInput.change(handlePhotoChange);
        $('#clientForm').on('submit', handleFormSubmit);
    }

    function openModal() {
        mode = 'POST';
        modal.css('display', 'flex');
    }

    function closeModal() {
        submitBtn.text('Cadastrar Cliente');
        modal.css('display', 'none');
        resetForm();
    }

    function handlePhotoChange() {
        const file = photoInput[0].files[0];
        if (file) {
            if (file.size > MAX_FILE_SIZE) {
                alert('File size exceeds 2 MB.');
                photoInput.val('');
                photoPreview.attr('src', '#').addClass('hidden');
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                photoPreview.attr('src', e.target.result).removeClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function handleFormSubmit(event) {
        event.preventDefault();

        const data = {
            id: $('#id').val(),
            name: $('#name').val(),
            dateOfBirth: $('#dateOfBirth').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            gender: $('#gender').val(),
            address: $('#address').val(),
        };

        const file = photoInput[0].files[0];
        if (file) {
            if (file.size > MAX_FILE_SIZE) {
                alert('File size exceeds 2 MB.');
                return;
            }
            const reader = new FileReader();
            reader.onloadend = () => {
                data.photo = reader.result;
                submitClient(data);
            };
            reader.readAsDataURL(file);
        } else {
            submitClient(data);
        }
    }

    function submitClient(data) {
        $.ajax({
            url: 'client.php',
            method: mode,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: () => {
                closeModal();
                fetchClients();
            },
            error: error => {
                console.error('Error adding client:', error);
            },
        });
    }

    function fetchClients(page = 1, limit = 10) {
        $.ajax({
            url: `client.php?page=${page}&limit=${limit}`,
            method: 'GET',
            success: handleFetchClientsSuccess,
            error: error => {
                console.error('Error fetching clients:', error);
            },
        });
    }

    function handleFetchClientsSuccess(response) {
        if (response.redirect) {
            window.location.href = response.redirect;
            return;
        }

        const tableBody = $('#client-table-body');
        tableBody.empty();

        if (response.length === 0) {
            tableBody.append('<tr><td colspan="6" class="px-4 py-2 text-center">Nenhum cliente cadastrado.</td></tr>');
            return;
        }

        clientsArray = response;
        clientsArray.forEach(client => {
            const row = $('<tr>').addClass('border-b');

            const avatarCell = $('<td>')
                .addClass('px-4 py-2')
                .append(
                    $('<div>')
                        .addClass('flex items-center gap-2')
                        .append(
                            $('<img>')
                                .attr('src', client.photo ? client.photo : '/fitsystem/placeholder.webp')
                                .attr('width', 40)
                                .attr('height', 40)
                                .addClass('rounded-full')
                                .attr('alt', 'Client Avatar')
                                .css({
                                    aspectRatio: '40/40',
                                    objectFit: 'cover',
                                })
                        )
                );

            const nameCell = $('<td>').addClass('px-4 py-2').text(client.name);
            const emailCell = $('<td>').addClass('px-4 py-2').text(client.email);
            const phoneCell = $('<td>').addClass('px-4 py-2').text(client.phone);
            const statusCell = $('<td>').addClass('px-4 py-2').text('Ativo');

            const actionsCell = $('<td>')
                .addClass('px-4 py-2')
                .append(
                    $('<div>')
                        .addClass('flex items-center gap-2')
                        .append(
                            $('<button>')
                                .attr('title', 'Editar')
                                .addClass('inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer')
                                .attr('onclick', `editClient(${client.id})`)
                                .append($('<i>').attr('data-lucide', 'file-pen')),
                            $('<button>')
                                .attr('title', 'Excluir')
                                .addClass('inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer')
                                .attr('onclick', `deleteClient(${client.id})`)
                                .append($('<i>').attr('data-lucide', 'trash'))
                        )
                );

            row.append(avatarCell, nameCell, emailCell, phoneCell, statusCell, actionsCell);
            tableBody.append(row);
        });

        lucide.createIcons();
    }

    function editClient(id) {
        const client = clientsArray.find(client => client.id === id);
        const formattedDate = new Date(client.dateOfBirth).toISOString().split('T')[0];
        $('#id').val(client.id);
        $('#name').val(client.name);
        $('#dateOfBirth').val(formattedDate);
        $('#email').val(client.email);
        $('#phone').val(client.phone);
        $('#gender').val(client.gender).change();
        $('#address').val(client.address);
        photoPreview.attr('src', client.photo ? client.photo : '/fitsystem/placeholder.webp');

        submitBtn.text('Atualizar Cliente');
        mode = 'PUT';
        modal.css('display', 'flex');
    }
    function handleFetchClientsSuccess(response) {
        if (response.redirect) {
            window.location.href = response.redirect;
            return;
        }

        const tableBody = $('#client-table-body');
        tableBody.empty();

        if (response.length === 0) {
            tableBody.append('<tr><td colspan="6" class="px-4 py-2 text-center">Nenhum cliente cadastrado.</td></tr>');
            return;
        }

        clientsArray = response;
        clientsArray.forEach(client => {
            const row = $('<tr>').addClass('border-b');

            const avatarCell = $('<td>')
                .addClass('px-4 py-2')
                .append(
                    $('<div>')
                        .addClass('flex items-center gap-2')
                        .append(
                            $('<img>')
                                .attr('src', client.photo ? client.photo : '/fitsystem/placeholder.webp')
                                .attr('width', 40)
                                .attr('height', 40)
                                .addClass('rounded-full')
                                .attr('alt', 'Client Avatar')
                                .css({
                                    aspectRatio: '40/40',
                                    objectFit: 'cover',
                                })
                        )
                );

            const nameCell = $('<td>').addClass('px-4 py-2').text(client.name);
            const emailCell = $('<td>').addClass('px-4 py-2').text(client.email);
            const phoneCell = $('<td>').addClass('px-4 py-2').text(client.phone);
            const statusCell = $('<td>').addClass('px-4 py-2').text('Ativo');

            const actionsCell = $('<td>')
                .addClass('px-4 py-2')
                .append(
                    $('<div>')
                        .addClass('flex items-center gap-2')
                        .append(
                            $('<button>')
                                .attr('title', 'Editar')
                                .addClass('inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer')
                                .attr('onclick', `editClient(${client.id})`)
                                .append($('<i>').attr('data-lucide', 'file-pen')),
                            $('<button>')
                                .attr('title', 'Excluir')
                                .addClass('inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer')
                                .attr('onclick', `deleteClient(${client.id})`)
                                .append($('<i>').attr('data-lucide', 'trash'))
                        )
                );

            row.append(avatarCell, nameCell, emailCell, phoneCell, statusCell, actionsCell);
            tableBody.append(row);
        });

        lucide.createIcons();
    }

    function deleteClient(id) {
        $.ajax({
            url: `client.php`,
            method: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ id }),
            success: () => fetchClients(),
            error: error => {
                console.error('Error deleting client:', error);
            },
        });
    }

    function resetForm() {
        $('#clientForm').trigger('reset');
        photoPreview.attr('src', './placeholder.webp');
    }

    window.editClient = editClient;
    window.deleteClient = deleteClient;
});
