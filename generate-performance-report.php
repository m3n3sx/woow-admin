<?php
/**
 * Performance Report Generator
 * 
 * Generates a detailed HTML report of performance test results
 * 
 * Usage: php generate-performance-report.php
 */

// Load WordPress
require_once dirname(__FILE__) . '/../../../wp-load.php';

// Ensure we're in admin context
if (!is_admin()) {
    define('WP_ADMIN', true);
}

// Load plugin files
require_once dirname(__FILE__) . '/woow-admin.php';

class WOOW_Performance_Report_Generator {
    private $results = [];
    private $palette_manager;
    private $template_manager;
    private $css_generator;
    private $settings;
    
    public function __construct() {
        $this->settings = new WOOW_Settings();
        $this->palette_manager = new WOOW_Palette_Manager($this->settings);
        $this->template_manager = new WOOW_Template_Manager($this->settings);
        $this->css_generator = new WOOW_CSS_Generator($this->settings);
    }
    
    /**
     * Run all performance tests and collect data
     */
    public function collect_performance_data() {
        echo "Collecting performance data...\n";
        
        $this->results = [
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => PHP_VERSION,
            'wordpress_version' => get_bloginfo('version'),
            'server_info' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'tests' => []
        ];
        
        // Test 1: Palette list loading
        echo "  Testing palette list loading...\n";
        $this->results['tests']['palette_list_loading'] = $this->test_palette_list_loading();
        
        // Test 2: Template list loading
        echo "  Testing template list loading...\n";
        $this->results['tests']['template_list_loading'] = $this->test_template_list_loading();
        
        // Test 3: Palette application
        echo "  Testing palette application...\n";
        $this->results['tests']['palette_application'] = $this->test_palette_application();
        
        // Test 4: Template application
        echo "  Testing template application...\n";
        $this->results['tests']['template_application'] = $this->test_template_application();
        
        // Test 5: CSS regeneration
        echo "  Testing CSS regeneration...\n";
        $this->results['tests']['css_regeneration'] = $this->test_css_regeneration();
        
        // Test 6: Memory usage
        echo "  Testing memory usage...\n";
        $this->results['tests']['memory_usage'] = $this->test_memory_usage();
        
        echo "Data collection complete!\n\n";
    }
    
    private function test_palette_list_loading() {
        $iterations = 20;
        $times = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            wp_cache_delete('woow_palettes_v1');
            
            $start = microtime(true);
            $palettes = $this->palette_manager->get_all_palettes();
            $end = microtime(true);
            
            $times[] = ($end - $start) * 1000;
        }
        
