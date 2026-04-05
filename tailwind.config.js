import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Geist', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#10B981',
                'primary-dark': '#059669',
                'primary-light': '#34D399',
            },
            letterSpacing: {
                wide: '0.025em',
            },
        },
    },

    plugins: [forms],
};
