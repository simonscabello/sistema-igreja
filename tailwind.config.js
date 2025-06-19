import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Fira Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: "#3BA99C",
                    light: "#A7D7CD",
                    dark: "#2C7A71",
                },
                secondary: "#7CAFB6",
                neutral: {
                    light: "#F5F9F8",
                    medium: "#C7D4D1",
                    dark: "#415E5A",
                },
                accent: {
                    subtle: "#F6EBD9"
                }
            }
        },
    },

    darkMode: 'class',

    plugins: [forms, require('flowbite/plugin')],
};
