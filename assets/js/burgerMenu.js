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

            const isOpen = mobileMenu.classList.contains('max-h-screen');

            // Toggle max-height classes for smooth transition
            if (isOpen) {
                // Closing: Transition to max-h-0
                mobileMenu.classList.remove('max-h-screen');
                mobileMenu.classList.add('max-h-0');
                menuButton.setAttribute('aria-expanded', 'false');
            } else {
                // Opening: Transition to max-h-screen (a large value to fit content)
                mobileMenu.classList.remove('max-h-0');
                mobileMenu.classList.add('max-h-screen');
                menuButton.setAttribute('aria-expanded', 'true');
            }

            // Toggle the 'hidden' class on the icons (still works instantly, which is fine for icons)
            iconOpen.classList.toggle('hidden');
            iconClosed.classList.toggle('hidden');
        });
    }
});
