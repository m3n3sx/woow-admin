<?php
/**
 * Template Preview Image Generation Helper
 *
 * This script helps generate preview images for all 11 WOOW! Admin design templates.
 * Access this file directly in your browser to use the interactive helper.
 *
 * URL: http://your-site.local/wp-content/plugins/woow-admin/generate-template-previews.php
 *
 * @package WOOW_Admin
 * @since 1.0.0
 */

// Load WordPress
require_once '../../../wp-load.php';

// Security check
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'You do not have permission to access this page.' );
}

// Load plugin files
require_once __DIR__ . '/includes/class-woow-settings.php';
require_once __DIR__ . '/includes/class-woow-template-manager.php';

// Initialize managers
$settings = new WOOW_Settings();
$template_manager = new WOOW_Template_Manager( $settings );

// Get all templates
$templates = $template_manager->get_all_templates();

// Handle template application
$applied_template = null;
$application_result = null;

if ( isset( $_POST['apply_template'] ) && isset( $_POST['template_id'] ) ) {
	check_admin_referer( 'woow_apply_template' );
	
	$template_id = sanitize_key( $_POST['template_id'] );
	$result = $template_manager->apply_template( $template_id );
	
	if ( $result ) {
		$applied_template = $template_id;
		$application_result = 'success';
	} else {
		$application_result = 'error';
	}
}

// Check which previews exist
$preview_dir = __DIR__ . '/assets/images/previews/templates/';
$existing_previews = array();
if ( is_dir( $preview_dir ) ) {
	$files = scandir( $preview_dir );
	foreach ( $files as $file ) {
		if ( pathinfo( $file, PATHINFO_EXTENSION ) === 'png' ) {
			$existing_previews[] = pathinfo( $file, PATHINFO_FILENAME );
		}
	}
}

// Calculate progress
$total_templates = count( $templates );
$completed_previews = count( $existing_previews );
$progress_percentage = $total_templates > 0 ? round( ( $completed_previews / $total_templates ) * 100 ) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>WOOW! Admin - Template Preview Generator</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		
		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			min-height: 100vh;
			padding: 40px 20px;
		}
		
		.container {
			max-width: 1200px;
			margin: 0 auto;
		}
		
		.header {
			background: white;
			border-radius: 20px;
			padding: 40px;
			margin-bottom: 30px;
			box-shadow: 0 20px 60px rgba(0,0,0,0.3);
		}
		
		.header h1 {
			font-size: 36px;
			color: #1a202c;
			margin-bottom: 10px;
		}
		
		.header p {
			color: #718096;
			font-size: 16px;
			line-height: 1.6;
		}
		
		.progress-section {
			background: white;
			border-radius: 20px;
			padding: 30px;
			margin-bottom: 30px;
			box-shadow: 0 20px 60px rgba(0,0,0,0.3);
		}
		
		.progress-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}
		
		.progress-header h2 {
			font-size: 24px;
			color: #1a202c;
		}
		
		.progress-stats {
			font-size: 18px;
			color: #667eea;
			font-weight: 600;
		}
		
		.progress-bar-container {
			background: #e2e8f0;
			border-radius: 10px;
			height: 20px;
			overflow: hidden;
		}
		
		.progress-bar {
			background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
			height: 100%;
			transition: width 0.3s ease;
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-size: 12px;
			font-weight: 600;
		}
		
		.instructions {
			background: #fff3cd;
			border: 2px solid #ffc107;
			border-radius: 15px;
			padding: 25px;
			margin-bottom: 30px;
		}
		
		.instructions h3 {
			color: #856404;
			margin-bottom: 15px;
			font-size: 20px;
		}
		
		.instructions ol {
			margin-left: 20px;
			color: #856404;
		}
		
		.instructions li {
			margin-bottom: 10px;
			line-height: 1.6;
		}
		
		.instructions code {
			background: #fff;
			padding: 2px 6px;
			border-radius: 4px;
			font-family: 'Courier New', monospace;
			font-size: 14px;
		}
		
		.templates-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
			gap: 25px;
		}
		
		.template-card {
			background: white;
			border-radius: 15px;
			padding: 25px;
			box-shadow: 0 10px 30px rgba(0,0,0,0.2);
			transition: transform 0.2s, box-shadow 0.2s;
		}
		
		.template-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 15px 40px rgba(0,0,0,0.3);
		}
		
		.template-card.completed {
			border: 3px solid #10b981;
		}
		
		.template-header {
			display: flex;
			justify-content: space-between;
			align-items: start;
			margin-bottom: 15px;
		}
		
		.template-info h3 {
			font-size: 20px;
			color: #1a202c;
			margin-bottom: 5px;
		}
		
		.template-info p {
			color: #718096;
			font-size: 14px;
			line-height: 1.5;
		}
		
		.status-badge {
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 600;
			white-space: nowrap;
		}
		
		.status-badge.completed {
			background: #d1fae5;
			color: #065f46;
		}
		
		.status-badge.pending {
			background: #fee2e2;
			color: #991b1b;
		}
		
		.template-meta {
			display: flex;
			gap: 10px;
			margin-bottom: 15px;
			flex-wrap: wrap;
		}
		
		.meta-tag {
			background: #f3f4f6;
			padding: 4px 10px;
			border-radius: 6px;
			font-size: 12px;
			color: #4b5563;
		}
		
		.template-actions {
			display: flex;
			gap: 10px;
		}
		
		.btn {
			flex: 1;
			padding: 12px 20px;
			border: none;
			border-radius: 10px;
			font-size: 14px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s;
			text-decoration: none;
			display: inline-block;
			text-align: center;
		}
		
		.btn-primary {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
		}
		
		.btn-primary:hover {
			transform: scale(1.05);
			box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
		}
		
		.btn-secondary {
			background: #e2e8f0;
			color: #1a202c;
		}
		
		.btn-secondary:hover {
			background: #cbd5e0;
		}
		
		.btn:disabled {
			opacity: 0.5;
			cursor: not-allowed;
		}
		
		.alert {
			padding: 20px;
			border-radius: 10px;
			margin-bottom: 20px;
			font-weight: 500;
		}
		
		.alert-success {
			background: #d1fae5;
			color: #065f46;
			border: 2px solid #10b981;
		}
		
		.alert-error {
			background: #fee2e2;
			color: #991b1b;
			border: 2px solid #ef4444;
		}
		
		.filename {
			font-family: 'Courier New', monospace;
			background: #f3f4f6;
			padding: 8px 12px;
			border-radius: 6px;
			font-size: 13px;
			margin-top: 10px;
			color: #4b5563;
		}
	</style>
