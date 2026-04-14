import colors from 'tailwindcss/colors'

export default {
    content: [
        './resources/**/*.blade.php', 
        './resources/**/*.js',        // ← tambah ini
        './vendor/filament/**/*.blade.php'
    ],
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },
}