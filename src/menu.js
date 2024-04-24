$(document).ready(() => {
    const menu = document.getElementById('menu-toggle');
    const menuButton = document.getElementById('menu-button');
    const menuItemLogout = document.getElementById('menu-item-logout');


    menuButton.addEventListener('click', () => {
        if (menu.style.display == 'none') {
            menu.style.display = 'flex';
        } else {
            menu.style.display = 'none';
        }
    });

    menuItemLogout.addEventListener('click', () => {
        document.cookie = "user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        window.location.pathname = "/fitsystem/login.html";
    });
});