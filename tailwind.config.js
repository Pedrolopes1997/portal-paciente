import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta "Health Tech"
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    500: '#0ea5e9', // Azul Sky principal
                    600: '#0284c7',
                    700: '#0369a1',
                    900: '#0c4a6e',
                },
                secondary: {
                    500: '#14b8a6', // Verde Teal (Saúde/Sucesso)
                }
            }
        },
    },
    plugins: [],
};