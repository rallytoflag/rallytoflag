const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            screens: {
              xs: '475px',
              ...defaultTheme.screens,
            },
            fontFamily: {
              'logo': ['UnifrakturMaguntia', 'cursive'],
              'main': ['Open+Sans'],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
