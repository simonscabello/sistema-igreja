import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/financial-dashboard.css',
                'resources/js/app.js',
                'resources/js/financial-dashboard.js'
            ],
            refresh: true,
        }),
    ],
});
