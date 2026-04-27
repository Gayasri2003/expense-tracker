import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                outfit: ['Outfit', 'sans-serif'],
            },
            colors: {
                navy: {
                    50: '#f2f5f9',
                    100: '#e1e8f0',
                    200: '#c8d4e4',
                    300: '#a1b8d2',
                    400: '#7295bc',
                    500: '#5277a5',
                    600: '#405f8b',
                    700: '#354d71',
                    800: '#2f425f',
                    900: '#2b3950',
                    950: '#0f172a', // Navy Black
                },
                gold: {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#eab308',
                    600: '#ca8a04',
                    700: '#a16207',
                    800: '#854d0e',
                    900: '#713f12',
                    950: '#422006',
                },
                primary: {
                    DEFAULT: '#0f172a', // Navy Black
                    light: '#1e293b',
                },
                accent: {
                    DEFAULT: '#ca8a04', // Dark Yellow
                    light: '#facc15',
                }
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.5rem',
            }
        },
    },

    plugins: [forms, typography],
};
