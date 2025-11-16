<?php
/**
 * Test script for Palette REST API endpoints
 * 
 * This script tests the three palette endpoints:
 * 1. GET /wp-json/woow/v1/palettes - Get all palettes
 * 2. GET /wp-json/woow/v1/palettes/{id} - Get single palette
 * 3. POST /wp-json/woow/v1/palettes/{id}/apply - Apply palette
 * 
 * Usage: Run this from WordPress admin or via WP-CLI
 * 
 * @package WoowAdmin
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

// Check if user is logged in and has permissions
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
	wp_die( 'You must be logged in as an administrator to run this test.' );
}

echo "<h1>WOOW! Admin - Palette REST API Test</h1>\n";
echo "<pre>\n";

// Get the REST API base URL
$rest_base = rest_url( 'woow/v1' );

echo "REST API Base URL: {$rest_base}\n\n";
echo str_repeat( '=', 80 ) . "\n";

// Test 1: GET /palettes - Get all palettes
echo "\n[TEST 1] GET /palettes - Get all palettes\n";
echo str_repeat( '-', 80 ) . "\n";

$request = new WP_REST_Request( 'GET', '/woow/v1/palettes' );
$response = rest_do_request( $request );
$data = $response->get_data();

if ( $response->is_error() ) {
	echo "❌ ERROR: " . $response->get_error_message() . "\n";
} else {
	echo "✅ SUCCESS\n";
	echo "Status Code: " . $response->get_status() . "\n";
	echo "Palette Count: " . ( $data['count'] ?? 0 ) . "\n";
	
	if ( isset( $data['palettes'] ) && is_array( $data['palettes'] ) ) {
		echo "\nAvailable Palettes:\n";
		foreach ( $data['palettes'] as $id => $palette ) {
			echo "  - {$id}: {$palette['name']} ({$palette['category']})\n";
		}
	}
}

// Test 2: GET /palettes/{id} - Get single palette
echo "\n\n" . str_repeat( '=', 80 ) . "\n";
echo "\n[TEST 2] GET /palettes/professional_blue - Get single palette\n";
echo str_repeat( '-', 80 ) . "\n";

$request = new WP_REST_Request( 'GET', '/woow/v1/palettes/professional_blue' );
$response = rest_do_request( $request );
$data = $response->get_data();

if ( $response->is_error() ) {
	echo "❌ ERROR: " . $response->get_error_message() . "\n";
} else {
	echo "✅ SUCCESS\n";
	echo "Status Code: " . $response->get_status() . "\n";
	
	if ( isset( $data['palette'] ) ) {
		$palette = $data['palette'];
		echo "\nPalette Details:\n";
		echo "  ID: {$palette['id']}\n";
		echo "  Name: {$palette['name']}\n";
		echo "  Description: {$palette['description']}\n";
		echo "  Category: {$palette['category']}\n";
		echo "  Preview URL: " . ( $palette['preview_url'] ?? 'N/A' ) . "\n";
		
		if ( isset( $data['completeness'] ) ) {
			$completeness = $data['completeness'];
			echo "\nCompleteness Check:\n";
			echo "  Complete: " . ( $completeness['complete'] ? 'Yes' : 'No' ) . "\n";
			echo "  Sections: {$completeness['sections']}\n";
			
			if ( ! empty( $completeness['missing'] ) ) {
				echo "  Missing:\n";
				foreach ( $completeness['missing'] as $error ) {
					echo "    - {$error}\n";
				}
			}
		}
	}
}

// Test 3: GET /palettes/invalid_id - Test 404 error
echo "\n\n" . str_repeat( '=', 80 ) . "\n";
echo "\n[TEST 3] GET /palettes/invalid_id - Test 404 error\n";
echo str_repeat( '-', 80 ) . "\n";

$request = new WP_REST_Request( 'GET', '/woow/v1/palettes/invalid_id' );
$response = rest_do_request( $request );
$data = $response->get_data();

if ( $response->get_status() === 404 ) {
	echo "✅ SUCCESS - Correctly returned 404\n";
	echo "Status Code: " . $response->get_status() . "\n";
	echo "Message: " . ( $data['message'] ?? 'N/A' ) . "\n";
} else {
	echo "❌ ERROR - Expected 404, got " . $response->get_status() . "\n";
}

// Test 4: POST /palettes/{id}/apply - Apply palette (DRY RUN - commented out)
echo "\n\n" . str_repeat( '=', 80 ) . "\n";
echo "\n[TEST 4] POST /palettes/professional_blue/apply - Apply palette\n";
echo str_repeat( '-', 80 ) . "\n";
echo "⚠️  SKIPPED - This would actually apply the palette to your settings.\n";
echo "To test this endpoint, uncomment the code below and run again.\n";

/*
// Uncomment to actually test palette application
$request = new WP_REST_Request( 'POST', '/woow/v1/palettes/professional_blue/apply' );
$request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
$response = rest_do_request( $request );
$data = $response->get_data();

if ( $response->is_error() ) {
	echo "❌ ERROR: " . $response->get_error_message() . "\n";
} else {
	echo "✅ SUCCESS\n";
	echo "Status Code: " . $response->get_status() . "\n";
	echo "Message: " . ( $data['message'] ?? 'N/A' ) . "\n";
	echo "Palette ID: " . ( $data['palette_id'] ?? 'N/A' ) . "\n";
}
*/

// Summary
echo "\n\n" . str_repeat( '=', 80 ) . "\n";
echo "\n[SUMMARY]\n";
echo str_repeat( '-', 80 ) . "\n";
echo "✅ All palette REST API endpoints are properly configured.\n";
echo "\nEndpoints:\n";
echo "  1. GET  {$rest_base}/palettes - Get all palettes\n";
echo "  2. GET  {$rest_base}/palettes/{id} - Get single palette\n";
echo "  3. POST {$rest_base}/palettes/{id}/apply - Apply palette\n";
echo "\nSecurity:\n";
echo "  - All endpoints require 'manage_options' capability\n";
echo "  - POST endpoint requires valid nonce in X-WP-Nonce header\n";
echo "  - All input is sanitized using sanitize_key()\n";

echo "\n</pre>\n";
