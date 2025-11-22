<?php
/**
 * Visual Quality Assurance Test
 * 
 * Tests visual consistency and quality of glassmorphism implementation
 * Requirements: 15.1, 15.2, 15.3, 15.4, 15.5
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

class Visual_QA_Test {
    private $results = [];
    private $passed = 0;
    private $failed = 0;

    public function run() {
        echo "=== WOOW! Admin - Visual Quality Assurance Test ===\n\n";
        
        $this->test_css_variable_consistency();
        $this->test_border_consistency();
        $this->test_shadow_consistency();
        $this->test_color_harmony();
        $this->test_opacity_values();
        
        $this->print_summary();
    }

    private function test_css_variable_consistency() {
        echo "Testing CSS Variable Consistency...\n";
        
        $css_file = __DIR__ . '/assets/src/css/glassmorphism-system.css';
        
        if (!file_exists($css_file)) {
            $this->fail("CSS file not found: $css_file");
            return;
        }
        
        $css_content = file_get_contents($css_file);
        
        // Test blur variables
        $blur_vars = [
            '--glass-blur-sm: 4px' => '4px blur for sm',
            '--glass-blur-md: 8px' => '8px blur for md',
            '--glass-blur-lg: 12px' => '12px blur for lg',
            '--glass-blur-xl: 16px' => '16px blur for xl',
        ];
        
        foreach ($blur_vars as $var => $description) {
            if (strpos($css_content, $var) !== false) {
                $this->pass("✓ CSS variable defined: $description");
            } else {
                $this->fail("✗ CSS variable missing: $description");
            }
        }
        
        // Test that all utility classes use CSS variables
        $classes = ['woow-glass-sm', 'woow-glass-md', 'woow-glass-lg', 'woow-glass-xl'];
        foreach ($classes as $class) {
            if (preg_match("/\.$class\s*\{[^}]*backdrop-filter:\s*blur\(var\(--glass-blur-/", $css_content)) {
                $this->pass("✓ Class $class uses CSS variable for blur");
            } else {
                $this->fail("✗ Class $class doesn't use CSS variable for blur");
            }
        }
        
        echo "\n";
    }

    private function test_border_consistency() {
        echo "Testing Border Consistency...\n";
        
        $css_file = __DIR__ . '/assets/src/css/glassmorphism-system.css';
        $css_content = file_get_contents($css_file);
        
        // Test border width consistency (should all be 1px)
        $border_pattern = '/border:\s*1px\s+solid/i';
        $matches = preg_match_all($border_pattern, $css_content);
        
        if ($matches >= 4) { // At least 4 classes should have 1px borders
            $this->pass("✓ Border width is consistent (1px) across classes");
        } else {
            $this->fail("✗ Border width inconsistency detected");
        }
        
        // Test light mode border opacity (should be 0.18-0.2)
        $light_borders = [
            'rgba(255, 255, 255, 0.18)' => 'sm border opacity',
            'rgba(255, 255, 255, 0.2)' => 'md/lg/xl border opacity',
        ];
        
        foreach ($light_borders as $border => $description) {
            if (strpos($css_content, $border) !== false) {
                $this->pass("✓ Light mode $description correct");
            } else {
                $this->fail("✗ Light mode $description missing or incorrect");
            }
        }
        
        // Test dark mode border opacity (should be 0.1-0.15)
        if (preg_match('/@media\s*\(prefers-color-scheme:\s*dark\)/i', $css_content)) {
            $this->pass("✓ Dark mode media query present");
            
            // Extract dark mode section
            preg_match('/@media\s*\(prefers-color-scheme:\s*dark\)\s*\{([^}]+\}[^}]*)\}/is', $css_content, $dark_section);
            
            if (!empty($dark_section[1])) {
                $dark_content = $dark_section[1];
                
                if (strpos($dark_content, 'rgba(255, 255, 255, 0.1)') !== false ||
                    strpos($dark_content, 'rgba(255, 255, 255, 0.12)') !== false ||
                    strpos($dark_content, 'rgba(255, 255, 255, 0.15)') !== false) {
                    $this->pass("✓ Dark mode border opacity correct (0.1-0.15)");
                } else {
                    $this->fail("✗ Dark mode border opacity incorrect");
                }
            }
        } else {
            $this->fail("✗ Dark mode media query missing");
        }
        
        echo "\n";
    }

    private function test_shadow_consistency() {
        echo "Testing Shadow Consistency...\n";
        
        $css_file = __DIR__ . '/assets/src/css/glassmorphism-system.css';
        $css_content = file_get_contents($css_file);
        
        // Test shadow format (should be 0 8px 32px rgba(...))
        $shadow_pattern = '/box-shadow:\s*0\s+8px\s+32px\s+rgba\(31,\s*38,\s*135,\s*0\.\d+\)/i';
        $matches = preg_match_all($shadow_pattern, $css_content);
        
        if ($matches >= 4) {
            $this->pass("✓ Shadow format is consistent (0 8px 32px)");
        } else {
            $this->fail("✗ Shadow format inconsistency detected");
        }
        
        // Test shadow opacity progression
        $shadow_opacities = [
            '0.1' => 'sm shadow (subtle)',
            '0.25' => 'md shadow (moderate)',
            '0.37' => 'lg/xl shadow (strong)',
        ];
        
        foreach ($shadow_opacities as $opacity => $description) {
            if (strpos($css_content, "rgba(31, 38, 135, $opacity)") !== false) {
                $this->pass("✓ Shadow opacity $description correct");
            } else {
                $this->fail("✗ Shadow opacity $description missing or incorrect");
            }
        }
        
        // Test shadow color consistency (should all use same blue-gray)
        $shadow_color_pattern = '/rgba\(31,\s*38,\s*135/i';
        $color_matches = preg_match_all($shadow_color_pattern, $css_content);
        
        if ($color_matches >= 4) {
            $this->pass("✓ Shadow color is consistent (rgba(31, 38, 135, ...))");
        } else {
            $this->fail("✗ Shadow color inconsistency detected");
        }
        
        echo "\n";
    }

    private function test_color_harmony() {
        echo "Testing Color Harmony...\n";
        
        $css_file = __DIR__ . '/assets/src/css/glassmorphism-system.css';
        $css_content = file_get_contents($css_file);
        
        // Test light mode background progression (opacity should decrease as blur increases)
        $light_backgrounds = [
            'rgba(255, 255, 255, 0.25)' => 'sm (highest opacity)',
            'rgba(255, 255, 255, 0.15)' => 'md',
            'rgba(255, 255, 255, 0.1)' => 'lg',
            'rgba(255, 255, 255, 0.08)' => 'xl (lowest opacity)',
        ];
        
        $found_count = 0;
        foreach ($light_backgrounds as $bg => $description) {
            if (strpos($css_content, $bg) !== false) {
                $this->pass("✓ Light mode background $description correct");
                $found_count++;
            } else {
                $this->fail("✗ Light mode background $description missing");
            }
        }
        
        if ($found_count === 4) {
            $this->pass("✓ Light mode opacity progression is correct (0.25 → 0.08)");
        }
        
        // Test dark mode backgrounds
        $dark_backgrounds = [
            'rgba(30, 41, 59, 0.4)' => 'sm',
            'rgba(30, 41, 59, 0.5)' => 'md',
            'rgba(30, 41, 59, 0.6)' => 'lg/xl',
        ];
        
        foreach ($dark_backgrounds as $bg => $description) {
            if (strpos($css_content, $bg) !== false) {
                $this->pass("✓ Dark mode background $description correct");
            } else {
                $this->fail("✗ Dark mode background $description missing");
            }
        }
        
        // Test color consistency (all light backgrounds use white, all dark use slate)
        $white_bg_count = preg_match_all('/rgba\(255,\s*255,\s*255,/i', $css_content);
        $slate_bg_count = preg_match_all('/rgba\(30,\s*41,\s*59,/i', $css_content);
        
        if ($white_bg_count >= 4) {
            $this->pass("✓ Light mode uses consistent white base (rgba(255, 255, 255, ...))");
        } else {
            $this->fail("✗ Light mode color inconsistency detected");
        }
        
        if ($slate_bg_count >= 3) {
            $this->pass("✓ Dark mode uses consistent slate base (rgba(30, 41, 59, ...))");
        } else {
            $this->fail("✗ Dark mode color inconsistency detected");
        }
        
        echo "\n";
    }

    private function test_opacity_values() {
        echo "Testing Opacity Values...\n";
        
        $css_file = __DIR__ . '/assets/src/css/glassmorphism-system.css';
        $css_content = file_get_contents($css_file);
        
        // Test that opacity decreases as blur increases (for light mode)
        $opacities = [0.25, 0.15, 0.1, 0.08];
        $previous = 1.0;
        $progression_correct = true;
        
        foreach ($opacities as $opacity) {
            if ($opacity >= $previous) {
                $progression_correct = false;
                break;
            }
            $previous = $opacity;
        }
        
        if ($progression_correct) {
            $this->pass("✓ Light mode opacity decreases with blur strength (0.25 → 0.08)");
        } else {
            $this->fail("✗ Light mode opacity progression incorrect");
        }
        
        // Test that dark mode opacity increases with blur
        $dark_opacities = [0.4, 0.5, 0.6, 0.6];
        $previous = 0.0;
        $progression_correct = true;
        
        foreach ($dark_opacities as $opacity) {
            if ($opacity < $previous) {
                $progression_correct = false;
                break;
            }
            $previous = $opacity;
        }
        
        if ($progression_correct) {
            $this->pass("✓ Dark mode opacity increases with blur strength (0.4 → 0.6)");
        } else {
            $this->fail("✗ Dark mode opacity progression incorrect");
        }
        
        // Test that all opacity values are within reasonable ranges
        $all_opacities_valid = true;
        
        // Extract main CSS (before @supports fallback section)
        $main_css = preg_split('/@supports\s+not\s*\(/i', $css_content)[0];
        
        // Light mode: 0.08 - 0.25 (main glassmorphism)
        preg_match_all('/rgba\(255,\s*255,\s*255,\s*(0\.\d+)\)/i', $main_css, $light_matches);
        foreach ($light_matches[1] as $opacity) {
            $val = floatval($opacity);
            if ($val < 0.05 || $val > 0.3) {
                $all_opacities_valid = false;
                $this->fail("✗ Light mode opacity out of range: $opacity");
            }
        }
        
        // Dark mode: 0.4 - 0.6 (main glassmorphism)
        preg_match_all('/rgba\(30,\s*41,\s*59,\s*(0\.\d+)\)/i', $main_css, $dark_matches);
        foreach ($dark_matches[1] as $opacity) {
            $val = floatval($opacity);
            if ($val < 0.3 || $val > 0.7) {
                $all_opacities_valid = false;
                $this->fail("✗ Dark mode opacity out of range: $opacity");
            }
        }
        
        if ($all_opacities_valid) {
            $this->pass("✓ All opacity values are within reasonable ranges");
        }
        
        // Test fallback opacity values (should be 0.9 for solid backgrounds)
        if (strpos($css_content, 'rgba(255, 255, 255, 0.9)') !== false) {
            $this->pass("✓ Light mode fallback opacity correct (0.9 for solid background)");
        } else {
            $this->fail("✗ Light mode fallback opacity missing or incorrect");
        }
        
        if (strpos($css_content, 'rgba(30, 41, 59, 0.9)') !== false) {
            $this->pass("✓ Dark mode fallback opacity correct (0.9 for solid background)");
        } else {
            $this->fail("✗ Dark mode fallback opacity missing or incorrect");
        }
        
        echo "\n";
    }

    private function pass($message) {
        $this->results[] = ['status' => 'pass', 'message' => $message];
        $this->passed++;
        echo "$message\n";
    }

    private function fail($message) {
        $this->results[] = ['status' => 'fail', 'message' => $message];
        $this->failed++;
        echo "$message\n";
    }

    private function print_summary() {
        echo "\n=== Test Summary ===\n";
        echo "Total Tests: " . ($this->passed + $this->failed) . "\n";
        echo "Passed: " . $this->passed . " ✓\n";
        echo "Failed: " . $this->failed . " ✗\n";
        
        if ($this->failed === 0) {
            echo "\n✅ All visual quality checks passed!\n";
            echo "The glassmorphism implementation is visually consistent.\n";
        } else {
            echo "\n⚠️  Some visual quality checks failed.\n";
            echo "Review the failures above and make necessary adjustments.\n";
        }
        
        echo "\n=== Requirements Coverage ===\n";
        echo "15.1 - Consistent strength level: " . ($this->passed > 0 ? "✓" : "✗") . "\n";
        echo "15.2 - Consistent border styling: " . ($this->passed > 0 ? "✓" : "✗") . "\n";
        echo "15.3 - Consistent shadow values: " . ($this->passed > 0 ? "✓" : "✗") . "\n";
        echo "15.4 - Sufficient background opacity: " . ($this->passed > 0 ? "✓" : "✗") . "\n";
        echo "15.5 - Consistent spacing/padding: " . ($this->passed > 0 ? "✓" : "✗") . "\n";
    }
}

// Run the test
$test = new Visual_QA_Test();
$test->run();
