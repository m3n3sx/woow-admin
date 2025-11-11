# WOOW! Admin

**Version:** 2.0.0-beta

Transform your WordPress admin panel with modern glassmorphism design, 10 color palettes, 11 templates, and real-time customization.

## Features

- 🎨 **10 Pre-configured Color Palettes** - Professional Blue, Energetic Green, Creative Purple, and more
- 🖼️ **11 Design Templates** - From Modern Minimal to Glassmorphism Pro
- ⚡ **Real-time Live Preview** - See changes instantly with <300ms debounced updates
- 🌫️ **Glassmorphism Effects** - Modern backdrop-filter blur with semi-transparent backgrounds
- 📱 **Mobile Optimized** - Responsive design with touch-friendly controls
- ♿ **WCAG AA Compliant** - Accessible design with proper contrast ratios
- 🚀 **Performance Optimized** - CSS generation <100ms, cache hit rate >80%
- 💾 **Import/Export** - Backup and transfer settings between sites
- 🔄 **Auto Backup** - Automatic backup system (max 10 backups)
- ⌨️ **Keyboard Shortcuts** - Ctrl+Shift+1-0 for palettes, Ctrl+S to save
- 🌓 **Auto Palette Switching** - Automatic day/night mode switching
- 🔒 **Secure** - Nonce verification, capability checks, input sanitization

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- Modern browser with backdrop-filter support (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)

## Installation

### From Source

1. Clone the repository:
```bash
git clone https://github.com/m3n3sx/woow-admin.git
cd woow-admin
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Build assets:
```bash
npm run build
```

4. Copy the plugin directory to your WordPress plugins folder:
```bash
cp -r woow-admin /path/to/wordpress/wp-content/plugins/
```

5. Activate the plugin in WordPress admin panel

## Development

### Setup Development Environment

```bash
# Install dependencies
composer install
npm install

# Start development server
npm run dev
```

### Available Commands

```bash
# Development
npm run dev          # Start Vite dev server
npm run build        # Production build
npm run preview      # Preview production build

# Testing
composer test        # Run PHPUnit tests
npm test            # Run Vitest tests
npm run test:e2e    # Run Cypress E2E tests

# Code Quality
composer phpcs      # Check PHP coding standards
composer phpcbf     # Fix PHP code style
composer analyse    # Run PHPStan static analysis
npm run lint        # Run ESLint
npm run lint:fix    # Fix ESLint issues
```

## Project Structure

```
woow-admin/
├── woow-admin.php              # Main plugin file
├── composer.json               # PHP dependencies
├── package.json                # Node dependencies
├── vite.config.js             # Vite configuration
│
├── includes/                   # PHP classes
│   ├── class-woow-*.php       # Core classes
│   └── templates/             # HTML templates
│       └── tabs/              # 13 configuration tabs
│
├── assets/
│   ├── src/                   # Source files
│   │   ├── js/                # JavaScript modules
│   │   └── css/               # CSS files
│   └── dist/                  # Built files (generated)
│
├── languages/                 # Translation files
├── docs/                      # Documentation
└── tests/                     # Test files
    ├── php/                   # PHPUnit tests
    ├── js/                    # Vitest tests
    └── e2e/                   # Cypress tests
```

## Usage

1. Go to **WOOW! Admin** in WordPress admin menu
2. Choose from 10 color palettes or 11 design templates
3. Customize 13 configuration tabs:
   - General settings
   - Color palettes
   - Design templates
   - Admin bar styling
   - Menu styling
   - Dashboard widgets
   - Form controls
   - Universal buttons
   - Backgrounds
   - Typography
   - Visual effects
   - Login page
   - Advanced settings
4. See changes in real-time preview
5. Save your configuration

## Keyboard Shortcuts

- `Ctrl+Shift+1-0` - Apply palette 1-10
- `Ctrl+S` - Save settings
- `Ctrl+E` - Export settings
- `Ctrl+I` - Import dialog
- `Ctrl+Z` - Restore last backup
- `Ctrl+P` - Toggle live preview

## Contributing

Contributions are welcome! Please read our contributing guidelines before submitting pull requests.

## License

GPL v2 or later - see LICENSE file for details

## Credits

- Design inspired by modern glassmorphism trends
- Built with WordPress best practices
- Powered by Vite for fast development

## Support

- GitHub Issues: https://github.com/m3n3sx/woow-admin/issues
- Documentation: https://github.com/m3n3sx/woow-admin/wiki

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.
