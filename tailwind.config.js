/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/views/**/*.js",
        "./resources/js/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                linen: '#faf8f4',
                cream: '#f0e8d8',
                sand: '#d4c4a4',
                'dark-oak': '#3d2f1e',
                'warm-gray': '#8a7560',
                'sage-green': '#5a7a4a',
                terracotta: '#c4785a',
                blush: '#e8c4b4',
            },
            fontFamily: {
                playfair: ['Playfair Display', 'serif'],
                dm: ['DM Sans', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
