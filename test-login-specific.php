<?php
/**
 * Test Login Page Specific Issues
 */

// Load WordPress
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

echo "<style>body { font-family: monospace; } table { border-collapse: collapse; } td, th { border: 1px solid #ccc; padding: 8px; }</style>";
echo "<h1>🔍 Login Page - Specific Test</h1>";

// Get current settings
$settings = get_option('woow_admin_settings', array());
$login = $settings['login_page'] ?? array();

echo "<h2>1️⃣ Current Login Page Settings in Database</h2>";
echo "<table>";
echo "<tr><th>Key</th><th>Value</th></tr>";

$keys = ['enabled', 'logo_url', 'background_type', 'background_color', 'gradient_start', 'gradient_end', 'background_image'];
foreach ($keys as $key) {
    $value = $login[$key] ?? 'NOT SET';
    echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

echo "<h2>2️⃣ Test: Change Background Type and Save</h2>";
echo "<form method='post'>";
echo "<p>Select Background Type:</p>";
echo "<label><input type='radio' name='test_bg_type' value='color' " . ($login['background_type'] === 'color' ? 'checked' : '') . "> Solid Color</label><br>";
echo "<label><input type='radio' name='test_bg_type' value='gradient' " . ($login['background_type'] === 'gradient' ? 'checked' : '') . "> Gradient</label><br>";
echo "<label><input type='radio' name='test_bg_type' value='image' " . ($login['background_type'] === 'image' ? 'checked' : '') . "> Image</label><br>";
echo "<br>";
echo "<input type='color' name='test_color' value='" . ($login['background_color'] ?? '#f8fafc') . "'> Background Color<br>";
echo "<br>";
echo "<button type='submit' name='test_save' class='button button-primary'>Test Save</button>";
echo "</form>";

if (isset($_POST['test_save'])) {
    $settings['login_page']['background_type'] = $_POST['test_bg_type'];
    $settings['login_page']['background_color'] = $_POST['test_color'];
    
    update_option('woow_admin_settings', $settings);
    
    echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;'>";
    echo "✅ <strong>Test save successful!</strong><br>";
    echo "Background Type: " . $_POST['test_bg_type'] . "<br>";
    echo "Background Color: " . $_POST['test_color'] . "<br>";
    echo "<a href='?'>Refresh to see changes</a>";
    echo "</div>";
}

echo "<h2>3️⃣ Check Template File</h2>";
$template_file = __DIR__ . '/includes/templates/tabs/login-tab.php';
$template_content = file_get_contents($template_file);

$checks = [
    'Has name="login_page[background_color]"' => strpos($template_content, 'name="login_page[background_color]"') !== false,
    'Has data-show-when="login_page[background_type]=color"' => strpos($template_content, 'data-show-when="login_page[background_type]=color"') !== false,
    'Has woow-upload-image class' => strpos($template_content, 'woow-upload-image') !== false,
    'Has data-target attribute' => strpos($template_content, 'data-target=') !== false,
];

echo "<table>";
echo "<tr><th>Check</th><th>Result</th></tr>";
foreach ($checks as $check => $result) {
    $status = $result ? '✅ PASS' : '❌ FAIL';
    echo "<tr><td>{$check}</td><td>{$status}</td></tr>";
}
echo "</table>";

echo "<h2>4️⃣ Check JavaScript</h2>";
$js_file = __DIR__ . '/assets/dist/main.js';
$js_content = file_get_contents($js_file);

$js_checks = [
    'Has MediaUploader' => strpos($js_content, 'MediaUploader') !== false,
    'Has openMediaUploader' => strpos($js_content, 'openMediaUploader') !== false,
    'Has woow-upload-image handler' => strpos($js_content, 'woow-upload-image') !== false,
];

echo "<table>";
echo "<tr><th>Check</th><th>Result</th></tr>";
foreach ($js_checks as $check => $result) {
    $status = $result ? '✅ PASS' : '❌ FAIL';
    echo "<tr><td>{$check}</td><td>{$status}</td></tr>";
}
echo "</table>";

echo "<h2>5️⃣ Check wp_enqueue_media</h2>";
$admin_class = file_get_contents(__DIR__ . '/includes/class-woow-admin.php');
$has_enqueue = strpos($admin_class, 'wp_enqueue_media()') !== false;

echo "<p>wp_enqueue_media() in code: " . ($has_enqueue ? "✅ YES" : "❌ NO") . "</p>";

echo "<h2>6️⃣ JavaScript Console Test</h2>";
echo "<p>Open browser console (F12) and run:</p>";
echo "<pre style='background: #f0f0f0; padding: 10px;'>";
echo "// Check if wp.media is available\n";
echo "console.log('wp:', typeof wp);\n";
echo "console.log('wp.media:', typeof wp.media);\n";
echo "\n";
echo "// Check if MediaUploader is initialized\n";
echo "// Look for: [MediaUploader] Initialized\n";
echo "</pre>";

echo "<h2>7️⃣ Manual Upload Test</h2>";
echo "<p>Click button below to test WordPress Media Library:</p>";
echo "<button type='button' id='test-upload-btn' class='button'>Test Media Library</button>";
echo "<div id='test-result' style='margin-top: 10px;'></div>";

echo "<script>
document.getElementById('test-upload-btn').addEventListener('click', function() {
    var resultDiv = document.getElementById('test-result');
    
    if (typeof wp === 'undefined') {
        resultDiv.innerHTML = '<p style=\"color: red;\">❌ wp is undefined - WordPress not loaded</p>';
        return;
    }
    
    if (typeof wp.media === 'undefined') {
        resultDiv.innerHTML = '<p style=\"color: red;\">❌ wp.media is undefined - Media library not loaded</p>';
        return;
    }
    
    resultDiv.innerHTML = '<p style=\"color: green;\">✅ wp.media is available! Opening...</p>';
    
    var frame = wp.media({
        title: 'Test Media Library',
        button: { text: 'Select' },
        multiple: false
    });
    
    frame.on('select', function() {
        var attachment = frame.state().get('selection').first().toJSON();
        resultDiv.innerHTML = '<p style=\"color: green;\">✅ Image selected: ' + attachment.url + '</p>';
        resultDiv.innerHTML += '<img src=\"' + attachment.url + '\" style=\"max-width: 200px; margin-top: 10px;\">';
    });
    
    frame.open();
});
</script>";

echo "<hr>";
echo "<p><a href='" . admin_url('admin.php?page=woow-admin&tab=login') . "' class='button button-primary'>Go to Login Page Tab</a></p>";
