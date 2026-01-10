#!/usr/bin/env bash

# Wrapper script to redirect Laravel Sail commands to Docker Compose
# This allows IDE extensions expecting Sail to work with custom Docker Compose setup

# Get the directory where this script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Change to the project directory
cd "$SCRIPT_DIR"

# Map Sail commands to docker compose equivalents
case "$1" in
    "up"|"start")
        docker compose up -d "${@:2}"
        ;;
    "down"|"stop")
        docker compose down "${@:2}"
        ;;
    "restart")
        docker compose restart "${@:2}"
        ;;
    "ps")
        docker compose ps "${@:2}"
        ;;
    "logs")
        docker compose logs "${@:2}"
        ;;
    "artisan"|"art")
        docker compose exec -T php php artisan "${@:2}"
        ;;
    "php")
        docker compose exec -T php php "${@:2}"
        ;;
    "composer")
        docker compose exec -T php composer "${@:2}"
        ;;
    "npm")
        docker compose exec -T php npm "${@:2}"
        ;;
    "yarn")
        docker compose exec -T php yarn "${@:2}"
        ;;
    "test")
        docker compose exec -T php php artisan test "${@:2}"
        ;;
    "shell"|"bash")
        docker compose exec php bash "${@:2}"
        ;;
    *)
        # Default: execute command in php container
        docker compose exec -T php "$@"
        ;;
esac

