import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/menu.css',
                'resources/css/encuestas.css',
                'resources/css/anuncio.css',
                'resources/css/incidencia.css',
                'resources/js/app.js',
                'resources/js/tablaComunidades.js',
                'resources/js/listaVecinos.js',
                'resources/js/meet.js',
                'resources/js/Anuncio.js',
                'resources/js/Incidencias.js',
                'resources/css/calendario.css',
                'resources/js/calendario.js',
                'resources/js/calendario_admin.js',
                'resources/js/chatPresi.js',
                'resources/js/chatVecino.js',
                'resources/js/seguros_admin.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
            ],
        }),
    ],
});
