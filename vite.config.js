// vite.config.js

import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd());

    return {
        plugins: [
            laravel({
                input: ['resources/sass/app.scss', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
        server: {
            host: '0.0.0.0', // aby vo vnútri Dockeru/WSL počúval na všetkých rozhraniach
            port: 5173,
            hmr: {
                host: 'localhost',
                protocol: 'http',
                port: 5173
            },
        },
    };
});
