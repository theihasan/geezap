import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.toggleMobileMenu = () => {
    const menu = document.getElementById('mobile-menu');
    const backdrop = document.getElementById('menu-backdrop');
    const menuItems = document.querySelectorAll('.mobile-menu-item');
    const icon = document.querySelector('#menu-toggle i');

    const isOpen = menu.classList.contains('translate-x-0');

    if (!isOpen) {
        backdrop.classList.remove('pointer-events-none', 'opacity-0');
        menu.classList.remove('translate-x-full', 'opacity-0');
        menu.classList.add('translate-x-0', 'opacity-100');

        menuItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.remove('translate-x-4', 'opacity-0');
                item.classList.add('translate-x-0', 'opacity-100');
            }, 100 * (index + 1));
        });
    } else {
        backdrop.classList.add('pointer-events-none', 'opacity-0');
        menu.classList.remove('translate-x-0', 'opacity-100');
        menu.classList.add('translate-x-full', 'opacity-0');

        menuItems.forEach(item => {
            item.classList.remove('translate-x-0', 'opacity-100');
            item.classList.add('translate-x-4', 'opacity-0');
        });
    }

    icon.classList.toggle('la-times');
    icon.classList.toggle('la-bars');
};

document.addEventListener('click', (e) => {
    const menu = document.getElementById('mobile-menu');
    const menuButton = document.getElementById('menu-toggle');

    if (!menu.contains(e.target) && !menuButton.contains(e.target) &&
        !menu.classList.contains('translate-x-full')) {
        toggleMobileMenu();
    }
});
