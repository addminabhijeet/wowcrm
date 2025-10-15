#!/bin/bash

# Path to your Laravel project
PROJECT_PATH="/home/u235777426/public_html"

# Full PHP path from hosting
PHP_PATH="/usr/bin/php"

# Infinite loop to auto-restart the command if it fails
while true; do
    echo "[$(date)] Starting timers:decrement process..."
    
    # Go to Laravel directory
    cd $PROJECT_PATH || exit

    # Run the artisan command
    $PHP_PATH artisan timers:decrement

    echo "[$(date)] Process crashed or stopped. Restarting in 5 seconds..."
    sleep 5
done
