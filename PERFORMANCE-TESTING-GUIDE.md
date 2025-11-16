# WOOW! Admin - Performance Testing Guide

## Overview

This guide covers performance testing for the WOOW! Admin plugin's palette and template system. The tests ensure that all operations meet the specified performance targets.

## Performance Targets

| Operation | Target | Requirement |
|-----------|--------|-------------|
| Palette List Loading | < 500ms | 30.1 |
| Template List Loading | < 500ms | 30.1 |
| Palette Application | < 2s | 30.2 |
| Template Application | < 2s | 30.2 |
| CSS Regeneration | < 1s | 30.5 |

## Testing Methods

### Method 1: Standalone Performance Test Script

The most comprehensive testing method with detailed output.

```bash
cd woow-admin
php test-performance.php
```

**Features:**
- Tests all 5 performance metrics
- Multiple iterations for accuracy
- Detailed console output
- JSON export of results
- Performance recommendations

**Output:**
```
╔════════════════════════════════════════════════════════════════╗
║         WOOW! Admin - Performance Testing Suite               ║
╚════════════════════════════════════════════════════════════════╝

Test 1: Palette List Loading Time
----------------------------------------------------------------
  Iteration  1:  45.23 ms (10 palettes loaded)
  Iteration  2:  42.18 ms (10 palettes loaded)
  ...
  
  Average: 43.52 ms
  Min:     41.05 ms
  Max:     48.91 ms
  Target:  < 500 ms
  Status:  ✅ PASSED
```

### Method 2: PHPUnit Performance Tests

Integrated with the test suite for CI/CD pipelines.

```bash
cd woow-admin
./vendor/bin/phpunit tests/php/PerformanceTest.php
```

**Features:**
- PHPUnit assertions
- CI/CD integration
- Test isolation
- Memory usage tracking
- Database query counting

**Output:**
```
PHPUnit 9.5.x

Palette List Loading: 43.52 ms (avg of 5 runs)
.
Template List Loading: 38.91 ms (avg of 5 runs)
.
Palette Application: 156.23 ms (avg of 3 runs)
.
Template Application: 142.87 ms (avg of 3 runs)
.
CSS Regeneration: 89.45 ms (avg of 5 runs)
.

Time: 00:05.234, Memory: 12.00 MB

OK (5 tests, 15 assertions)
```

### Method 3: HTML Performance Report

Generates a visual HTML report with charts.

```bash
cd woow-admin
php generate-performance-report.php
```

**Features:**
- Beautiful HTML report
- Interactive charts (Chart.js)
- System information
- Detailed metrics
- Exportable results

**Output:**
- Creates `performance-report-YYYY-MM-DD-HHMMSS.html`
- Open in browser for visual analysis
- Includes line charts showing performance over iterations
- Color-coded pass/fail indicators

## Test Details

### 1. Palette List Loading Test

**What it tests:**
- Loading all palette definitions from `includes/data/palettes.php`
- Parsing palette data structures
- Validation of palette completeness

**Methodology:**
- 10-20 iterations
- Cache cleared before each iteration
- Measures time from load start to completion
- Verifies all 10 palettes loaded

**Target:** < 500ms average

**Optimization tips if failing:**
- Implement caching with `wp_cache_set()`
- Lazy load palette data
- Optimize data structure (reduce nesting)
- Use opcode caching (OPcache)

### 2. Template List Loading Test

**What it tests:**
- Loading all template definitions from `includes/data/templates-data.php`
- Parsing template data structures
- Validation of template completeness

**Methodology:**
- 10-20 iterations
- Cache cleared before each iteration
- Measures time from load start to completion
- Verifies all 11 templates loaded

**Target:** < 500ms average

**Optimization tips if failing:**
- Implement caching with `wp_cache_set()`
- Lazy load template data
- Optimize data structure
- Use opcode caching

### 3. Palette Application Test

**What it tests:**
- Applying palette settings to WordPress options
- Merging palette data with current settings
- Database update operations
- CSS regeneration (if included)

**Methodology:**
- 3-5 iterations
- Tests multiple palettes
- Measures complete application cycle
- Verifies successful application

**Target:** < 2000ms average

**Optimization tips if failing:**
- Batch database updates (single `update_option()`)
- Defer CSS regeneration
- Optimize settings merge algorithm
- Use transients for temporary data

### 4. Template Application Test

**What it tests:**
- Applying template settings to WordPress options
- Merging template data with current settings
- Database update operations
- CSS regeneration (if included)

**Methodology:**
- 3-5 iterations
- Tests multiple templates
- Measures complete application cycle
- Verifies successful application

**Target:** < 2000ms average

**Optimization tips if failing:**
- Batch database updates
- Defer CSS regeneration
- Optimize settings merge
- Minimize validation overhead

### 5. CSS Regeneration Test

**What it tests:**
- Generating CSS from current settings
- Processing all sections (admin bar, menu, widgets, etc.)
- String concatenation performance
- File write operations (if applicable)

**Methodology:**
- 10-20 iterations
- Measures pure CSS generation time
- No database operations included
- Tests with current settings

**Target:** < 1000ms average

**Optimization tips if failing:**
- Use string builder pattern
- Cache generated CSS
- Minimize string concatenation
- Optimize CSS generation algorithm
- Use output buffering

## Additional Performance Metrics

### Memory Usage

**Tracked metrics:**
- Memory used during palette loading
- Memory used during template loading
- Peak memory usage
- Memory limit compliance

