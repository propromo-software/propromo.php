import defaultTheme from 'tailwindcss/defaultTheme';
import typography from '@tailwindcss/typography';

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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                koulen: ['"Koulen"', 'normal'],
                sourceSansPro: ['"Source Sans 3"', 'normal']
            },
        },
        colors: {
            'white': '#FFFFFF',
            'primary-blue': '#0D3269',
            'secondary-grey': '#9A9A9A',
            'other-grey': '#DCDCDC',
            'border-color': 'rgba(0, 0, 0, 0.2)',
            'additional-green':'#229342',
            'additional-orange':'#FBC116',
            'additional-red':'#E33B2E'
        },

    },

    plugins: [typography],
};
