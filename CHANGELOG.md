# Changelog

All notable changes to WOOW! Admin will be documented in this file.

## [2.0.0-beta] - 2025-11-11

### Added
- ✨ **Admin Bar Width Control** - New slider with % and px unit selection
- ✨ **Spacing Controls** - Padding with all/individual modes
- ✨ **Margin Controls** - Offset from edges with all/individual modes
- ✨ **Conditional Fields** - Dynamic form visibility based on selections
- ✨ **Glassmorphism Effect** - Transparency and blur with rgba conversion
- ✨ **Text & Icon Colors** - Comprehensive color controls for admin bar
- ✨ **Shadow Styles** - Multiple shadow presets (none, sm, md, lg, xl, 2xl)
- ✨ **Hover Styles** - Normal and compact hover modes
- ✨ **Submenu Styling** - Inherit or custom submenu colors
- ✨ Default values system for all settings (`includes/defaults.php`)
- ✨ Improved validation with proper type conversion
- ✨ Enhanced color input handling with fallback values
- ✨ Better opacity conversion (0-100 → 0-1)
- ✨ Unitless line-height support
- ✨ Keyword validation for CSS properties (cover, contain, auto)
- ✨ Smart centering for admin bar when width < 100%

### Fixed
- 🐛 **Margin/Spacing validation** - Fixed unitless field validation
- 🐛 **Validator sync** - Fixed case-sensitive file naming (validator.js ↔ Validator.js)
- 🐛 **Live preview** - Restored functionality after validation updates
- 🐛 **Save settings** - Fixed AJAX save with proper validation
- 🐛 Color inputs now have proper default values (no more empty #rrggbb errors)
- 🐛 Validation no longer requires units for line-height
- 🐛 Image size accepts keywords without validation errors
- 🐛 Opacity slider properly converts values on save
- 🐛 Size inputs automatically add units (px) when needed
- 🐛 Double units bug (24pxpx) fixed with data-type="unitless"

### Improved
- ⚡ Better error handling and user feedback
- ⚡ Improved validator with type-specific rules
- ⚡ Enhanced settings merge with defaults
- ⚡ Cleaner codebase with removed temporary files

### Changed
- 🔄 Version bumped to 2.0.0-beta
- 🔄 Cleaned up documentation and test files
- 🔄 Removed backup and temporary files

## [1.0.0] - 2025-11-08

### Initial Release
- 🎨 10 pre-configured color palettes
- 🖼️ 11 design templates
- ⚡ Real-time live preview
- 🌫️ Glassmorphism effects
- 📱 Mobile optimized
- ♿ WCAG AA compliant
- 🚀 Performance optimized
- 💾 Import/Export functionality
- 🔄 Auto backup system
- ⌨️ Keyboard shortcuts
- 🌓 Auto palette switching
- 🔒 Security features

---

**Legend:**
- ✨ New feature
- 🐛 Bug fix
- ⚡ Improvement
- 🔄 Change
- 🗑️ Removal
- 🔒 Security
