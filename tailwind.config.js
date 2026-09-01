import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,ts,tsx}',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#2F8A7E',
                    light: '#7ECFC4',
                    dark: '#1F5F57',
                    fg: '#F4FFFC',
                },
                secondary: '#5E8F94',
                canvas: {
                    DEFAULT: '#F3F6F5',
                    dark: '#0F1C1B',
                },
                surface: {
                    DEFAULT: '#FFFFFF',
                    dark: '#162624',
                },
                ink: {
                    DEFAULT: '#1C3330',
                    muted: '#4D6561',
                    inverse: '#E8F2F0',
                },
                line: {
                    DEFAULT: '#D5E0DD',
                    dark: '#2A3F3C',
                },
                neutral: {
                    light: '#F3F6F5',
                    medium: '#C9D6D3',
                    dark: '#1C3330',
                },
                accent: {
                    DEFAULT: '#B8955A',
                    subtle: '#E8D7B8',
                },
                entrada: {
                    DEFAULT: '#1F7A4D',
                    soft: '#E6F5EC',
                },
                saida: {
                    DEFAULT: '#B42318',
                    soft: '#FCEBEA',
                },
            },
            boxShadow: {
                surface: '0 1px 2px rgba(28, 51, 48, 0.06), 0 8px 24px rgba(28, 51, 48, 0.04)',
                float: '0 10px 40px rgba(15, 28, 27, 0.18)',
            },
            borderRadius: {
                DEFAULT: '0.5rem',
            },
            minHeight: {
                touch: '44px',
            },
            transitionDuration: {
                DEFAULT: '180ms',
            },
        },
    },

    darkMode: 'class',

    plugins: [forms, require('flowbite/plugin')],
};
