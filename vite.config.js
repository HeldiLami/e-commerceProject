import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/amazon.js',
                'resources/js/cart.js',
                'resources/js/orders.js',
                'resources/js/admin/menuBtn.js',
                'resources/js/session-timeout.js',
                'resources/js/product-page.js',
                'resources/js/utils/updateCartDisplay.js',
                'resources/css/general.css',
                'resources/css/amazon-header.css',
                'resources/css/pages/amazon.css',
                'resources/css/pages/cart.css',
                'resources/css/pages/orders.css',
                'resources/css/pages/tracking.css',
                'resources/css/pages/checkout/checkout.css',
                'resources/css/pages/checkout/checkout-header.css',
                'resources/css/pages/auth/verify-email.css',
                'resources/css/admin/sidebar.css',
                'resources/css/admin/statistics.css',
                'resources/css/admin/users.css',
                'resources/css/user-page.css',
                'resources/js/amazon-header.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        watch: {
            usePolling: true,
        },
    },
});
