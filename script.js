window.onload = function () {
    function toggleNavbar() {
        let navbarMenu = document.querySelector('.navbar-menu');
        let headerContainer = document.querySelector('.header-container');
        navbarMenu.classList.toggle('open')
    }

    const toggle = document.querySelector('.menu-toggle');
    toggle.addEventListener('click', function () {
        const menu = document.querySelector('.menu');
        menu.classList.toggle('active');

        toggle.classList.toggle('active');

        const toggleSpans = toggle.querySelectorAll('span');
        toggleSpans.forEach((span) => span.classList.toggle('active'));

    });

    // Close the menu when a menu item is clicked (optional)
    const menuItems = document.querySelectorAll('.menu a');
    menuItems.forEach((menuItem) => {
        menuItem.addEventListener('click', function () {
            const menu = document.querySelector('.menu');
            menu.classList.remove('active');

            toggle.classList.remove('active');

            const toggleSpans = toggle.querySelectorAll('span');
            toggleSpans.forEach((span) => span.classList.remove('active'));

            const menuItems = document.querySelectorAll('.menu li');
            menuItems.forEach((menuItem) => {
                menuItem.style.animation = '';
            });
        });
    });
}




