<?php
/**
 * Test Template REST API Endpoints
 *
 * This file tests the template REST API endpoints to ensure they work correctly.
 * 
 * Usage:
 * 1. Place this file in the woow-admin plugin directory
 * 2. Access via: /wp-content/plugins/woow-admin/test-template-api.php
 * 3. Or run from command line: php test-template-api.php
 *
 * @package WoowAdmin
 */

// Load WordPress
require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';

// Check if user is admin
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'You do not have permission to access this page.' );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>WOOW! Admin - Template API Test</title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
			max-width: 1200px;
			margin: 40px auto;
			padding: 20px;
			background: #f5f5f5;
		}
		.container {
			background: white;
			border-radius: 8px;
			padding: 30px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		h1 {
			color: #333;
			border-bottom: 3px solid #6366f1;
			padding-bottom: 10px;
		}
		h2 {
			color: #6366f1;
			margin-top: 30px;
		}
		.test-section {
			margin: 20px 0;
			padding: 20px;
			background: #f9fafb;
			border-radius: 6px;
			border-left: 4px solid #6366f1;
		}
		.endpoint {
			font-family: 'Courier New', monospace;
			background: #1e293b;
			color: #10b981;
			padding: 8px 12px;
			border-radius: 4px;
			display: inline-block;
			margin: 10px 0;
		}
		.method {
			display: inline-block;
			padding: 4px 8px;
			border-radius: 4px;
			font-weight: bold;
			font-size: 12px;
			margin-right: 8px;
		}
		.method.get { background: #10b981; color: white; }
		.method.post { background: #f59e0b; color: white; }
		button {
			background: #6366f1;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 6px;
			cursor: pointer;
			font-size: 14px;
			font-weight: 600;
			margin: 5px;
		}
		button:hover {
			background: #4f46e5;
		}
		button:disabled {
			background: #9ca3af;
			cursor: not-allowed;
		}
		.result {
			margin-top: 15px;
			padding: 15px;
			background: #1e293b;
			color: #e2e8f0;
			border-radius: 6px;
			font-family: 'Courier New', monospace;
			font-size: 13px;
			white-space: pre-wrap;
			word-wrap: break-word;
			max-height: 400px;
			overflow-y: auto;
		}
		.success {
			border-left: 4px solid #10b981;
		}
		.error {
			border-left: 4px solid #ef4444;
		}
		.info {
			background: #dbeafe;
			border-left: 4px solid #3b82f6;
			padding: 15px;
			margin: 15px 0;
			border-radius: 4px;
			color: #1e40af;
		}
		.template-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
			gap: 15px;
			margin-top: 15px;
		}
		.template-card {
			background: white;
			border: 2px solid #e5e7eb;
			border-radius: 8px;
			padding: 15px;
			cursor: pointer;
			transition: all 0.2s;
		}
		.template-card:hover {
			border-color: #6366f1;
			box-shadow: 0 4px 6px rgba(99, 102, 241, 0.1);
		}
		.template-card.selected {
			border-color: #6366f1;
			background: #f0f9ff;
		}
		.template-name {
			font-weight: 600;
			color: #1f2937;
			margin-bottom: 5px;
		}
		.template-id {
			font-size: 12px;
			color: #6b7280;
			font-family: monospace;
		}
		.loading {
			display: inline-block;
			width: 16px;
			height: 16px;
			border: 3px solid #f3f4f6;
			border-top-color: #6366f1;
			border-radius: 50%;
			animation: spin 0.8s linear infinite;
			margin-left: 8px;
		}
		@keyframes spin {
			to { transform: rotate(360deg); }
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>🎨 WOOW! Admin - Template REST API Test</h1>
		
		<div class="info">
			<strong>ℹ️ API Base URL:</strong> <?php echo esc_html( rest_url( 'woow/v1' ) ); ?>
		</div>

		<!-- Test 1: Get All Templates -->
		<div class="test-section">
			<h2>1. Get All Templates</h2>
			<div>
				<span class="method get">GET</span>
				<span class="endpoint">/wp-json/woow/v1/templates</span>
			</div>
			<p>Retrieves all available design templates with metadata.</p>
			<button onclick="testGetTemplates()">Run Test</button>
			<div id="result-get-templates" class="result" style="display:none;"></div>
			<div id="templates-grid" class="template-grid" style="display:none;"></div>
		</div>

		<!-- Test 2: Get Single Template -->
		<div class="test-section">
			<h2>2. Get Single Template</h2>
			<div>
				<span class="method get">GET</span>
				<span class="endpoint">/wp-json/woow/v1/templates/{id}</span>
			</div>
			<p>Retrieves a specific template by ID with full details.</p>
			<input type="text" id="template-id-get" placeholder="e.g., modern_minimal" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; width: 300px;">
			<button onclick="testGetTemplate()">Run Test</button>
			<div id="result-get-template" class="result" style="display:none;"></div>
		</div>

		<!-- Test 3: Apply Template -->
		<div class="test-section">
			<h2>3. Apply Template</h2>
			<div>
				<span class="method post">POST</span>
				<span class="endpoint">/wp-json/woow/v1/templates/{id}/apply</span>
			</div>
			<p>Applies a template to the current settings. Creates a backup before applying.</p>
			<div class="info">
				⚠️ <strong>Warning:</strong> This will modify your current settings. A backup will be created automatically.
			</div>
			<input type="text" id="template-id-apply" placeholder="e.g., modern_minimal" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; width: 300px;">
			<button onclick="testApplyTemplate()">Apply Template</button>
			<div id="result-apply-template" class="result" style="display:none;"></div>
		</div>

		<!-- Test 4: Error Handling -->
		<div class="test-section">
			<h2>4. Error Handling Tests</h2>
			<p>Test various error scenarios:</p>
			<button onclick="testNonExistentTemplate()">Test Non-Existent Template</button>
			<button onclick="testInvalidTemplateId()">Test Invalid Template ID</button>
			<div id="result-error-tests" class="result" style="display:none;"></div>
		</div>
	</div>

	<script>
		const apiUrl = '<?php echo esc_js( rest_url( 'woow/v1' ) ); ?>';
		const nonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';

		// Helper function to make API requests
		async function apiRequest(endpoint, method = 'GET', body = null) {
			const options = {
				method: method,
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': nonce
				}
			};

			if (body) {
				options.body = JSON.stringify(body);
			}

			const response = await fetch(apiUrl + endpoint, options);
			const data = await response.json();
			
			return {
				status: response.status,
				data: data
			};
		}

		// Helper function to display results
		function displayResult(elementId, result, isError = false) {
			const element = document.getElementById(elementId);
			element.style.display = 'block';
			element.className = 'result ' + (isError ? 'error' : 'success');
			element.textContent = JSON.stringify(result, null, 2);
		}

		// Test 1: Get All Templates
		async function testGetTemplates() {
			const btn = event.target;
			btn.disabled = true;
			btn.innerHTML = 'Loading<span class="loading"></span>';

			try {
				const result = await apiRequest('templates');
				displayResult('result-get-templates', result);

				// Display templates in grid
				if (result.data.success && result.data.templates) {
					const grid = document.getElementById('templates-grid');
					grid.style.display = 'grid';
					grid.innerHTML = result.data.templates.map(template => `
						<div class="template-card" onclick="selectTemplate('${template.id}')">
							<div class="template-name">${template.name}</div>
							<div class="template-id">${template.id}</div>
						</div>
					`).join('');
				}
			} catch (error) {
				displayResult('result-get-templates', { error: error.message }, true);
			} finally {
				btn.disabled = false;
				btn.textContent = 'Run Test';
			}
		}

		// Helper to select template
		function selectTemplate(templateId) {
			document.getElementById('template-id-get').value = templateId;
			document.getElementById('template-id-apply').value = templateId;
			
			// Highlight selected
			document.querySelectorAll('.template-card').forEach(card => {
				card.classList.remove('selected');
			});
			event.target.closest('.template-card').classList.add('selected');
		}

		// Test 2: Get Single Template
		async function testGetTemplate() {
			const templateId = document.getElementById('template-id-get').value;
			if (!templateId) {
				alert('Please enter a template ID');
				return;
			}

			const btn = event.target;
			btn.disabled = true;
			btn.innerHTML = 'Loading<span class="loading"></span>';

			try {
				const result = await apiRequest('templates/' + templateId);
				displayResult('result-get-template', result, !result.data.success);
			} catch (error) {
				displayResult('result-get-template', { error: error.message }, true);
			} finally {
				btn.disabled = false;
				btn.textContent = 'Run Test';
			}
		}

		// Test 3: Apply Template
		async function testApplyTemplate() {
			const templateId = document.getElementById('template-id-apply').value;
			if (!templateId) {
				alert('Please enter a template ID');
				return;
			}

			if (!confirm(`Are you sure you want to apply template "${templateId}"? A backup will be created.`)) {
				return;
			}

			const btn = event.target;
			btn.disabled = true;
			btn.innerHTML = 'Applying<span class="loading"></span>';

			try {
				const result = await apiRequest('templates/' + templateId + '/apply', 'POST');
				displayResult('result-apply-template', result, !result.data.success);

				if (result.data.success) {
					alert('Template applied successfully! The page will reload to show changes.');
					setTimeout(() => window.location.reload(), 2000);
				}
			} catch (error) {
				displayResult('result-apply-template', { error: error.message }, true);
			} finally {
				btn.disabled = false;
				btn.textContent = 'Apply Template';
			}
		}

		// Test 4: Non-Existent Template
		async function testNonExistentTemplate() {
			const btn = event.target;
			btn.disabled = true;

			try {
				const result = await apiRequest('templates/non_existent_template_xyz');
				displayResult('result-error-tests', result, true);
			} catch (error) {
				displayResult('result-error-tests', { error: error.message }, true);
			} finally {
				btn.disabled = false;
			}
		}

		// Test 5: Invalid Template ID
		async function testInvalidTemplateId() {
			const btn = event.target;
			btn.disabled = true;

			try {
				const result = await apiRequest('templates/invalid@template#id!');
				displayResult('result-error-tests', result, true);
			} catch (error) {
				displayResult('result-error-tests', { error: error.message }, true);
			} finally {
				btn.disabled = false;
			}
		}
	</script>
</body>
</html>
