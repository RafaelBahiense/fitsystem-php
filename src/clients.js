$(document).ready(() => {
    const modal = document.getElementById('client-modal');
    const openModalBtn = document.getElementById('add-client');
    const closeModalBtn = document.getElementById('close-client-modal');
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const submitBtn = document.getElementById('submit-client');
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB
    let CLIENTS_ARRAY = [];
    let mode = 'POST';

    const masks = ['(00) 00000-0000', '(00) 0000-0000'];

    $('#phone').mask(masks[1], {
        onKeyPress: function (val, e, field, options) {
            field.mask(val.charAt(5) === '9' ? masks[0] : masks[1], options);
        },
    });

    openModalBtn.addEventListener('click', () => {
        mode = 'POST';
        modal.style.display = 'flex';
    });

    closeModalBtn.addEventListener('click', () => {
        submitBtn.innerText = 'Cadastar Cliente';
        modal.style.display = 'none';
        resetForm();
    });

    window.addEventListener('click', event => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        if (file) {
            if (file.size > MAX_FILE_SIZE) {
                alert('File size exceeds 2 MB.');
                photoInput.value = '';
                photoPreview.src = '#';
                photoPreview.classList.add('hidden');
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                photoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    function fetchClients(page = 1, limit = 10) {
        $.ajax({
            url: `client.php?page=${page}&limit=${limit}`,
            method: 'GET',
            success: response => {
                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }

                if (response.length === 0) {
                    const tableBody = $('#client-table-body');
                    tableBody.empty();
                    tableBody.append('<tr><td colspan="6" class="px-4 py-2 text-center">Nenhum cliente cadastrado.</td></tr>');
                    return;
                }

                CLIENTS_ARRAY = response;
                const tableBody = $('#client-table-body');
                tableBody.empty();
                CLIENTS_ARRAY.forEach(client => {
                    const row = `
                        <tr class='border-b'>
                            <td class='px-4 py-2'>
                                <div class='flex items-center gap-2'>
                                    <img 
                                        src='${client.photo ? client.photo : '/fitsystem/placeholder.webp'}'
                                        width='40'
                                        height='40'
                                        class='rounded-full'
                                        alt='Client Avatar'
                                        style='aspect-ratio:40/40;object-fit:cover'
                                    />
                                </div>
                            </td>
                            <td class='px-4 py-2'>${client.name}</td>
                            <td class='px-4 py-2'>${client.email}</td>
                            <td class='px-4 py-2'>${client.phone}</td>
                            <td class='px-4 py-2'>Ativo</td>
                            <td class='px-4 py-2'>
                                <div class='flex items-center gap-2'>
                                    <button
                                        title='Editar'
                                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer'
                                        onclick="editClient(${client.id})"
                                    >
                                        <i data-lucide='file-pen'></i> 
                                    </button>
                                    <button
                                        title='Excluir'
                                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer' 
                                        onclick="deleteClient(${client.id})"
                                    >
                                        <i data-lucide='trash'></i> 
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                lucide.createIcons();
            },
            error: error => {
                console.error('Error fetching clients:', error);
            },
        });
    }

    function editClient(id) {
        const client = CLIENTS_ARRAY.find(client => client.id === id);
        const date = new Date(client.dateOfBirth);
        const formattedDate = date.toISOString().split('T')[0];
        $('#id').val(client.id);
        $('#name').val(client.name);
        $('#dateOfBirth').val(formattedDate);
        $('#email').val(client.email);
        $('#phone').val(client.phone);
        $('#gender').val(client.gender).change();
        $('#address').val(client.address);
        $('#photo-preview').attr('src', client.photo ? client.photo : '/fitsystem/placeholder.webp');

        submitBtn.innerText = 'Atualizar Cliente';
        mode = 'PUT';
        modal.style.display = 'flex';
    }

    function deleteClient(id) {
        $.ajax({
            url: `client.php`,
            method: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ id }),
            success: response => {
                fetchClients();
            },
            error: error => {
                console.error('Error deleting client:', error);
            },
        });
    }

    function resetForm() {
        $('#clientForm').trigger('reset');
        photoPreview.src = './placeholder.webp';
    }

    window.editClient = editClient;
    window.deleteClient = deleteClient;

    fetchClients();

    $('#clientForm').on('submit', event => {
        event.preventDefault();

        const data = {};

        data.id = $('#id').val();
        data.name = $('#name').val();
        data.dateOfBirth = $('#dateOfBirth').val();
        data.email = $('#email').val();
        data.phone = $('#phone').val();
        data.gender = $('#gender').val();
        data.address = $('#address').val();
        console.log(data);

        const file = photoInput.files[0];
        if (file) {
            if (file.size > MAX_FILE_SIZE) {
                alert('File size exceeds 2 MB.');
                return;
            }

            const reader = new FileReader();
            reader.onloadend = () => {
                data.photo = reader.result;

                $.ajax({
                    url: 'client.php',
                    method: mode,
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: _ => {
                        modal.style.display = 'none';
                        fetchClients();
                        resetForm();
                    },
                    error: error => {
                        console.error('Error adding client:', error);
                    },
                });
            };
            reader.readAsDataURL(file);
        } else {
            $.ajax({
                url: 'client.php',
                method: mode,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: _ => {
                    modal.style.display = 'none';
                    fetchClients();
                },
                error: error => {
                    console.error('Error adding client:', error);
                },
            });
        }
    });
});
