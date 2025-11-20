<?php
/**
 * WOOW_Google_Fonts Class
 *
 * Manages Google Fonts library and URL generation for typography customization.
 *
 * @package WoowAdmin
 * @since 2.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Google Fonts Manager Class
 */
class WOOW_Google_Fonts {
    /**
     * Font library with 50+ popular Google Fonts
     *
     * @var array
     */
    private const FONT_LIBRARY = [
        // Sans-Serif Fonts (Most Popular)
        'Inter' => [
            'category' => 'sans-serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Roboto' => [
            'category' => 'sans-serif',
            'weights' => [100, 300, 400, 500, 700, 900],
        ],
        'Open Sans' => [
            'category' => 'sans-serif',
            'weights' => [300, 400, 500, 600, 700, 800],
        ],
        'Lato' => [
            'category' => 'sans-serif',
            'weights' => [100, 300, 400, 700, 900],
        ],
        'Montserrat' => [
            'category' => 'sans-serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Poppins' => [
            'category' => 'sans-serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Raleway' => [
            'category' => 'sans-serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Nunito' => [
            'category' => 'sans-serif',
            'weights' => [200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Ubuntu' => [
            'category' => 'sans-serif',
            'weights' => [300, 400, 500, 700],
        ],
        'Work Sans' => [
            'category' => 'sans-serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Rubik' => [
            'category' => 'sans-serif',
            'weights' => [300, 400, 500, 600, 700, 800, 900],
        ],
        'Nunito Sans' => [
            'category' => 'sans-serif',
            'weights' => [200, 300, 400, 600, 700, 800, 900],
        ],
        'Source Sans Pro' => [
            'category' => 'sans-serif',
            'weights' => [200, 300, 400, 600, 700, 900],
        ],
        'Oswald' => [
            'category' => 'sans-serif',
            'weights' => [200, 300, 400, 500, 600, 700],
        ],
        'Mukta' => [
            'category' => 'sans-serif',
            'weights' => [200, 300, 400, 500, 600, 700, 800],
        ],
        'Barlow' => [
            'category' => 'sans-serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Quicksand' => [
            'category' => 'sans-serif',
            'weights' => [300, 400, 500, 600, 700],
        ],
        'Karla' => [
            'category' => 'sans-serif',
            'weights' => [200, 300, 400, 500, 600, 700, 800],
        ],
        'Oxygen' => [
            'category' => 'sans-serif',
            'weights' => [300, 400, 700],
        ],
        'Manrope' => [
            'category' => 'sans-serif',
            'weights' => [200, 300, 400, 500, 600, 700, 800],
        ],
        
        // Serif Fonts
        'Playfair Display' => [
            'category' => 'serif',
            'weights' => [400, 500, 600, 700, 800, 900],
        ],
        'Merriweather' => [
            'category' => 'serif',
            'weights' => [300, 400, 700, 900],
        ],
        'Lora' => [
            'category' => 'serif',
            'weights' => [400, 500, 600, 700],
        ],
        'PT Serif' => [
            'category' => 'serif',
            'weights' => [400, 700],
        ],
        'Crimson Text' => [
            'category' => 'serif',
            'weights' => [400, 600, 700],
        ],
        'Libre Baskerville' => [
            'category' => 'serif',
            'weights' => [400, 700],
        ],
        'Cormorant Garamond' => [
            'category' => 'serif',
            'weights' => [300, 400, 500, 600, 700],
        ],
        'EB Garamond' => [
            'category' => 'serif',
            'weights' => [400, 500, 600, 700, 800],
        ],
        'Spectral' => [
            'category' => 'serif',
            'weights' => [200, 300, 400, 500, 600, 700, 800],
        ],
        'Bitter' => [
            'category' => 'serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Cardo' => [
            'category' => 'serif',
            'weights' => [400, 700],
        ],
        'Alegreya' => [
            'category' => 'serif',
            'weights' => [400, 500, 600, 700, 800, 900],
        ],
        'Vollkorn' => [
            'category' => 'serif',
            'weights' => [400, 500, 600, 700, 800, 900],
        ],
        'Arvo' => [
            'category' => 'serif',
            'weights' => [400, 700],
        ],
        'Rokkitt' => [
            'category' => 'serif',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
        ],
        
        // Monospace Fonts
        'Roboto Mono' => [
            'category' => 'monospace',
            'weights' => [100, 200, 300, 400, 500, 600, 700],
        ],
        'Source Code Pro' => [
            'category' => 'monospace',
            'weights' => [200, 300, 400, 500, 600, 700, 900],
        ],
        'Fira Code' => [
            'category' => 'monospace',
            'weights' => [300, 400, 500, 600, 700],
        ],
        'JetBrains Mono' => [
            'category' => 'monospace',
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800],
        ],
        'IBM Plex Mono' => [
            'category' => 'monospace',
            'weights' => [100, 200, 300, 400, 500, 600, 700],
        ],
        'Space Mono' => [
            'category' => 'monospace',
            'weights' => [400, 700],
        ],
        'Inconsolata' => [
            'category' => 'monospace',
            'weights' => [200, 300, 400, 500, 600, 700, 800, 900],
        ],
        'Courier Prime' => [
            'category' => 'monospace',
            'weights' => [400, 700],
        ],
        
        // Handwriting/Display Fonts
        'Pacifico' => [
            'category' => 'handwriting',
            'weights' => [400],
        ],
        'Dancing Script' => [
            'category' => 'handwriting',
            'weights' => [400, 500, 600, 700],
        ],
        'Caveat' => [
            'category' => 'handwriting',
            'weights' => [400, 500, 600, 700],
        ],
        'Satisfy' => [
            'category' => 'handwriting',
            'weights' => [400],
        ],
        'Kalam' => [
            'category' => 'handwriting',
            'weights' => [300, 400, 700],
        ],
        'Indie Flower' => [
            'category' => 'handwriting',
            'weights' => [400],
        ],
        'Shadows Into Light' => [
            'category' => 'handwriting',
            'weights' => [400],
        ],
        'Permanent Marker' => [
            'category' => 'handwriting',
            'weights' => [400],
        ],
    ];

    /**
     * Get all available fonts
     *
     * @return array Array of all fonts with their properties
     */
    public function get_fonts(): array {
        return self::FONT_LIBRARY;
    }

    /**
     * Get a specific font by name
     *
     * @param string $font_name Font name to retrieve
     * @return array|null Font data or null if not found
     */
    public function get_font( string $font_name ): ?array {
        return self::FONT_LIBRARY[ $font_name ] ?? null;
    }

    /**
     * Get available weights for a specific font
     *
     * @param string $font_name Font name
     * @return array Array of available weights, or empty array if font not found
     */
    public function get_available_weights( string $font_name ): array {
        $font = $this->get_font( $font_name );
        return $font['weights'] ?? [];
    }

    /**
     * Generate Google Fonts API URL for a font with specified weights
     *
     * @param string $font_name Font name
     * @param array  $weights   Array of font weights to load (e.g., [400, 600, 700])
     * @return string Google Fonts API URL
     */
    public function get_font_url( string $font_name, array $weights = [] ): string {
        // Validate font exists
        if ( ! isset( self::FONT_LIBRARY[ $font_name ] ) ) {
            return '';
        }

        // Default to regular weight if no weights specified
        if ( empty( $weights ) ) {
            $weights = [400];
        }

        // Filter and sort weights
        $available_weights = $this->get_available_weights( $font_name );
        $valid_weights = array_intersect( $weights, $available_weights );
        
        // If no valid weights, use default
        if ( empty( $valid_weights ) ) {
            $valid_weights = [400];
        }
        
        sort( $valid_weights );

        // Encode font name for URL (spaces become +)
        $encoded_font_name = str_replace( ' ', '+', $font_name );

        // Build weights parameter (e.g., "400;600;700")
        $weights_param = implode( ';', $valid_weights );

        // Construct Google Fonts API URL with display=swap parameter
        // Note: We don't use urlencode() on the already-encoded font name
        // because Google Fonts API expects spaces as + not %20
        $url = sprintf(
            'https://fonts.googleapis.com/css2?family=%s:wght@%s&display=swap',
            $encoded_font_name,
            $weights_param
        );

        return $url;
    }

    /**
     * Generate HTML link tag for font preconnect
     *
     * @return string HTML link tags for preconnect
     */
    public function get_preconnect_links(): string {
        $links = '';
        $links .= '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        $links .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        return $links;
    }

    /**
     * Generate HTML link tag for loading a font
     *
     * @param string $font_name Font name
     * @param array  $weights   Array of font weights to load
     * @return string HTML link tag
     */
    public function get_font_link( string $font_name, array $weights = [] ): string {
        $url = $this->get_font_url( $font_name, $weights );
        
        if ( empty( $url ) ) {
            return '';
        }

        return sprintf(
            '<link rel="stylesheet" href="%s">',
            esc_url( $url )
        );
    }

    /**
     * Get fonts organized by category
     *
     * @return array Fonts grouped by category
     */
    public function get_fonts_by_category(): array {
        $categorized = [
            'sans-serif' => [],
            'serif' => [],
            'monospace' => [],
            'handwriting' => [],
        ];

        foreach ( self::FONT_LIBRARY as $name => $data ) {
            $category = $data['category'];
            $categorized[ $category ][ $name ] = $data;
        }

        return $categorized;
    }

    /**
     * Check if a font name is valid
     *
     * @param string $font_name Font name to validate
     * @return bool True if font exists in library
     */
    public function is_valid_font( string $font_name ): bool {
        return isset( self::FONT_LIBRARY[ $font_name ] );
    }

    /**
     * Get font category
     *
     * @param string $font_name Font name
     * @return string|null Category name or null if font not found
     */
    public function get_font_category( string $font_name ): ?string {
        $font = $this->get_font( $font_name );
        return $font['category'] ?? null;
    }

    /**
     * Get fallback font stack for a category
     *
     * @param string $category Font category
     * @return string CSS font-family fallback stack
     */
    public function get_fallback_stack( string $category ): string {
        $fallbacks = [
            'sans-serif' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
            'serif' => 'Georgia, "Times New Roman", Times, serif',
            'monospace' => 'Menlo, Monaco, Consolas, "Courier New", monospace',
            'handwriting' => 'cursive, sans-serif',
        ];

        return $fallbacks[ $category ] ?? $fallbacks['sans-serif'];
    }

    /**
     * Generate complete font-family CSS value with fallbacks
     *
     * @param string $font_name Font name
     * @return string Complete font-family CSS value
     */
    public function get_font_family_css( string $font_name ): string {
        $font = $this->get_font( $font_name );
        
        if ( ! $font ) {
            return $this->get_fallback_stack( 'sans-serif' );
        }

        $category = $font['category'];
        $fallback = $this->get_fallback_stack( $category );

        // Quote font name if it contains spaces
        $quoted_name = strpos( $font_name, ' ' ) !== false 
            ? "'{$font_name}'" 
            : $font_name;

        return "{$quoted_name}, {$fallback}";
    }
}