</head>
<body>
	<div class="container">
		<!-- Header -->
		<div class="header">
			<h1>🎨 Template Preview Generator</h1>
			<p>Generate preview images for all 11 WOOW! Admin design templates. Each preview should be 1200x800px and showcase the template applied to the WordPress admin interface.</p>
		</div>
		
		<!-- Progress Section -->
		<div class="progress-section">
			<div class="progress-header">
				<h2>Generation Progress</h2>
				<div class="progress-stats">
					<?php echo $completed_previews; ?> / <?php echo $total_templates; ?> Complete
				</div>
			</div>
			<div class="progress-bar-container">
				<div class="progress-bar" style="width: <?php echo $progress_percentage; ?>%;">
					<?php echo $progress_percentage; ?>%
				</div>
			</div>
		</div>
		
		<!-- Instructions -->
		<div class="instructions">
			<h3>📋 How to Generate Previews</h3>
			<ol>
				<li>Click <strong>"Apply & Screenshot"</strong> for a template</li>
				<li>Open browser DevTools (F12) and set viewport to <code>1200x800px</code></li>
				<li>Navigate to <code>wp-admin/index.php</code> (Dashboard)</li>
				<li>Take a screenshot (Ctrl+Shift+S in Firefox, or use DevTools screenshot tool)</li>
				<li>Save the image with the exact filename shown below</li>
				<li>Place it in <code>woow-admin/assets/images/previews/templates/</code></li>
				<li>Refresh this page to see progress update</li>
				<li>Repeat for all templates</li>
			</ol>
		</div>
		
		<?php if ( $application_result === 'success' ) : ?>
			<div class="alert alert-success">
				✅ Template "<?php echo esc_html( $applied_template ); ?>" applied successfully! Now take a screenshot at 1200x800px.
			</div>
		<?php elseif ( $application_result === 'error' ) : ?>
			<div class="alert alert-error">
				❌ Failed to apply template. Please try again or check error logs.
			</div>
		<?php endif; ?>
		
		<!-- Templates Grid -->
		<div class="templates-grid">
			<?php foreach ( $templates as $template_id => $template ) : 
				$is_completed = in_array( $template_id, $existing_previews );
				$preview_filename = $template['preview_image'] ?? $template_id . '.png';
			?>
				<div class="template-card <?php echo $is_completed ? 'completed' : ''; ?>">
					<div class="template-header">
						<div class="template-info">
							<h3><?php echo esc_html( $template['name'] ); ?></h3>
							<p><?php echo esc_html( $template['description'] ); ?></p>
						</div>
						<span class="status-badge <?php echo $is_completed ? 'completed' : 'pending'; ?>">
							<?php echo $is_completed ? '✓ Done' : 'Pending'; ?>
						</span>
					</div>
					
					<div class="template-meta">
						<span class="meta-tag">📁 <?php echo esc_html( $template['category'] ); ?></span>
						<?php if ( isset( $template['characteristics']['glassmorphism'] ) && $template['characteristics']['glassmorphism'] ) : ?>
							<span class="meta-tag">✨ Glass</span>
						<?php endif; ?>
						<?php if ( isset( $template['characteristics']['gradients'] ) && $template['characteristics']['gradients'] ) : ?>
							<span class="meta-tag">🌈 Gradients</span>
						<?php endif; ?>
					</div>
					
					<div class="filename">
						📄 <?php echo esc_html( $preview_filename ); ?>
					</div>
					
					<div class="template-actions" style="margin-top: 15px;">
						<form method="post" style="flex: 1;">
							<?php wp_nonce_field( 'woow_apply_template' ); ?>
							<input type="hidden" name="template_id" value="<?php echo esc_attr( $template_id ); ?>">
							<button type="submit" name="apply_template" class="btn btn-primary" <?php echo $is_completed ? 'disabled' : ''; ?>>
								<?php echo $is_completed ? '✓ Applied' : '🎨 Apply & Screenshot'; ?>
							</button>
						</form>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</body>
</html>
