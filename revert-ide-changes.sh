#!/usr/bin/env bash

echo "Reverting IDE configuration changes..."

# Restore original sail script
if [ -f "vendor/bin/sail.backup" ]; then
    mv vendor/bin/sail.backup vendor/bin/sail
    chmod +x vendor/bin/sail
    echo "✓ Restored original sail script"
fi

# Remove sail wrapper
if [ -f "sail-wrapper.sh" ]; then
    rm sail-wrapper.sh
    echo "✓ Removed sail-wrapper.sh"
fi

# Remove VS Code settings
if [ -f ".vscode/settings.json" ]; then
    rm .vscode/settings.json
    rmdir .vscode 2>/dev/null
    echo "✓ Removed .vscode/settings.json"
fi

# Remove IDE helper files
rm -f _ide_helper.php _ide_helper_models.php .phpstorm.meta.php
echo "✓ Removed IDE helper files"

# Restore gitignore
git checkout .gitignore 2>/dev/null || echo "! Could not restore .gitignore (manual restore needed)"

# Restore README
git checkout README.md 2>/dev/null || echo "! Could not restore README.md (manual restore needed)"

# Restore docker.conf
git checkout .docker/php/docker.conf 2>/dev/null || echo "! Could not restore docker.conf (manual restore needed)"

echo ""
echo "Revert complete! Please reload your IDE."
echo "Note: You may need to rebuild the PHP container: docker compose up -d --build php"

