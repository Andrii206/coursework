import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            sass: {
                includePaths: ['node_modules'], 
            },
            scss: {
                includePaths: ['node_modules'],
            },
        },
    },
});
