import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';

export default defineConfig(({ command }) => ({
    plugins: [
        ...(command === 'build'
            ? [
                laravel({
                    input: [
                        'resources/css/app.css',
                        'resources/js/app.js',
                    ],
                    refresh: true,
                }),
            ]
            : []),
        svelte(),
    ],

    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,

        proxy: {
            '/api': {
                target: 'http://localhost:15000',
                changeOrigin: true,
            },
        },
    },
}));
