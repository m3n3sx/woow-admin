<?php
/**
 * Test Upload Button - Complete Diagnostic
 */

// Load WordPress
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Enqueue WordPress media
wp_enqueue_media();

// Get plugin URL
$plugin_url = plugins_url('', __FILE__);
$assets_url = $plugin_url . '/assets/dist/';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Upload Button</title>
    <meta charset="UTF-8">
    <?php wp_head(); ?>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
        .test-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .button { padding: 10px 20px; background: #0073aa; color: white; border: none; cursor: pointer; border-radius: 4px; }
        .button:hover { background: #005177; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🧪 Upload Button Test</h1>
    
    <div class="test-box">
        <h2>1️⃣ Check WordPress Media Library</h2>
        <button onclick="checkWpMedia()" class="button">Check wp.media</button>
        <div id="wp-media-result"></div>
    </div>
    
    <div class="test-box">
        <h2>2️⃣ Check WOOW! Admin JavaScript</h2>
        <button onclick="checkWoowAdmin()" class="button">Check WOOW Admin</button>
        <div id="woow-admin-result"></div>
    </div>
    
    <div class="test-box">
        <h2>3️⃣ Test Upload Button (WordPress Media Library)</h2>
        <p>This should open WordPress Media Library:</p>
        <input type="hidden" id="test-logo-url" value="" />
        <button type="button" class="button woow-upload-image" data-target="#test-logo-url">
            📤 Upload Logo
        </button>
        <div id="upload-result" style="margin-top: 10px;"></div>
        <div id="upload-preview"></div>
    </div>
    
    <div class="test-box">
        <h2>4️⃣ Manual Test (Direct wp.media call)</h2>
        <button onclick="manualUpload()" class="button">Open Media Library Manually</button>
        <div id="manual-result"></div>
    </div>
    
    <div class="test-box">
        <h2>5️⃣ Console Logs</h2>
        <p>Open browser console (F12) and look for:</p>
        <pre>
[MediaUploader] Initialized
[MediaUploader] Image selected: ...
        </pre>
        <button onclick="showConsoleInstructions()" class="button">Show Instructions</button>
        <div id="console-instructions"></div>
    </div>
    
    <script>
        console.log('=== WOOW Upload Test Started ===');
        
        function checkWpMedia() {
            const result = document.getElementById('wp-media-result');
            let html = '<h3>Results:</h3><ul>';
            
            html += '<li><strong>typeof wp:</strong> ' + typeof wp;
            if (typeof wp !== 'undefined') {
                html += ' <span class="success">✅</span>';
            } else {
                html += ' <span class="error">❌</span>';
            }
            html += '</li>';
            
            if (typeof wp !== 'undefined') {
                html += '<li><strong>typeof wp.media:</strong> ' + typeof wp.media;
                if (typeof wp.media !== 'undefined') {
                    html += ' <span class="success">✅</span>';
                } else {
                    html += ' <span class="error">❌</span>';
                }
                html += '</li>';
            }
            
            html += '</ul>';
            
            if (typeof wp !== 'undefined' && typeof wp.media !== 'undefined') {
                html += '<p class="success">✅ WordPress Media Library is available!</p>';
            } else {
                html += '<p class="error">❌ WordPress Media Library is NOT available!</p>';
                html += '<p>Make sure wp_enqueue_media() is called.</p>';
            }
            
            result.innerHTML = html;
        }
        
        function checkWoowAdmin() {
            const result = document.getElementById('woow-admin-result');
            let html = '<h3>Results:</h3><ul>';
            
            // Check if main.js is loaded
            const scripts = Array.from(document.scripts);
            const mainJs = scripts.find(s => s.src.includes('main.js'));
            
            html += '<li><strong>main.js loaded:</strong> ';
            if (mainJs) {
                html += 'YES <span class="success">✅</span>';
                html += '<br><small>' + mainJs.src + '</small>';
            } else {
                html += 'NO <span class="error">❌</span>';
            }
            html += '</li>';
            
            // Check for MediaUploader in code
            html += '<li><strong>MediaUploader in code:</strong> ';
            fetch('<?php echo $assets_url; ?>main.js')
                .then(r => r.text())
                .then(code => {
                    if (code.includes('MediaUploader')) {
                        document.getElementById('woow-admin-result').innerHTML += '<span class="success">✅ Found</span></li></ul>';
                    } else {
                        document.getElementById('woow-admin-result').innerHTML += '<span class="error">❌ Not found</span></li></ul>';
                    }
                });
            html += '<span class="info">Checking...</span>';
            html += '</li>';
            
            html += '</ul>';
            result.innerHTML = html;
        }
        
        function manualUpload() {
            const result = document.getElementById('manual-result');
            
            if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
                result.innerHTML = '<p class="error">❌ wp.media not available</p>';
                return;
            }
            
            result.innerHTML = '<p class="info">⏳ Opening Media Library...</p>';
            
            const frame = wp.media({
                title: 'Select Image',
                button: { text: 'Use this image' },
                multiple: false,
                library: { type: 'image' }
            });
            
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                result.innerHTML = '<p class="success">✅ Image selected!</p>';
                result.innerHTML += '<p><strong>URL:</strong> ' + attachment.url + '</p>';
                result.innerHTML += '<img src="' + attachment.url + '" style="max-width: 300px; margin-top: 10px; border-radius: 8px;">';
            });
            
            frame.open();
        }
        
        function showConsoleInstructions() {
            const div = document.getElementById('console-instructions');
            div.innerHTML = `
                <h4>How to check console:</h4>
                <ol>
                    <li>Press <strong>F12</strong> to open Developer Tools</li>
                    <li>Click on <strong>Console</strong> tab</li>
                    <li>Look for messages starting with <code>[MediaUploader]</code></li>
                    <li>Click the upload button above</li>
                    <li>You should see: <code>[MediaUploader] Image selected: ...</code></li>
                </ol>
            `;
        }
        
        // Monitor upload button clicks
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('woow-upload-image')) {
                console.log('[TEST] Upload button clicked!');
                const result = document.getElementById('upload-result');
                result.innerHTML = '<p class="info">⏳ Button clicked! Checking MediaUploader...</p>';
                
                setTimeout(() => {
                    if (typeof wp !== 'undefined' && typeof wp.media !== 'undefined') {
                        result.innerHTML += '<p class="success">✅ wp.media is available</p>';
                    } else {
                        result.innerHTML += '<p class="error">❌ wp.media is NOT available</p>';
                    }
                }, 100);
            }
        });
        
        // Monitor hidden input changes
        document.getElementById('test-logo-url').addEventListener('change', function(e) {
            console.log('[TEST] Logo URL changed:', e.target.value);
            const preview = document.getElementById('upload-preview');
            if (e.target.value) {
                preview.innerHTML = '<p class="success">✅ Image URL set!</p>';
                preview.innerHTML += '<p><strong>URL:</strong> ' + e.target.value + '</p>';
                preview.innerHTML += '<img src="' + e.target.value + '" style="max-width: 300px; margin-top: 10px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
            }
        });
        
        // Auto-check on load
        window.addEventListener('load', function() {
            console.log('[TEST] Page loaded');
            console.log('[TEST] wp:', typeof wp);
            console.log('[TEST] wp.media:', typeof wp !== 'undefined' ? typeof wp.media : 'N/A');
            
            setTimeout(() => {
                checkWpMedia();
            }, 500);
        });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html>
