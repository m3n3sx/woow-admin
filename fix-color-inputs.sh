#!/bin/bash
# Fix all color input fields in tab templates

cd "$(dirname "$0")/includes/templates/tabs"

# Function to fix color inputs in a file
fix_file() {
    local file="$1"
    echo "Fixing $file..."
    
    # Backup
    cp "$file" "$file.bak"
    
    # Fix pattern: value="<?php echo esc_attr( $var['key'] ); ?>"
    # To: value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $var['key'] ?? '#default' ) ); ?>"
    
    sed -i 's/value="<?php echo esc_attr( \$buttons\[\x27primary_bg\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons[\x27primary_bg\x27] ?? \x27#6366f1\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$buttons\[\x27primary_text\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons[\x27primary_text\x27] ?? \x27#ffffff\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$buttons\[\x27secondary_border\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons[\x27secondary_border\x27] ?? \x27#e2e8f0\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$buttons\[\x27secondary_text\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons[\x27secondary_text\x27] ?? \x27#6366f1\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$buttons\[\x27destructive_bg\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons[\x27destructive_bg\x27] ?? \x27#ef4444\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$buttons\[\x27destructive_text\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons[\x27destructive_text\x27] ?? \x27#ffffff\x27 ) ); ?>"/g' "$file"
    
    sed -i 's/value="<?php echo esc_attr( \$forms\[\x27background_color\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $forms[\x27background_color\x27] ?? \x27#ffffff\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$forms\[\x27border_color\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $forms[\x27border_color\x27] ?? \x27#e2e8f0\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$forms\[\x27text_color\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $forms[\x27text_color\x27] ?? \x27#0f172a\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$forms\[\x27focus_ring_color\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $forms[\x27focus_ring_color\x27] ?? \x27#6366f1\x27 ) ); ?>"/g' "$file"
    
    sed -i 's/value="<?php echo esc_attr( \$backgrounds\[\x27gradient_start\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $backgrounds[\x27gradient_start\x27] ?? \x27#f8fafc\x27 ) ); ?>"/g' "$file"
    sed -i 's/value="<?php echo esc_attr( \$backgrounds\[\x27gradient_end\x27\] ); ?>"/value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $backgrounds[\x27gradient_end\x27] ?? \x27#eef2ff\x27 ) ); ?>"/g' "$file"
    
    echo "Fixed $file"
}

# Fix all tab files with color inputs
fix_file "buttons-tab.php"
fix_file "forms-tab.php"
fix_file "backgrounds-tab.php"

echo "Done! All color inputs fixed."
echo "Backups saved as *.bak"
