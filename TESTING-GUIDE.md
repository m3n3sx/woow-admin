# WOOW! Admin - Testing Guide

## Quick Start

### Running All Tests

```bash
# From plugin root directory
./vendor/bin/phpunit

# With test documentation
./vendor/bin/phpunit --testdox

# With colors
./vendor/bin/phpunit --colors=always
```

### Running Specific Tests

```bash
# Run specific test class
./vendor/bin/phpunit tests/php/PaletteManagerTest.php

# Run specific test method
./vendor/bin/phpunit --filter test_load_palettes_successfully

# Run tests matching pattern
./vendor/bin/phpunit --filter Palette
```

## Test Structure

```
woow-admin/
├── tests/
│   ├── bootstrap.php          # Test bootstrap and WordPress mocks
│   └── php/
│       └── PaletteManagerTest.php  # Palette Manager unit tests
├── phpunit.xml                # PHPUnit configuration
└── composer.json              # Test dependencies
```

## Available Test Suites

### Unit Tests

#### PaletteManagerTest (29 tests, 416 assertions)
Tests for `WOOW_Palette_Manager` class:
- Palette loading and retrieval
- Validation and completeness checks
- Palette application with error handling
- Category filtering and utility methods

**Run**: `./vendor/bin/phpunit tests/php/PaletteManagerTest.php`

## Test Dependencies

### Required Packages (composer.json)

```json
{
  "require-dev": {
    "phpunit/phpunit": "^10.0",
    "squizlabs/php_codesniffer": "^3.7",
    "wp-coding-standards/wpcs": "^3.0",
    "phpstan/phpstan": "^1.10"
  }
}
```

### Installing Dependencies

```bash
composer install
```

## Writing New Tests

### Test Class Template

```php
<?php
namespace WOOW\Tests;

use PHPUnit\Framework\TestCase;

class MyClassTest extends TestCase {
    private $instance;
    
    protected function setUp(): void {
        parent::setUp();
        $this->instance = new MyClass();
    }
    
    public function test_something(): void {
        $result = $this->instance->doSomething();
        $this->assertTrue( $result );
    }
    
    protected function tearDown(): void {
        parent::tearDown();
    }
}
```

### Naming Conventions

- Test files: `ClassNameTest.php`
- Test methods: `test_method_name_does_something()`
- Use descriptive names that explain what is being tested

### Assertions

```php
// Type assertions
$this->assertIsArray( $value );
$this->assertIsString( $value );
$this->assertInstanceOf( ClassName::class, $object );

// Value assertions
$this->assertEquals( $expected, $actual );
$this->assertTrue( $condition );
$this->assertNull( $value );

// Array assertions
$this->assertArrayHasKey( 'key', $array );
$this->assertCount( 10, $array );
$this->assertEmpty( $array );

// Exception assertions
$this->expectException( Exception::class );
$this->expectExceptionMessage( 'Error message' );
```

### Mocking

```php
// Create mock
$mock = $this->createMock( ClassName::class );

// Configure mock method
$mock->method( 'methodName' )
     ->willReturn( 'return value' );

// Configure mock to throw exception
$mock->method( 'methodName' )
     ->willThrowException( new Exception( 'Error' ) );

// Verify method was called
$mock->expects( $this->once() )
     ->method( 'methodName' );
```

## Code Coverage

### Generating Coverage Report

```bash
# HTML report (requires Xdebug)
./vendor/bin/phpunit --coverage-html tests/coverage/html

# Text report
./vendor/bin/phpunit --coverage-text

# View HTML report
open tests/coverage/html/index.html
```

### Installing Xdebug

```bash
# Ubuntu/Debian
sudo apt-get install php-xdebug

# macOS (Homebrew)
pecl install xdebug

# Enable in php.ini
zend_extension=xdebug.so
xdebug.mode=coverage
```

## Code Quality Tools

### PHP CodeSniffer (WordPress Coding Standards)

```bash
# Check code style
composer phpcs

# Auto-fix code style
composer phpcbf

# Check specific file
./vendor/bin/phpcs includes/class-woow-palette-manager.php
```

### PHPStan (Static Analysis)

```bash
# Analyze code
composer analyse

# Analyze specific file
./vendor/bin/phpstan analyse includes/class-woow-palette-manager.php
```

## Continuous Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.0'
          extensions: mbstring, xml
          coverage: xdebug
      
      - name: Install dependencies
        run: composer install
      
      - name: Run tests
        run: ./vendor/bin/phpunit
      
      - name: Check code style
        run: composer phpcs
      
      - name: Static analysis
        run: composer analyse
```

## Troubleshooting

### Common Issues

#### "Class not found" errors
- Run `composer dump-autoload`
- Check namespace declarations
- Verify class files are in correct directories

#### "Method does not exist" errors
- Check mock method names match actual class
- Verify method visibility (public/protected/private)

#### Tests fail in CI but pass locally
- Check PHP version compatibility
- Verify all dependencies are installed
- Check for environment-specific code

### Debug Mode

```bash
# Run with verbose output
./vendor/bin/phpunit --verbose

# Stop on first failure
./vendor/bin/phpunit --stop-on-failure

# Show test execution order
./vendor/bin/phpunit --debug
```

## Best Practices

1. **Test One Thing**: Each test should verify one specific behavior
2. **Use Descriptive Names**: Test names should explain what is being tested
3. **Arrange-Act-Assert**: Structure tests with setup, execution, and verification
4. **Mock External Dependencies**: Don't rely on database, filesystem, or network
5. **Test Edge Cases**: Include tests for error conditions and boundary values
6. **Keep Tests Fast**: Unit tests should run in milliseconds
7. **Make Tests Independent**: Tests should not depend on each other
8. **Use setUp/tearDown**: Initialize and clean up in proper lifecycle methods

## Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [PHPStan Documentation](https://phpstan.org/user-guide/getting-started)
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

---

**Last Updated**: 2024
**PHPUnit Version**: 10.5.x
**PHP Version**: 8.0+
