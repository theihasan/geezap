@php
    $uniqueId = uniqid('theme-switcher-');
@endphp

<button 
    id="{{ $uniqueId }}"
    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors"
    onclick="toggleTheme('{{ $uniqueId }}')"
    title="Toggle theme"
>
    <!-- Sun icon for light mode -->
    <svg id="sun-icon-{{ $uniqueId }}" class="w-5 h-5 text-gray-700 dark:text-gray-300 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>
    
    <!-- Moon icon for dark mode -->
    <svg id="moon-icon-{{ $uniqueId }}" class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
    </svg>
</button>

<script>
// Theme switcher functionality
function toggleTheme(switcherId) {
    const html = document.documentElement;
    
    if (html.classList.contains('dark')) {
        // Switch to light mode
        html.classList.remove('dark');
        html.classList.add('light');
        localStorage.setItem('theme', 'light');
        updateAllThemeSwitchers('light');
    } else {
        // Switch to dark mode
        html.classList.add('dark');
        html.classList.remove('light');
        localStorage.setItem('theme', 'dark');
        updateAllThemeSwitchers('dark');
    }
}

// Update all theme switcher instances
function updateAllThemeSwitchers(theme) {
    const switchers = document.querySelectorAll('[id^="theme-switcher-"]');
    switchers.forEach(switcher => {
        const switcherId = switcher.id;
        const sunIcon = document.getElementById('sun-icon-' + switcherId);
        const moonIcon = document.getElementById('moon-icon-' + switcherId);
        
        if (theme === 'dark') {
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        } else {
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        }
    });
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    
    // Check for saved theme preference or default to light mode
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark') {
        html.classList.add('dark');
        html.classList.remove('light');
        updateAllThemeSwitchers('dark');
    } else {
        // Default to light mode
        html.classList.remove('dark');
        html.classList.add('light');
        updateAllThemeSwitchers('light');
    }
});
</script>
