import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Entrada adicional para el módulo Vue (Fase 6)
                'resources/js/vue-app/main-vue.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
        // Plugin Vue - igual que el proyecto semana 12
        vue(),
    ],
    resolve: {
        alias: {
            // Igual que el proyecto semana 12
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    server: {
        watch: {
            // Configuración original del proyecto
            ignored: ['**/storage/framework/views/**'],
        },
    },
});