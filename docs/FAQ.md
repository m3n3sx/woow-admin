# WOOW! Admin - Frequently Asked Questions (FAQ)

Common questions and answers about WOOW! Admin palettes and templates.

---

## 📋 Table of Contents

- [General Questions](#general-questions)
- [Palettes](#palettes)
- [Templates](#templates)
- [Backup & Restore](#backup--restore)
- [Customization](#customization)
- [Troubleshooting](#troubleshooting)
- [Performance](#performance)
- [Compatibility](#compatibility)

---

## General Questions

### What is WOOW! Admin?

WOOW! Admin is a WordPress plugin that transforms your admin panel with modern glassmorphism design, 10 color palettes, 11 design templates, and extensive customization options.

### Do I need coding knowledge to use WOOW! Admin?

No! WOOW! Admin is designed for users of all skill levels. You can apply palettes and templates with a single click, or customize settings using visual controls.

### Will WOOW! Admin affect my website's front-end?

No. WOOW! Admin only styles the WordPress admin panel. Your website's front-end appearance remains unchanged.

### Is WOOW! Admin compatible with my theme?

Yes! WOOW! Admin works with any WordPress theme because it only affects the admin panel, not your site's front-end.

### Can I use WOOW! Admin on multiple sites?

Yes! You can install WOOW! Admin on as many WordPress sites as you want. You can also export settings from one site and import them to another.

---

## Palettes

### What is a color palette?

A color palette is a pre-configured set of colors that instantly updates all color settings across your admin panel. WOOW! Admin includes 10 professionally designed palettes.

### How many palettes are included?

WOOW! Admin includes 10 color palettes:
1. Professional Blue
2. Warm Sunset
3. Dark Mode Pro
4. Nature Green
5. Minimalist Gray
6. Vibrant Purple
7. Ocean Blue
8. Cherry Red
9. Monochrome Elite
10. Cyberpunk Neon

### Can I create my own palette?

While you can't create a named palette through the UI, you can:
1. Apply an existing palette as a starting point
2. Customize all colors to your liking
3. Export your configuration
4. Import it on other sites

### What happens to my current colors when I apply a palette?

When you apply a palette:
1. A backup of your current settings is automatically created
2. All color settings are updated to match the palette
3. You can restore your previous colors anytime from the backup

### Can I customize colors after applying a palette?

Yes! After applying a palette, you can customize any color in any section. Your customizations will override the palette defaults.

### How do I switch between palettes?

1. Go to the **Palettes** tab
2. Click **Apply** on any palette
3. Or use keyboard shortcuts: `Ctrl+Shift+1` through `Ctrl+Shift+0`

### Do palettes change typography or spacing?

No. Palettes only change colors. If you want to change typography, spacing, effects, and more, use a design template instead.

### Which palette is best for accessibility?

For maximum accessibility, use:
- **High Contrast** template (not a palette, but best for accessibility)
- **Minimalist Gray** palette (simple, high contrast)
- **Professional Blue** palette (WCAG AA compliant)

### Can I preview a palette before applying it?

Yes! Hover over a palette card and click the preview icon (eye) to see a full-screen preview before applying.

### How do I undo a palette application?

Press `Ctrl+Z` or go to **Advanced** → **Backup & Restore** → Click **Restore** on the backup created before applying the palette.

---

## Templates

### What is a design template?

A design template is a complete design configuration that sets ALL styling options (100+ settings) including colors, typography, spacing, effects, borders, shadows, and more.

### How many templates are included?

WOOW! Admin includes 11 design templates:
1. Modern Minimal
2. Glassmorphism Pro
3. Dark Dashboard
4. Colorful Creative
5. Corporate Blue
6. Material Design
7. Flat 2.0
8. Neumorphism
9. Retro Wave
10. Nature Inspired
11. High Contrast

### What's the difference between a palette and a template?

| Feature | Palette | Template |
|---------|---------|----------|
| Changes | Colors only (~50 settings) | Everything (100+ settings) |
| Includes | Color values | Colors, fonts, spacing, effects, etc. |
| Best for | Quick color change | Complete redesign |
| Customization | Easy to customize after | More comprehensive |

### Can I mix features from different templates?

Yes! You can:
1. Apply a template as a base
2. Go to individual tabs (Admin Bar, Typography, etc.)
3. Adjust settings to match features from other templates
4. Save your custom configuration

### Which template is best for beginners?

Start with:
- **Modern Minimal** - Simple and clean
- **Corporate Blue** - Professional and familiar
- **Material Design** - Well-known design system

### Which template is most unique?

The most unique templates are:
- **Glassmorphism Pro** - Premium frosted glass effects
- **Neumorphism** - Soft, tactile UI
- **Retro Wave** - 1980s synthwave aesthetic
- **Cyberpunk Neon** - Futuristic neon design

### Can I apply a template and then a palette?

Yes, but be aware:
1. Applying a template sets all 100+ settings
2. Applying a palette afterward will only change colors
3. Other template settings (typography, spacing, effects) remain

**Recommended order:** Apply template first, then customize colors if needed.

### Do templates work on mobile devices?

Yes! All templates are fully responsive and work on desktop, tablet, and mobile devices.

### How do I preview a template before applying?

Click on any template card to open a preview modal showing a full-size screenshot of the template applied.

### Can I create my own template?

While you can't create a named template through the UI, you can:
1. Apply an existing template as a starting point
2. Customize all settings across all tabs
3. Export your configuration
4. Import it on other sites

---

## Backup & Restore

### Are backups created automatically?

Yes! Backups are automatically created:
- Before applying any palette
- Before applying any template
- Before importing settings
- When you manually create one

### How many backups are stored?

WOOW! Admin stores up to 10 backups. When the limit is reached, the oldest backup is automatically deleted (except the most recent one, which is protected).

### Where are backups stored?

Backups are stored in your WordPress database in a secure format.

### Can I download backups?

Yes! Use the **Export Settings** feature in the **Advanced** tab to download your current configuration as a JSON file.

### How do I restore a backup?

**Quick method:** Press `Ctrl+Z` to restore the most recent backup.

**Manual method:**
1. Go to **Advanced** → **Backup & Restore**
2. Find the backup you want to restore
3. Click **Restore**
4. Confirm the restoration

### What happens when I restore a backup?

When you restore a backup:
1. A new backup of your current settings is created first
2. All settings are reverted to the backup state
3. CSS is regenerated
4. You see a confirmation message

### Can I delete backups?

Yes, you can delete any backup except the most recent one (which is protected to ensure you always have a restore point).

### Do backups include custom CSS?

Yes! Backups include ALL settings, including custom CSS from the Advanced tab.

### Can I restore a backup from another site?

Yes! Export settings from one site, then import them on another site. This works across different WordPress installations.

### How long are backups kept?

Backups are kept indefinitely until:
- You manually delete them
- The 10-backup limit is reached (oldest is deleted)
- You uninstall the plugin (all data is removed)

---

## Customization

### Can I customize individual settings after applying a palette/template?

Yes! After applying a palette or template, you can customize any setting in any tab. Your changes will override the palette/template defaults.

### How do I reset a single setting to default?

Most settings have a reset button (↻ icon) next to them. Click it to reset that specific setting to its default value.

### How do I reset all settings to defaults?

Go to **Advanced** → **Reset Settings** → Click **Reset to Defaults** → Confirm.

**Warning:** This will delete all your customizations. Create a backup first!

### Can I add custom CSS?

Yes! Go to **Advanced** → **Custom CSS** → Add your CSS code → Save.

Your custom CSS will be applied after all other styles, allowing you to override any setting.

### Do my customizations survive WordPress updates?

Yes! Your WOOW! Admin settings are stored in the database and are not affected by WordPress core updates.

### Can I customize the login page?

Yes! Go to the **Login Page** tab to customize:
- Background (color, gradient, or image)
- Form styling
- Button colors
- Logo
- Link colors

### How do I change just the admin bar color?

1. Go to **Admin Bar** tab
2. Find "Background Color" setting
3. Click the color picker
4. Choose your color
5. Press `Ctrl+S` to save

### Can I use my brand colors?

Yes! You can:
1. Apply a palette as a starting point
2. Go to **Color Overrides** tab
3. Set your brand colors (primary, secondary, accent)
4. These colors will be used throughout the admin panel

### How do I enable glassmorphism effects?

1. Go to **Effects** tab
2. Toggle "Enable Glassmorphism" to ON
3. Adjust blur strength (12-16px recommended)
4. Adjust opacity (0.85-0.95 recommended)
5. Save changes

### Can I change fonts?

Yes! Go to **Typography** tab to change:
- Body font family
- Heading font family
- Font sizes
- Font weights
- Line heights

---

## Troubleshooting

### Changes aren't showing after saving

**Try these solutions:**
1. Hard refresh: `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)
2. Clear browser cache
3. Clear WordPress cache (if using a caching plugin)
4. Go to **Advanced** → Click **Regenerate CSS**

### Palette/template applied but looks wrong

**Possible causes:**
1. **Browser cache** - Hard refresh with `Ctrl+Shift+R`
2. **Theme conflicts** - Your theme may override some styles
3. **Plugin conflicts** - Another plugin may interfere
4. **Custom CSS** - Check if custom CSS is overriding settings

**Solutions:**
1. Clear all caches
2. Temporarily disable other plugins to test
3. Check custom CSS in Advanced tab
4. Restore a backup and reapply

### Live preview not working

**Check these:**
1. Is "Real-time" toggle ON in the control bar?
2. Is your browser supported? (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)
3. Are there JavaScript errors? (Check browser console with F12)
4. Try disabling other plugins temporarily

### Colors look different than preview images

**This is normal because:**
1. Monitor calibration varies
2. Browser rendering differs slightly
3. Your WordPress theme may add styles
4. Custom CSS may override colors

**To match preview exactly:**
1. Ensure no custom CSS is active
2. Use a default WordPress theme for testing
3. Disable other admin styling plugins

### Backup restore isn't working

**Try these:**
1. Check if the backup file is corrupted (try a different backup)
2. Clear all caches after restoring
3. Hard refresh the page
4. Check browser console for errors
5. Try exporting settings and importing them

### Settings not saving

**Possible causes:**
1. **Permissions** - Ensure you have admin capabilities
2. **Server limits** - Check PHP memory limit and max_input_vars
3. **Validation errors** - Check browser console for error messages
4. **Nonce expired** - Refresh the page and try again

**Solutions:**
1. Refresh the page and try again
2. Contact your hosting provider to increase limits
3. Check browser console (F12) for specific errors

### Plugin conflicts

**Common conflicts:**
- Other admin styling plugins
- Page builders that modify admin
- Security plugins with strict settings

**To identify conflicts:**
1. Disable all other plugins
2. Test if WOOW! Admin works
3. Re-enable plugins one by one
4. Identify which plugin causes the conflict

### Performance issues after applying template

**Solutions:**
1. Disable heavy effects (glassmorphism, animations)
2. Use a simpler template (Modern Minimal, Flat 2.0)
3. Reduce blur strength in Effects tab
4. Disable animations in Effects tab
5. Check server resources

---

## Performance

### Does WOOW! Admin slow down my admin panel?

WOOW! Admin is highly optimized:
- CSS generation: < 100ms
- Cache hit rate: > 80%
- Minimal JavaScript overhead
- Efficient database queries

Most users notice no performance impact.

### Does WOOW! Admin affect front-end performance?

No! WOOW! Admin only loads in the WordPress admin panel. Your website's front-end performance is completely unaffected.

### Which template is fastest?

The fastest templates are:
1. **Modern Minimal** - No effects, flat design
2. **Flat 2.0** - No gradients or shadows
3. **Minimalist Gray** - Minimal styling

The slowest (but still fast) are:
1. **Glassmorphism Pro** - Heavy blur effects
2. **Retro Wave** - Glow effects
3. **Neumorphism** - Complex shadows

### Can I improve performance?

Yes! To optimize performance:
1. Disable glassmorphism (Effects tab)
2. Disable animations (Effects tab)
3. Use simpler templates
4. Reduce blur strength
5. Clear old backups

### Does WOOW! Admin work on slow servers?

Yes! WOOW! Admin is designed to work on all hosting environments. However, very slow servers may experience slightly longer CSS generation times (still < 500ms).

---

## Compatibility

### What WordPress version is required?

WOOW! Admin requires WordPress 6.0 or higher.

### What PHP version is required?

WOOW! Admin requires PHP 8.0 or higher.

### What browsers are supported?

**Fully supported:**
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

**Partially supported (no glassmorphism):**
- Older browser versions

### Does WOOW! Admin work with multisite?

Yes! WOOW! Admin works with WordPress multisite installations. Each site can have its own configuration.

### Does WOOW! Admin work with page builders?

Yes! WOOW! Admin is compatible with:
- Elementor
- Beaver Builder
- Divi
- WPBakery
- Gutenberg
- And more!

### Does WOOW! Admin work with WooCommerce?

Yes! WOOW! Admin styles the entire WordPress admin panel, including WooCommerce admin pages.

### Does WOOW! Admin work with membership plugins?

Yes! WOOW! Admin is compatible with membership plugins like:
- MemberPress
- Restrict Content Pro
- Paid Memberships Pro
- And more!

### Can I use WOOW! Admin with other admin styling plugins?

It's not recommended. Using multiple admin styling plugins can cause conflicts. Choose one plugin for best results.

### Does WOOW! Admin work with security plugins?

Yes! WOOW! Admin is compatible with security plugins like:
- Wordfence
- Sucuri
- iThemes Security
- All In One WP Security

### Does WOOW! Admin work with caching plugins?

Yes! WOOW! Admin is compatible with caching plugins like:
- WP Rocket
- W3 Total Cache
- WP Super Cache
- LiteSpeed Cache

**Note:** You may need to clear cache after applying palettes/templates.

---

## Advanced Questions

### Can I use WOOW! Admin in a client project?

Yes! WOOW! Admin is GPL-licensed, so you can use it in client projects without restrictions.

### Can I white-label WOOW! Admin?

The free version cannot be white-labeled. Contact us for white-label licensing options.

### Is there an API for developers?

Yes! WOOW! Admin provides REST API endpoints and PHP hooks for developers. See the [API Documentation](API-DOCUMENTATION.md) for details.

### Can I contribute to WOOW! Admin?

Yes! WOOW! Admin is open source. Visit our [GitHub repository](https://github.com/m3n3sx/woow-admin) to contribute.

### How do I report a bug?

Report bugs on our [GitHub Issues](https://github.com/m3n3sx/woow-admin/issues) page with:
- WordPress version
- PHP version
- Browser and version
- Steps to reproduce
- Screenshots (if applicable)

### How do I request a feature?

Request features on our [GitHub Issues](https://github.com/m3n3sx/woow-admin/issues) page with:
- Clear description of the feature
- Use case / why it's needed
- Examples or mockups (if applicable)

### Is there a premium version?

Currently, WOOW! Admin is free and open source. Premium features may be added in the future.

### How can I support the project?

You can support WOOW! Admin by:
- ⭐ Starring the GitHub repository
- 📝 Writing a review
- 🐛 Reporting bugs
- 💡 Suggesting features
- 🔧 Contributing code
- 📢 Sharing with others

---

## Still Have Questions?

### Documentation
- 📖 [User Guide](USER-GUIDE.md) - Complete documentation
- 🚀 [Quick Start](QUICK-START.md) - Get started in 5 minutes
- 🎨 [Visual Guide](VISUAL-GUIDE.md) - Visual examples and diagrams

### Support
- 💬 [WordPress Forums](https://wordpress.org/support/plugin/woow-admin/)
- 🐛 [GitHub Issues](https://github.com/m3n3sx/woow-admin/issues)
- 📧 Email: support@woowadmin.com

### Community
- 🌟 [GitHub](https://github.com/m3n3sx/woow-admin)
- 🐦 Twitter: @woowadmin
- 💼 LinkedIn: WOOW! Admin

---

**Last Updated:** November 2025  
**Version:** 2.0.0

If your question isn't answered here, please contact us through one of the support channels above!
