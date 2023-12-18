import { getIconCollections, iconsPlugin } from '@egoist/tailwindcss-icons';
import forms from '@tailwindcss/forms';
import defaultTheme from 'tailwindcss/defaultTheme';

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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        iconsPlugin({
            // Select the icon collections you want to use
            // You can also ignore this option to automatically discover all icon collections you have installed
            collections: getIconCollections(["mdi",]),
          }),
    ],
};
