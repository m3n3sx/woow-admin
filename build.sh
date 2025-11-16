#!/bin/bash

# WOOW! Admin Build Script
# Builds JavaScript and CSS using Vite

echo "=== WOOW! Admin Build Script ==="
echo ""

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "❌ node_modules not found. Running npm install..."
    npm install
    if [ $? -ne 0 ]; then
        echo "❌ npm install failed"
        exit 1
    fi
fi

# Run Vite build
echo "🔨 Building with Vite..."
npx vite build

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Build completed successfully!"
    echo ""
    echo "Generated files:"
    ls -lh assets/dist/
    echo ""
    echo "Next steps:"
    echo "1. Clear WordPress cache"
    echo "2. Hard refresh browser (Ctrl+Shift+R)"
    echo "3. Test palette and template application"
else
    echo ""
    echo "❌ Build failed"
    exit 1
fi
