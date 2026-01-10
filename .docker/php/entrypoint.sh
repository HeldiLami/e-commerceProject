#!/bin/sh
set -e

# Configure Git to allow /var/www as safe directory (fixes "dubious ownership" warning)
git config --global --add safe.directory /var/www || true

# Set permissions for Laravel directories
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# permissions for PHPMyAdmin
mkdir -p /sessions

chmod 777 /sessions

# Start Vite dev server in the background if node_modules exists (dependencies installed)
if [ -d "/var/www/node_modules" ]; then
    # Ensure logs directory exists
    mkdir -p /var/www/storage/logs
    cd /var/www
    # Start Vite in the background and redirect output to log file
    npm run dev > /var/www/storage/logs/vite.log 2>&1 &
    VITE_PID=$!
    echo "Vite dev server started in the background (PID: $VITE_PID)"
    echo "Vite logs are available at: /var/www/storage/logs/vite.log"
fi

exec "$@"