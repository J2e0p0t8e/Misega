import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#BC002D',
                    hover: '#9E0025',
                    light: '#E60037',
                    glow: 'rgba(188, 0, 45, 0.4)',
                },
                misega: {
                    red: '#BC002D',
                    black: '#0A0A0C',
                    card: '#121216',
                    cardHover: '#181820',
                    border: '#262630',
                    textMuted: '#9CA3AF',
                }
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', '"Google Sans"', 'Lato', ...defaultTheme.fontFamily.sans],
                gang: ['"GangOfThree"', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