        return [
            'iterations' => $iterations,
            'times' => $times,
            'avg' => array_sum($times) / count($times),
            'min' => min($times),
            'max' => max($times),
            'median' => $this->calculate_median($times),
            'target' => 500,
            'passed' => (array_sum($times) / count($times)) < 500
        ];
    }
    
    private function test_template_list_loading() {
        $iterations = 20;
        $times = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            wp_cache_delete('woow_templates_v1');
            
            $start = microtime(true);
            $templates = $this->template_manager->get_all_templates();
            $end = microtime(true);
            
            $times[] = ($end - $start) * 1000;
        }
        
        return [
            'iterations' => $iterations,
            'times' => $times,
            'avg' => array_sum($times) / count($times),
            'min' => min($times),
            'max' => max($times),
            'median' => $this->calculate_median($times),
            'target' => 500,
            'passed' => (array_sum($times) / count($times)) < 500
        ];
    }
    
    private function test_palette_application() {
        $palettes = $this->palette_manager->get_all_palettes();
        $palette_ids = array_slice(array_keys($palettes), 0, 5);
        $times = [];
        
        foreach ($palette_ids as $palette_id) {
            $start = microtime(true);
            $this->palette_manager->apply_palette($palette_id);
            $end = microtime(true);
            
            $times[] = ($end - $start) * 1000;
        }
        
        return [
            'iterations' => count($times),
            'times' => $times,
            'avg' => array_sum($times) / count($times),
            'min' => min($times),
            'max' => max($times),
            'median' => $this->calculate_median($times),
            'target' => 2000,
            'passed' => (array_sum($times) / count($times)) < 2000
        ];
    }
    
    private function test_template_application() {
        $templates = $this->template_manager->get_all_templates();
        $template_ids = array_slice(array_keys($templates), 0, 5);
        $times = [];
        
        foreach ($template_ids as $template_id) {
            $start = microtime(true);
            $this->template_manager->apply_template($template_id);
            $end = microtime(true);
            
            $times[] = ($end - $start) * 1000;
        }
        
        return [
            'iterations' => count($times),
            'times' => $times,
            'avg' => array_sum($times) / count($times),
            'min' => min($times),
            'max' => max($times),
            'median' => $this->calculate_median($times),
            'target' => 2000,
            'passed' => (array_sum($times) / count($times)) < 2000
        ];
    }
    
    private function test_css_regeneration() {
        $iterations = 20;
        $times = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $this->css_generator->generate();
            $end = microtime(true);
            
            $times[] = ($end - $start) * 1000;
        }
        
        return [
            'iterations' => $iterations,
            'times' => $times,
            'avg' => array_sum($times) / count($times),
            'min' => min($times),
            'max' => max($times),
            'median' => $this->calculate_median($times),
            'target' => 1000,
            'passed' => (array_sum($times) / count($times)) < 1000
        ];
    }
    
    private function test_memory_usage() {
        $memory_before = memory_get_usage();
        
        $palettes = $this->palette_manager->get_all_palettes();
        $templates = $this->template_manager->get_all_templates();
        
        $memory_after = memory_get_usage();
        $memory_used = $memory_after - $memory_before;
        
        return [
            'memory_before' => $memory_before,
            'memory_after' => $memory_after,
            'memory_used' => $memory_used,
            'memory_used_mb' => $memory_used / 1024 / 1024,
            'peak_memory' => memory_get_peak_usage(),
            'peak_memory_mb' => memory_get_peak_usage() / 1024 / 1024
        ];
    }
    
    private function calculate_median($array) {
        sort($array);
        $count = count($array);
        $middle = floor($count / 2);
        
        if ($count % 2 == 0) {
            return ($array[$middle - 1] + $array[$middle]) / 2;
        } else {
            return $array[$middle];
        }
    }
    
    /**
     * Generate HTML report
     */
    public function generate_html_report() {
        $html = $this->get_html_template();
        
        // Replace placeholders
        $html = str_replace('{{TIMESTAMP}}', $this->results['timestamp'], $html);
        $html = str_replace('{{PHP_VERSION}}', $this->results['php_version'], $html);
        $html = str_replace('{{WP_VERSION}}', $this->results['wordpress_version'], $html);
        $html = str_replace('{{SERVER_INFO}}', $this->results['server_info'], $html);
        $html = str_replace('{{MEMORY_LIMIT}}', $this->results['memory_limit'], $html);
        
        // Generate test results HTML
        $test_results_html = $this->generate_test_results_html();
        $html = str_replace('{{TEST_RESULTS}}', $test_results_html, $html);
        
        // Generate charts data
        $charts_data = $this->generate_charts_data();
        $html = str_replace('{{CHARTS_DATA}}', $charts_data, $html);
        
        // Save report
        $filename = 'performance-report-' . date('Y-m-d-His') . '.html';
        $filepath = dirname(__FILE__) . '/' . $filename;
        file_put_contents($filepath, $html);
        
        echo "HTML report generated: {$filename}\n";
        return $filepath;
    }
    
    private function generate_test_results_html() {
        $html = '';
        
        foreach ($this->results['tests'] as $test_name => $test_data) {
            if ($test_name === 'memory_usage') {
                continue; // Handle separately
            }
            
            $status_class = $test_data['passed'] ? 'passed' : 'failed';
            $status_icon = $test_data['passed'] ? '✅' : '❌';
            $test_label = ucwords(str_replace('_', ' ', $test_name));
            
            $html .= '<div class="test-result ' . $status_class . '">';
            $html .= '<h3>' . $status_icon . ' ' . $test_label . '</h3>';
            $html .= '<div class="metrics">';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Average:</span>';
            $html .= '<span class="value">' . number_format($test_data['avg'], 2) . ' ms</span>';
            $html .= '</div>';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Min:</span>';
            $html .= '<span class="value">' . number_format($test_data['min'], 2) . ' ms</span>';
            $html .= '</div>';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Max:</span>';
            $html .= '<span class="value">' . number_format($test_data['max'], 2) . ' ms</span>';
            $html .= '</div>';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Median:</span>';
            $html .= '<span class="value">' . number_format($test_data['median'], 2) . ' ms</span>';
            $html .= '</div>';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Target:</span>';
            $html .= '<span class="value">< ' . $test_data['target'] . ' ms</span>';
            $html .= '</div>';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Iterations:</span>';
            $html .= '<span class="value">' . $test_data['iterations'] . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        // Memory usage
        if (isset($this->results['tests']['memory_usage'])) {
            $mem = $this->results['tests']['memory_usage'];
            $html .= '<div class="test-result">';
            $html .= '<h3>💾 Memory Usage</h3>';
            $html .= '<div class="metrics">';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Used:</span>';
            $html .= '<span class="value">' . number_format($mem['memory_used_mb'], 2) . ' MB</span>';
            $html .= '</div>';
            $html .= '<div class="metric">';
            $html .= '<span class="label">Peak:</span>';
            $html .= '<span class="value">' . number_format($mem['peak_memory_mb'], 2) . ' MB</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function generate_charts_data() {
        $data = [];
        
        foreach ($this->results['tests'] as $test_name => $test_data) {
            if ($test_name === 'memory_usage' || !isset($test_data['times'])) {
                continue;
            }
            
            $data[$test_name] = [
                'label' => ucwords(str_replace('_', ' ', $test_name)),
                'times' => $test_data['times'],
                'avg' => $test_data['avg'],
                'target' => $test_data['target']
            ];
        }
        
        return json_encode($data);
    }
    
    private function get_html_template() {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WOOW! Admin - Performance Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 18px;
            opacity: 0.9;
        }
        .system-info {
            background: #f8f9fa;
            padding: 30px 40px;
            border-bottom: 1px solid #e9ecef;
        }
        .system-info h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #495057;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-item .label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-item .value {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
        }
        .content {
            padding: 40px;
        }
        .test-result {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .test-result.passed {
            border-left-color: #28a745;
        }
        .test-result.failed {
            border-left-color: #dc3545;
        }
        .test-result h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #212529;
        }
        .metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .metric {
            display: flex;
            flex-direction: column;
        }
        .metric .label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .metric .value {
            font-size: 18px;
            font-weight: 600;
            color: #212529;
        }
        .chart-container {
            margin: 30px 0;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        .chart-container h3 {
            margin-bottom: 20px;
            color: #212529;
        }
        canvas {
            max-height: 400px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 40px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 WOOW! Admin Performance Report</h1>
            <p>Generated on {{TIMESTAMP}}</p>
        </div>
        
        <div class="system-info">
            <h2>System Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">PHP Version</span>
                    <span class="value">{{PHP_VERSION}}</span>
                </div>
                <div class="info-item">
                    <span class="label">WordPress Version</span>
                    <span class="value">{{WP_VERSION}}</span>
                </div>
                <div class="info-item">
                    <span class="label">Server</span>
                    <span class="value">{{SERVER_INFO}}</span>
                </div>
                <div class="info-item">
                    <span class="label">Memory Limit</span>
                    <span class="value">{{MEMORY_LIMIT}}</span>
                </div>
            </div>
        </div>
        
        <div class="content">
            <h2 style="margin-bottom: 25px; color: #212529;">Test Results</h2>
            {{TEST_RESULTS}}
            
            <div class="chart-container">
                <h3>Performance Over Time</h3>
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        
        <div class="footer">
            <p>WOOW! Admin Plugin - Performance Testing Suite</p>
        </div>
    </div>
    
    <script>
        const chartsData = {{CHARTS_DATA}};
        
        // Create performance chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const datasets = [];
        const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b'];
        
        let colorIndex = 0;
        for (const [testName, testData] of Object.entries(chartsData)) {
            datasets.push({
                label: testData.label,
                data: testData.times,
                borderColor: colors[colorIndex % colors.length],
                backgroundColor: colors[colorIndex % colors.length] + '20',
                tension: 0.4,
                fill: true
            });
            colorIndex++;
        }
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({length: datasets[0]?.data.length || 0}, (_, i) => i + 1),
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Performance Metrics (ms)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Time (ms)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Iteration'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
HTML;
    }
}

// Run report generation
try {
    $generator = new WOOW_Performance_Report_Generator();
    $generator->collect_performance_data();
    $report_path = $generator->generate_html_report();
    
    echo "\n✅ Performance report generated successfully!\n";
    echo "Open the report in your browser: {$report_path}\n\n";
    
} catch (Exception $e) {
    echo "\n❌ Error generating performance report:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
