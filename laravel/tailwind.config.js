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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: "#2D9C8F",   // Hijau Tosca
                    dark: "#247A72",      // Versi lebih gelap (hover)
                    light: "#58B8AB",     // Versi lebih terang
                },
                secondary: {
                    DEFAULT: "#F5A623",   // Oranye Cerah
                    dark: "#E59420",      // Hover
                    light: "#FFC65A",     // Lebih terang
                },
                background: {
                    DEFAULT: "#FFFFFF",   // Putih
                    light: "#F5F5F5",     // Abu-abu muda
                },
                text: {
                    DEFAULT: "#333333",   // Abu-abu tua (body text)
                    light: "#666666",     // Abu-abu medium
                    white: "#FFFFFF",     // Untuk teks di atas background gelap
                },
            },
        },
    },

    plugins: [forms],
};
