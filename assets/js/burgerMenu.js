document.addEventListener('DOMContentLoaded', function() {

    console.log('burgerMenu loaded');

    // 1. Get the main elements by their IDs
    const menuButton = document.getElementById('menu-toggle-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');   // X icon
    const iconClosed = document.getElementById('icon-closed'); // Hamburger icon

    // 2. Add a click event listener to the button
    if (menuButton && mobileMenu && iconClosed && iconOpen) {
        menuButton.addEventListener('click', function() {

            // Toggle the 'hidden' Tailwind class on the mobile menu
            mobileMenu.classList.toggle('hidden');

            // Toggle the 'hidden' class on the icons to switch them
            iconOpen.classList.toggle('hidden');
            iconClosed.classList.toggle('hidden');

            // Update ARIA attribute for accessibility
            const isExpanded = mobileMenu.classList.contains('hidden') ? 'false' : 'true';
            menuButton.setAttribute('aria-expanded', isExpanded);
        });
    }
});
