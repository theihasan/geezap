import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        cssCodeSplit: true,
        cssMinify: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'axios', 'laravel-echo', 'pusher-js'],
                },
                entryFileNames: 'assets/[name].[hash].js',
                chunkFileNames: 'assets/[name].[hash].js',
                assetFileNames: 'assets/[name].[hash].[ext]',
            },
        },
        sourcemap: false,
        chunkSizeWarningLimit: 1000,
    },
});
