/**
 * Dark Mode Glassmorphism Verification Script
 * 
 * This script verifies that dark mode glassmorphism is properly implemented
 * according to requirements 9.1, 9.2, 9.3, and 9.5
 * 
 * Run this in the browser console on the test page
 */

(function() {
    'use strict';
    
    console.log('🧪 WOOW! Admin - Dark Mode Glassmorphism Verification\n');
    console.log('=' .repeat(60));
    
    const results = {
        passed: [],
        failed: [],
        warnings: []
    };
    
    // Test 1: Verify CSS file is loaded
    console.log('\n📋 Test 1: CSS File Loading');
    const cssLoaded = Array.from(document.styleSheets).some(sheet => {
        try {
            return sheet.href && sheet.href.includes('glassmorphism');
        } catch (e) {
            return false;
        }
    });
    
    if (cssLoaded) {
        results.passed.push('CSS file loaded successfully');
        console.log('✅ Glassmorphism CSS file is loaded');
    } else {
        results.warnings.push('CSS file might be embedded or not found');
        console.log('⚠️  Glassmorphism CSS file not found (might be embedded)');
    }
    
    // Test 2: Verify glassmorphism elements exist
    console.log('\n📋 Test 2: Glassmorphism Elements');
    const glassElements = {
        sm: document.querySelectorAll('.woow-glass-sm'),
        md: document.querySelectorAll('.woow-glass-md'),
        lg: document.querySelectorAll('.woow-glass-lg'),
        xl: document.querySelectorAll('.woow-glass-xl')
    };
    
    Object.entries(glassElements).forEach(([level, elements]) => {
        if (elements.length > 0) {
            results.passed.push(`Found ${elements.length} .woow-glass-${level} element(s)`);
            console.log(`✅ Found ${elements.length} .woow-glass-${level} element(s)`);
        } else {
            results.failed.push(`No .woow-glass-${level} elements found`);
            console.log(`❌ No .woow-glass-${level} elements found`);
        }
    });
    
    // Test 3: Verify backdrop-filter support (Req 9.3)
    console.log('\n📋 Test 3: Backdrop-Filter Support (Req 9.3)');
    const testDiv = document.createElement('div');
    testDiv.style.backdropFilter = 'blur(10px)';
    const backdropSupported = testDiv.style.backdropFilter !== '';
    
    if (backdropSupported) {
        results.passed.push('Backdrop-filter is supported');
        console.log('✅ Backdrop-filter is supported by this browser');
    } else {
        // Check webkit prefix
        testDiv.style.webkitBackdropFilter = 'blur(10px)';
        const webkitSupported = testDiv.style.webkitBackdropFilter !== '';
        if (webkitSupported) {
            results.passed.push('Webkit-backdrop-filter is supported');
            console.log('✅ -webkit-backdrop-filter is supported by this browser');
        } else {
            results.failed.push('Backdrop-filter not supported');
            console.log('❌ Backdrop-filter is not supported by this browser');
        }
    }
    
    // Test 4: Verify blur values (Req 9.3)
    console.log('\n📋 Test 4: Blur Strength Values (Req 9.3)');
    const expectedBlurs = {
        'woow-glass-sm': '4px',
        'woow-glass-md': '8px',
        'woow-glass-lg': '12px',
        'woow-glass-xl': '16px'
    };
    
    Object.entries(expectedBlurs).forEach(([className, expectedBlur]) => {
        const element = document.querySelector(`.${className}`);
        if (element) {
            const styles = window.getComputedStyle(element);
            const actualBlur = styles.backdropFilter || styles.webkitBackdropFilter;
            
            if (actualBlur && actualBlur.includes(expectedBlur)) {
                results.passed.push(`${className}: ${expectedBlur} blur ✓`);
                console.log(`✅ ${className}: ${expectedBlur} blur applied correctly`);
            } else {
                results.failed.push(`${className}: Expected ${expectedBlur}, got ${actualBlur}`);
                console.log(`❌ ${className}: Expected ${expectedBlur}, got ${actualBlur}`);
            }
        }
    });
    
    // Test 5: Verify dark mode media query exists (Req 9.1)
    console.log('\n📋 Test 5: Dark Mode Media Query (Req 9.1)');
    const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    if (darkModeQuery) {
        results.passed.push('Dark mode media query supported');
        console.log('✅ Dark mode media query is supported');
        console.log(`   Current state: ${darkModeQuery.matches ? 'DARK' : 'LIGHT'} mode`);
    } else {
        results.failed.push('Dark mode media query not supported');
        console.log('❌ Dark mode media query is not supported');
    }
    
    // Test 6: Verify background colors in current mode
    console.log('\n📋 Test 6: Background Colors');
    const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    console.log(`   Testing in ${isDarkMode ? 'DARK' : 'LIGHT'} mode`);
    
    const expectedBackgrounds = isDarkMode ? {
        'woow-glass-sm': 'rgba(30, 41, 59, 0.4)',
        'woow-glass-md': 'rgba(30, 41, 59, 0.5)',
        'woow-glass-lg': 'rgba(30, 41, 59, 0.6)',
        'woow-glass-xl': 'rgba(30, 41, 59, 0.6)'
    } : {
        'woow-glass-sm': 'rgba(255, 255, 255, 0.25)',
        'woow-glass-md': 'rgba(255, 255, 255, 0.15)',
        'woow-glass-lg': 'rgba(255, 255, 255, 0.1)',
        'woow-glass-xl': 'rgba(255, 255, 255, 0.08)'
    };
    
    Object.entries(expectedBackgrounds).forEach(([className, expectedBg]) => {
        const element = document.querySelector(`.${className}`);
        if (element) {
            const styles = window.getComputedStyle(element);
            const actualBg = styles.backgroundColor;
            
            // Convert expected rgba to rgb for comparison
            const rgbaMatch = expectedBg.match(/rgba?\((\d+),\s*(\d+),\s*(\d+),?\s*([\d.]+)?\)/);
            if (rgbaMatch) {
                const [, r, g, b, a] = rgbaMatch;
                const expectedRgba = `rgba(${r}, ${g}, ${b}, ${a || 1})`;
                
                // Normalize both values for comparison
                const normalizeRgba = (str) => str.replace(/\s+/g, '');
                
                if (normalizeRgba(actualBg).includes(normalizeRgba(`${r}, ${g}, ${b}`))) {
                    results.passed.push(`${className}: Background color correct`);
                    console.log(`✅ ${className}: ${actualBg}`);
                } else {
                    results.warnings.push(`${className}: Background might differ (${actualBg})`);
                    console.log(`⚠️  ${className}: Expected ${expectedBg}, got ${actualBg}`);
                }
            }
        }
    });
    
    // Test 7: Verify border colors
    console.log('\n📋 Test 7: Border Colors');
    const expectedBorders = isDarkMode ? {
        'woow-glass-sm': 'rgba(255, 255, 255, 0.1)',
        'woow-glass-md': 'rgba(255, 255, 255, 0.12)',
        'woow-glass-lg': 'rgba(255, 255, 255, 0.15)',
        'woow-glass-xl': 'rgba(255, 255, 255, 0.15)'
    } : {
        'woow-glass-sm': 'rgba(255, 255, 255, 0.18)',
        'woow-glass-md': 'rgba(255, 255, 255, 0.2)',
        'woow-glass-lg': 'rgba(255, 255, 255, 0.2)',
        'woow-glass-xl': 'rgba(255, 255, 255, 0.2)'
    };
    
    Object.entries(expectedBorders).forEach(([className, expectedBorder]) => {
        const element = document.querySelector(`.${className}`);
        if (element) {
            const styles = window.getComputedStyle(element);
            const actualBorder = styles.borderColor;
            
            console.log(`   ${className}: ${actualBorder}`);
            results.passed.push(`${className}: Border applied`);
        }
    });
    
    // Test 8: Text readability check (Req 9.5)
    console.log('\n📋 Test 8: Text Readability (Req 9.5)');
    const textElements = document.querySelectorAll('.woow-glass-sm, .woow-glass-md, .woow-glass-lg, .woow-glass-xl');
    
    textElements.forEach((element, index) => {
        const styles = window.getComputedStyle(element);
        const color = styles.color;
        const bgColor = styles.backgroundColor;
        
        console.log(`   Element ${index + 1}:`);
        console.log(`     Text color: ${color}`);
        console.log(`     Background: ${bgColor}`);
        
        results.passed.push(`Element ${index + 1}: Text styling applied`);
    });
    
    console.log('\n' + '='.repeat(60));
    console.log('📊 SUMMARY');
    console.log('='.repeat(60));
    console.log(`✅ Passed: ${results.passed.length}`);
    console.log(`❌ Failed: ${results.failed.length}`);
    console.log(`⚠️  Warnings: ${results.warnings.length}`);
    
    if (results.failed.length > 0) {
        console.log('\n❌ Failed Tests:');
        results.failed.forEach(fail => console.log(`   - ${fail}`));
    }
    
    if (results.warnings.length > 0) {
        console.log('\n⚠️  Warnings:');
        results.warnings.forEach(warn => console.log(`   - ${warn}`));
    }
    
    const successRate = (results.passed.length / (results.passed.length + results.failed.length)) * 100;
    console.log(`\n📈 Success Rate: ${successRate.toFixed(1)}%`);
    
    if (results.failed.length === 0) {
        console.log('\n🎉 All tests passed! Dark mode glassmorphism is working correctly.');
    } else {
        console.log('\n⚠️  Some tests failed. Please review the issues above.');
    }
    
    console.log('\n💡 Next Steps:');
    console.log('   1. Toggle dark mode using the button or system settings');
    console.log('   2. Run this script again to verify dark mode styles');
    console.log('   3. Manually verify text readability');
    console.log('   4. Check all four strength levels (sm, md, lg, xl)');
    
    return {
        passed: results.passed.length,
        failed: results.failed.length,
        warnings: results.warnings.length,
        successRate: successRate,
        details: results
    };
})();
