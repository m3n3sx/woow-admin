#!/bin/bash

# WOOW! Admin Build Script
# Builds CSS and JS files using Vite

echo "🚀 Building WOOW! Admin assets..."

# Change to plugin directory
cd "$(dirname "$0")"

# Run Vite build
if command -v node &> /dev/null; then
    echo "✓ Node found, running build..."
    node node_modules/vite/bin/vite.js build
    
    if [ $? -eq 0 ]; then
        echo "✅ Build successful!"
        echo ""
        echo "Generated files:"
        ls -lh assets/dist/css/
        ls -lh assets/dist/js/
    else
        echo "❌ Build failed!"
        exit 1
    fi
else
    echo "❌ Node.js not found!"
    echo "Please install Node.js first."
    exit 1
fi
