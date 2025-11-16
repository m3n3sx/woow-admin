# Performance Testing - Quick Reference

## 🎯 Performance Targets

| Test | Target | Status |
|------|--------|--------|
| Palette List Loading | < 500ms | ⏱️ |
| Template List Loading | < 500ms | ⏱️ |
| Palette Application | < 2s | ⏱️ |
| Template Application | < 2s | ⏱️ |
| CSS Regeneration | < 1s | ⏱️ |

## 🚀 Quick Commands

### Run All Tests (Console)
```bash
php test-performance.php
```

### Run PHPUnit Tests
```bash
./vendor/bin/phpunit tests/php/PerformanceTest.php
```

### Generate HTML Report
```bash
php generate-performance-report.php
# Open performance-report-*.html
```

## 📊 Test Files

| File | Purpose |
|------|---------|
| `test-performance.php` | Standalone console test |
| `tests/php/PerformanceTest.php` | PHPUnit integration |
| `generate-performance-report.php` | HTML report generator |
| `PERFORMANCE-TESTING-GUIDE.md` | Full documentation |

## 🔍 What Each Test Measures

### 1. Palette List Loading
- Loading `includes/data/palettes.php`
- Parsing 10 palette definitions
- Validation of data structures

### 2. Template List Loading
- Loading `includes/data/templates-data.php`
- Parsing 11 template definitions
- Validation of data structures

### 3. Palette Application
- Merging palette settings
- Database update operations
- Settings persistence

### 4. Template Application
- Merging template settings
- Database update operations
- Settings persistence

### 5. CSS Regeneration
- Generating CSS from settings
- Processing all sections
- String concatenation

## ⚡ Optimization Tips

### If Palette/Template Loading is Slow
```php
// Add caching
$cache_key = 'woow_palettes_v1';
$palettes = wp_cache_get($cache_key);
if ($palettes === false) {
    $palettes = require WOOW_PLUGIN_DIR . 'includes/data/palettes.php';
    wp_cache_set($cache_key, $palettes, '', 3600);
}
```

### If Application is Slow
```php
// Batch database updates
update_option('woow_admin_settings', $all_settings);

// Not multiple calls:
// update_option('woow_admin_bar', $bar);
// update_option('woow_admin_menu', $menu);
```

### If CSS Regeneration is Slow
```php
// Use output buffering
ob_start();
echo $css_content;
$css = ob_get_clean();

// Cache generated CSS
wp_cache_set('woow_generated_css', $css, '', 3600);
```

## 📈 Interpreting Results

### ✅ PASSED
```
Average: 43.52 ms
Target:  < 500 ms
Status:  ✅ PASSED
```
**Action:** None needed

### ❌ FAILED
```
Average: 1250.00 ms
Target:  < 500 ms
Status:  ❌ FAILED
⚠️  Exceeded target by 750.00 ms (150.0%)
```
**Action:** Follow optimization recommendations

## 🔧 Troubleshooting

### Test Fails Intermittently
- Run more iterations
- Clear all caches
- Test on dedicated environment

### Memory Limit Errors
- Increase PHP `memory_limit`
- Optimize data structures
- Implement lazy loading

### Timeout Errors
- Increase `max_execution_time`
- Optimize slow operations
- Profile code

## 📝 CI/CD Integration

### GitHub Actions
```yaml
- name: Performance Tests
  run: ./vendor/bin/phpunit tests/php/PerformanceTest.php
```

### GitLab CI
```yaml
performance:
  script:
    - ./vendor/bin/phpunit tests/php/PerformanceTest.php
```

## 🎨 HTML Report Features

- 📊 Interactive charts (Chart.js)
- 🎯 Color-coded pass/fail
- 📈 Performance trends
- 💾 System information
- 📥 Exportable results

## 🔗 Related Files

- `includes/class-woow-palette-manager.php` - Palette management
- `includes/class-woow-template-manager.php` - Template management
- `includes/class-woow-css-generator.php` - CSS generation
- `includes/data/palettes.php` - Palette data
- `includes/data/templates-data.php` - Template data

## 📚 Full Documentation

See `PERFORMANCE-TESTING-GUIDE.md` for:
- Detailed methodology
- Optimization strategies
- Profiling tools
- Best practices
- Troubleshooting guide

## ✅ Pre-Release Checklist

- [ ] All performance tests pass
- [ ] Memory usage within limits
- [ ] Database queries optimized
- [ ] Caching implemented
- [ ] HTML report generated
- [ ] Results documented
- [ ] Production environment tested

## 🆘 Support

If tests consistently fail:
1. Review optimization recommendations
2. Profile code to identify bottlenecks
3. Check server specifications
4. Review database indexes
5. Consider architectural changes

---

**Quick Start:** `php test-performance.php`
