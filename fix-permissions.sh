# #!/bin/bash
# # Script to fix file permissions in Laravel Docker project
# # This fixes files owned by root that should be owned by the current user

# echo "Fixing file permissions..."

# # Get current user ID and group ID
# USER_ID=$(id -u)
# GROUP_ID=$(id -g)

# # Find and fix all root-owned files (excluding vendor and node_modules)
# echo "Finding root-owned files..."
# ROOT_FILES=$(find . -not -path "*/vendor/*" -not -path "*/node_modules/*" -not -path "*/.git/*" -user root 2>/dev/null)

# if [ -z "$ROOT_FILES" ]; then
#     echo "No root-owned files found. Permissions are correct!"
#     exit 0
# fi

# echo "Found root-owned files. Fixing ownership..."
# echo "$ROOT_FILES" | while read -r file; do
#     if [ -n "$file" ]; then
#         sudo chown $USER_ID:$GROUP_ID "$file" 2>/dev/null && echo "Fixed: $file" || echo "Failed to fix: $file"
#     fi
# done

# # Also fix directories
# ROOT_DIRS=$(find . -not -path "*/vendor/*" -not -path "*/node_modules/*" -not -path "*/.git/*" -user root -type d 2>/dev/null)
# if [ -n "$ROOT_DIRS" ]; then
#     echo "Fixing root-owned directories..."
#     echo "$ROOT_DIRS" | while read -r dir; do
#         if [ -n "$dir" ]; then
#             sudo chown $USER_ID:$GROUP_ID "$dir" 2>/dev/null && echo "Fixed directory: $dir" || echo "Failed to fix directory: $dir"
#         fi
#     done
# fi

# echo "Done! File permissions have been fixed."

