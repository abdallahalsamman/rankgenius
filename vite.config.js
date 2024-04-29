import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import dotenv from 'dotenv';
dotenv.config();

const host = process.env.APP_DOMAIN;

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/editor.js'
            ],
            refresh: false,
        }),
    ],
    server: {
        host: true,
        hmr: { host },
    }
});
