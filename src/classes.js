$(document).ready(() => {
    const classModal = document.getElementById('class-modal');
    const openClassModalBtn = document.getElementById('add-class');
    const closeClassModalBtn = document.getElementById('close-class-modal');
    const classModalSubmitBtn = document.getElementById('submit-class');
    const selectedIconContainer = document.getElementById('selected-icon-container');

    let CLASSES_ARRAY = [];
    let selectedIcon = '';
    let mode = 'POST';

    openClassModalBtn.addEventListener('click', () => {
        clearForm();
        mode = 'POST';
        classModalSubmitBtn.innerText = 'Cadastrar Classe';
        classModal.style.display = 'flex';
    });

    closeClassModalBtn.addEventListener('click', () => {
        classModal.style.display = 'none';
    });

    window.addEventListener('click', event => {
        if (event.target === classModal) {
            classModal.style.display = 'none';
        }
    });

    function setSelectIcon(icon, iconContainer = null) {
        removeSelectionBorderFromPreviousIcon();
        if (!icon) {
            selectedIcon = '';
            selectedIconContainer.innerHTML = '';
            return;
        }

        if (!iconContainer) {
            console.log('icon', icon);
            const nodeList = document.querySelectorAll(`svg[data-lucide=${icon}]`);
            if (nodeList.length > 0) {
                npdeArray = Array.from(nodeList);
                iconContainer = npdeArray.at(-1).parentNode;
            }
            console.log('iconContainer', iconContainer);
        }

        iconContainer.classList.toggle('border');
        iconContainer.classList.toggle('border-red-500');

        selectedIcon = icon;

        const iconElement = document.createElement('i');
        iconElement.setAttribute('data-lucide', icon);
        iconElement.classList.add('m-2');
        selectedIconContainer.innerHTML = '';
        selectedIconContainer.appendChild(iconElement);

        lucide.createIcons();
    }

    function fetchClasses(page = 1, limit = 10) {
        $.ajax({
            url: `class.php?page=${page}&limit=${limit}`,
            method: 'GET',
            success: response => {
                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }

                if (response.length === 0) {
                    const tableBody = $('#class-table-body');
                    tableBody.empty();
                    tableBody.append('<tr><td colspan="6" class="px-4 py-2 text-center">Nenhum cliente cadastrado.</td></tr>');
                    return;
                }

                CLASSES_ARRAY = response;
                const tableBody = $('#class-table-body');
                tableBody.empty();
                CLASSES_ARRAY.forEach(classRow => {
                    const row = `
              <tr class='border-b border-gray-200'>
                <td class='px-4 py-2 text-left'>
                  <i data-lucide="${classRow.icon}"></i>
                </td>
                <td class='px-4 py-2 text-left'>${classRow.name}</td>
                <td class='px-4 py-2 text-left'>Terça e Quinta 18:00</td>
                <td class='px-4 py-2 text-left'>
                  <div class='flex items-center gap-1 text-green-500'>
                      <i data-lucide="users"></i> 
                      <span>15/20 Inscritos</span>
                  </div>
                </td>
                <td class='px-4 py-2 text-left'>Ativo</td>
                <td class='px-4 py-2 text-left'>
                  <div class='flex items-center gap-2'>
                    <div class='flex items-center gap-2'>
                      <button
                        title='Adicionar Horários'
                        onclick='changeSchedules(${classRow.id})'
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer'
                      >
                        <i data-lucide='calendar-plus'></i>
                      </button>
                      <button
                        title='Editar'
                        onclick='editClass(${classRow.id})'
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer'
                      >
                          <i data-lucide='file-pen'></i> 
                      </button>
                      <button
                        title='Excluir'
                        onclick='deleteClass(${classRow.id})'
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input h-10 w-10 hover:cursor-pointer'
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

    function editClass(id) {
        const classRow = CLASSES_ARRAY.find(classRow => classRow.id === id);
        $('#class-id').val(classRow.id);
        $('#name').val(classRow.name);
        $('#description').val(classRow.description);
        setSelectIcon(classRow.icon);

        classModalSubmitBtn.innerText = 'Atualizar Classe';
        mode = 'PUT';
        classModal.style.display = 'flex';
    }

    function deleteClass(id) {
        $.ajax({
            url: `class.php`,
            method: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ id }),
            success: _ => {
                fetchClasses();
            },
            error: error => {
                console.error('Error deleting client:', error);
            },
        });
    }

    window.editClass = editClass;
    window.deleteClass = deleteClass;

    $('#classForm').on('submit', event => {
        event.preventDefault();

        const data = {};

        data.id = $('#class-id').val();
        data.name = $('#name').val();
        data.icon = selectedIcon;
        data.description = $('#description').val();
        data.status = true;

        $.ajax({
            url: 'class.php',
            method: mode,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: _ => {
                classModal.style.display = 'none';
                clearForm();
                fetchClasses();
            },
            error: error => {
                console.error('Error adding client:', error);
            },
        });
    });

    function clearForm() {
        $('#class-id').val('');
        $('#name').val('');
        $('#description').val('');
        $('#status').val('');
        setSelectIcon('');
    }

    function renderIconGrid(iconNames) {
        const iconGrid = document.getElementById('iconGrid');

        if (!iconGrid) {
            console.error('Icon grid element not found');
            return;
        }

        iconNames.forEach(iconName => {
            const iconContainer = document.createElement('div');
            const iconElement = document.createElement('i');
            iconElement.setAttribute('data-lucide', iconName);
            iconElement.classList.add('m-2', 'cursor-pointer');

            iconContainer.addEventListener('click', e => {
                const target = e.target;
                const dataLucide = target.getAttribute('data-lucide') || target.closest('[data-lucide]')?.getAttribute('data-lucide');
                if (dataLucide) {
                    setSelectIcon(dataLucide, iconContainer);
                }
            });

            iconContainer.appendChild(iconElement);
            iconGrid.appendChild(iconContainer);
        });

        lucide.createIcons();
    }

    function removeSelectionBorderFromPreviousIcon() {
        const selectedIconOcorrences = document.querySelectorAll('div.border.border-red-500');
        selectedIconOcorrences.forEach(icon => {
            icon.classList.remove('border');
            icon.classList.remove('border-red-500');
        });
    }

    function getIcons() {
        $.ajax({
            url: 'svg-icons.json',
            method: 'GET',
            success: response => {
                renderIconGrid(JSON.parse(response));
            },
            error: error => {
                console.error('Error fetching icons:', error);
            },
        });
    }

    getIcons();
    fetchClasses();
});
