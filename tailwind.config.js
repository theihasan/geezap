import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                'ubuntu': ['Ubuntu', 'sans-serif'],
                'oxanium': ['Oxanium', 'cursive'],
            },
            colors: {
                primary: '#0A0A1B',
                secondary: '#12122b',
            }
        },
    },
    plugins: [forms, typography],
};
