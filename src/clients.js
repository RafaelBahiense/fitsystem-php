$(document).ready(() => {
    const modal = document.getElementById('client-modal');
    const openModalBtn = document.getElementById('add-client');
    const closeModalBtn = document.getElementById('close-client-modal');

    openModalBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});