**Targets:**
- Palette loading: < 5 MB
- Template loading: < 5 MB
- Peak memory: < 50% of memory_limit

### Database Queries

**Tracked metrics:**
- Number of queries during application
- Query execution time
- Query complexity

**Targets:**
- Palette application: < 5 queries
- Template application: < 5 queries
- Ideally: 1-2 queries (batch updates)

### Concurrent Operations

**Tracked metrics:**
- Multiple simultaneous operations
- Cache efficiency
- Resource contention

**Target:**
- Concurrent operations: < 1000ms total

## Interpreting Results

### ✅ All Tests Passed

```
Overall Status: ✅ ALL TESTS PASSED
```

**Action:** No action needed. Performance is within targets.

### ❌ Some Tests Failed

```
Overall Status: ❌ SOME TESTS FAILED

Performance Recommendations:
• Palette List Loading:
  - Implement caching with wp_cache_set()
  - Consider lazy loading palette data
  - Optimize palette data structure
```

**Action:** Follow the specific recommendations for each failed test.

## Performance Optimization Strategies

### 1. Caching

```php
// Cache palette data
$cache_key = 'woow_palettes_v1';
$palettes = wp_cache_get($cache_key);

if ($palettes === false) {
    $palettes = require WOOW_PLUGIN_DIR . 'includes/data/palettes.php';
    wp_cache_set($cache_key, $palettes, '', 3600); // Cache for 1 hour
}
```

### 2. Lazy Loading

```php
class WOOW_Palette_Manager {
    private ?array $palettes = null;
    
    public function get_all_palettes(): array {
        if ($this->palettes === null) {
            $this->load_palettes();
        }
        return $this->palettes;
    }
}
```

### 3. Batch Database Updates

```php
// Instead of multiple update_option() calls
update_option('woow_admin_settings', $all_settings);

// Not this:
// update_option('woow_admin_bar', $bar_settings);
// update_option('woow_admin_menu', $menu_settings);
// ... etc
```

### 4. Deferred CSS Regeneration

```php
// Apply settings first
$this->apply_palette($palette_id);

// Regenerate CSS after (or defer to next page load)
add_action('shutdown', function() {
    $this->css_generator->regenerate();
});
```

### 5. Opcode Caching

Ensure OPcache is enabled in PHP:

```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

## Continuous Integration

### GitHub Actions Example

```yaml
name: Performance Tests

on: [push, pull_request]

jobs:
  performance:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: mbstring, xml
          
      - name: Install dependencies
        run: composer install
        
      - name: Run performance tests
        run: ./vendor/bin/phpunit tests/php/PerformanceTest.php
        
      - name: Check performance targets
        run: |
          if grep -q "FAILED" phpunit.log; then
            echo "Performance tests failed!"
            exit 1
          fi
```

## Troubleshooting

### Test Fails Intermittently

**Possible causes:**
- Server load variations
- Cache state differences
- Database query caching

**Solutions:**
- Run more iterations
- Clear all caches before testing
- Test on dedicated environment

### Memory Limit Errors

**Error:** `Fatal error: Allowed memory size exhausted`

**Solutions:**
- Increase PHP memory_limit
- Optimize data structures
- Implement lazy loading
- Clear unused variables

### Timeout Errors

**Error:** `Maximum execution time exceeded`

**Solutions:**
- Increase max_execution_time
- Optimize slow operations
- Reduce test iterations
- Profile code to find bottlenecks

## Profiling Tools

### Xdebug Profiler

```ini
; php.ini
xdebug.mode=profile
xdebug.output_dir=/tmp/xdebug
xdebug.profiler_output_name=cachegrind.out.%p
```

Analyze with:
- KCachegrind (Linux)
- QCachegrind (Windows/Mac)
- Webgrind (Web-based)

### Blackfire.io

```bash
# Install Blackfire probe
blackfire run php test-performance.php
```

### New Relic

Monitor in production:
- Transaction traces
- Database queries
- External services
- Memory usage

## Best Practices

1. **Test Regularly**
   - Run performance tests before each release
   - Include in CI/CD pipeline
   - Monitor production performance

2. **Set Baselines**
   - Record initial performance metrics
   - Track improvements over time
   - Detect performance regressions

3. **Test Realistic Scenarios**
   - Use production-like data
   - Test with typical server specs
   - Include network latency

4. **Optimize Incrementally**
   - Profile before optimizing
   - Measure impact of changes
   - Don't over-optimize

5. **Document Results**
   - Keep performance reports
   - Track optimization history
   - Share results with team

## Performance Checklist

Before release, verify:

- [ ] All performance tests pass
- [ ] Memory usage within limits
- [ ] Database queries optimized
- [ ] Caching implemented
- [ ] No N+1 query problems
- [ ] CSS generation optimized
- [ ] Large data sets tested
- [ ] Production environment tested
- [ ] Performance report generated
- [ ] Results documented

## Support

If performance tests consistently fail:

1. Review optimization recommendations
2. Profile code to identify bottlenecks
3. Check server specifications
4. Review database indexes
5. Consider architectural changes
6. Contact development team

## References

- [WordPress Performance Best Practices](https://developer.wordpress.org/advanced-administration/performance/)
- [PHP Performance Tips](https://www.php.net/manual/en/features.performance.php)
- [Database Optimization](https://developer.wordpress.org/advanced-administration/performance/optimization/)
- [Caching Strategies](https://developer.wordpress.org/reference/classes/wp_object_cache/)
