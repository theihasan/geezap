import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.toggleMobileMenu = () => {
    const menu = document.getElementById('mobile-menu');
    const icon = document.querySelector('#menu-toggle i');

    menu.classList.toggle('hidden');
    icon.classList.toggle('la-times');
    icon.classList.toggle('la-bars');
};
