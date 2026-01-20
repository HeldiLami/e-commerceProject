import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/general.css',
                'resources/css/amazon-header.css',
                'resources/css/pages/amazon.css',
                'resources/css/pages/auth/verify-email.css',
                'resources/css/product.css',
                'resources/css/pages/checkout/checkout-header.css',
                'resources/css/pages/checkout/checkout.css',
                'resources/css/pages/tracking.css',
                'resources/css/admin/sidebar.css',
                'resources/css/admin/statistics.css',
                'resources/css/admin/users.css',
                'resources/css/admin/products-create.css',
                'resources/css/admin/users-edit.css',
                'resources/css/user-page.css',
                'resources/js/app.js',
                'resources/js/amazon.js',
                'resources/js/cart.js',
                'resources/js/orders.js',
                'resources/js/admin/menuBtn.js',
                'resources/js/admin/products-create.js',
                'resources/js/admin/users-edit.js',
                'resources/js/session-timeout.js',
                'resources/js/product-page.js',
                'resources/js/utils/updateCartDisplay.js',
                'resources/css/pages/cart.css',
                'resources/css/pages/orders.css',
                'resources/css/pages/edit-profile.css',
                'resources/js/amazon-header.js',
                'resources/js/admin/registerForm.js',
                'resources/js/edit-profile.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: "0.0.0.0",
        port: 5174,
        strictPort: true,
        hmr: {
            host: "localhost",
            port: 5174,
        },
        watch: {
            usePolling: true,
        },
    },
});
