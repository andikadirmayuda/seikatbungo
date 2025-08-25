import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/modern-theme.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        // Generate manifest.json in public/build
        manifest: true,
        // Output compiled files to public/build
        outDir: 'public/build',
        rollupOptions: {
            output: {
                // Ensure unique chunk names
                manualChunks: undefined
            }
        }
    }
});
