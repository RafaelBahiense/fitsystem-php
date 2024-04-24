$(document).ready(() => {
    const modal = document.getElementById('class-modal');
    const openModalBtn = document.getElementById('add-class');
    const closeModalBtn = document.getElementById('close-class-modal');

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