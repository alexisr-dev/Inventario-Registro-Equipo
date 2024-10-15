import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    theme: {
        extend: {
            colors: {
                backgroundGreen: '#00ff00', // Define tu color aquí
            },
        },
    },
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/css/**/*.css', // Asegúrate de incluir tus archivos CSS aquí
    ],
}
