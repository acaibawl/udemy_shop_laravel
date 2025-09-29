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
    server: {
        host: '0.0.0.0',  // ← これが重要（全てのホストからアクセス許可）
        port: 5173,
        watch: {
            usePolling: true, // Docker 環境では必要
        },
    },
});
