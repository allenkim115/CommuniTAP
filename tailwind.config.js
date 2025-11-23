import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Explicitly set dark mode strategy

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'brand-orange': '#F3A261',
                'brand-teal': '#2B9D8D',
                'brand-peach': '#FED2B3',
                'brand-orange-dark': '#E8944F',
                'brand-teal-dark': '#248A7C',
                'brand-peach-dark': '#E8C19F',
            },
        },
    },

    plugins: [forms],
};
