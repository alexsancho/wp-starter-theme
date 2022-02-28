<?php
/**
 * Image setup file.
 */

if ( ! function_exists('THEMENAME_mime_types')) {
    /**
     * Add SVG support.
     *
     * @param array $mimes The accepted mime types.
     *
     * @return array
     */
    function THEMENAME_mime_types(array $mimes) : array
    {
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }
}

add_filter('upload_mimes', 'THEMENAME_mime_types');

if ( ! function_exists('THEMENAME_jpeg_quality')) {
    /**
     * Add compressing level.
     *
     * @return int
     */
    function THEMENAME_jpeg_quality() : int
    {
        return 80;
    }
}

add_filter('jpeg_quality', 'THEMENAME_jpeg_quality');

if ( ! function_exists('THEMENAME_image_sizes')) {
    /**
     * Add and update image sizes for theme images.
     *
     * @return void
     */
    function THEMENAME_image_sizes()
    {
        /**
         * These are examples.
         * Add your own and delete these when developing the theme.
         */
        /**
         * // Update medium size.
         * update_option( 'medium_size_w', 320 );
         * update_option( 'medium_size_h', 9999 );
         * // Update medium_large size.
         * update_option( 'medium_large_size_w', 768 );
         * update_option( 'medium_large_size_h', 9999 );
         * // Update large size.
         * update_option( 'large_size_w', 1024 );
         * update_option( 'large_size_h', 9999 );
         * // Add custom image sizes.
         * add_image_size( 'fullhd', 1920, 9999 );
         */
    }
}

// Hook the image_sizes function to after_setup_theme.
add_action('after_setup_theme', 'THEMENAME_image_sizes');
