<?php
/**
 * Test AJAX endpoint
 */

// Load WordPress
require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';

// Check if user is logged in
if ( ! is_user_logged_in() ) {
    die( 'Not logged in' );
}

// Check if user can manage options
if ( ! current_user_can( 'manage_options' ) ) {
    die( 'No permissions' );
}

echo "✅ User logged in and has permissions\n\n";

// Test nonce
$nonce = wp_create_nonce( 'woow_admin_nonce' );
echo "Generated nonce: {$nonce}\n\n";

// Check if nonce from JS matches
if ( isset( $_GET['test_nonce'] ) ) {
    $test_nonce = $_GET['test_nonce'];
    echo "Received nonce: {$test_nonce}\n";
    
    if ( wp_verify_nonce( $test_nonce, 'woow_admin_nonce' ) ) {
        echo "✅ Nonce is VALID\n";
    } else {
        echo "❌ Nonce is INVALID\n";
    }
}

// Show current woowAdminData
echo "\n\nCurrent woowAdminData.nonce from page:\n";
echo "Open browser console and type: woowAdminData.nonce\n";
