import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  build: {
    // Output directory
    outDir: 'assets/dist',
    
    // Empty output directory before build
    emptyOutDir: true,
    
    // Generate sourcemaps for debugging
    sourcemap: process.env.NODE_ENV === 'development',
    
    // Minify in production (using esbuild instead of terser)
    minify: process.env.NODE_ENV === 'production' ? 'esbuild' : false,
    
    // Rollup options
    rollupOptions: {
      input: {
        // Main JavaScript entry point
        main: resolve(__dirname, 'assets/src/js/main.js'),
        
        // Main CSS entry point
        style: resolve(__dirname, 'assets/src/css/main.css'),
      },
      output: {
        // Output file naming - flat structure for WordPress
        entryFileNames: '[name].js',
        chunkFileNames: '[name]-[hash].js',
        assetFileNames: (assetInfo) => {
          // CSS files - flat structure
          if (assetInfo.name.endsWith('.css')) {
            return '[name][extname]';
          }
          // Images go to images/ directory
          if (/\.(png|jpe?g|gif|svg|webp)$/.test(assetInfo.name)) {
            return 'images/[name][extname]';
          }
          // Fonts go to fonts/ directory
          if (/\.(woff2?|eot|ttf|otf)$/.test(assetInfo.name)) {
            return 'fonts/[name][extname]';
          }
          // Everything else
          return 'assets/[name][extname]';
        },
      },
    },
    
    // Target modern browsers (WordPress 6.0+ requirement)
    target: 'es2020',
    
    // Chunk size warnings
    chunkSizeWarningLimit: 500,
  },
  
  // CSS options
  css: {
    devSourcemap: true,
    postcss: {
      plugins: [],
    },
  },
  
  // Server options for development
  server: {
    port: 3000,
    strictPort: false,
    open: false,
    cors: true,
  },
  
  // Preview server options
  preview: {
    port: 3001,
    strictPort: false,
    open: false,
  },
  
  // Resolve options
  resolve: {
    alias: {
      '@': resolve(__dirname, 'assets/src'),
      '@js': resolve(__dirname, 'assets/src/js'),
      '@css': resolve(__dirname, 'assets/src/css'),
      '@components': resolve(__dirname, 'assets/src/js/components'),
    },
  },
  
  // Define global constants
  define: {
    'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development'),
  },
  
  // Optimize dependencies
  optimizeDeps: {
    include: [],
  },
  
  // Test configuration
  test: {
    environment: 'jsdom',
    globals: true,
    setupFiles: [],
  },
});
