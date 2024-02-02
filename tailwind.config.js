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
                sourceSansPro: ['"Source Sans 3"']
            },
        },
    },

    plugins: [typography],
};
