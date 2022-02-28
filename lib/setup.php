<?php
/**
 * Theme setup functions.
 */

// Define the asset path.
if ( ! defined('ASSET_URI')) {
    define('ASSET_URI', \get_template_directory_uri() . '/dist');
}

if ( ! function_exists('THEMENAME_setup')) {
    /**
     * Theme setup.
     */
    function THEMENAME_setup()
    {
        // Make theme available for translation
        load_theme_textdomain('textdomain', get_template_directory() . '/lang');

        /**
         * Enable plugins to manage the document title
         *
         * @see http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
         */
        add_theme_support('title-tag');

        /**
         * Register wp_nav_menu() menus
         *
         * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
         */
        register_nav_menus(
            [
                'primary_navigation' => __('Primary Navigation', 'textdomain'),
            ]
        );

        /**
         * Enable post thumbnails
         *
         * @see http://codex.wordpress.org/Post_Thumbnails
         * @see http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
         * @see http://codex.wordpress.org/Function_Reference/add_image_size
         */
        add_theme_support('post-thumbnails');

        /**
         * Enable post formats
         *
         * @see http://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

        /**
         * Enable HTML5 markup support
         *
         * @see http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
         */
        add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);
    }
}

add_action('after_setup_theme', 'THEMENAME_setup');

if ( ! function_exists('THEMENAME_assets')) {
    /**
     * Theme assets.
     *
     * @throws JsonException
     */
    function THEMENAME_assets()
    {
        $version = wp_get_theme()->get('Version');

        $file = THEMENAME_get_manifest_file('bundle.min.css');
        if ( ! is_wp_error($file)) {
            wp_enqueue_style(
                'themename-styles',
                $file,
                [],
                $version,
                'all'
            );
        }

        $handle = 'themename-scripts';
        $file   = THEMENAME_get_manifest_file('bundle.min.js');
        if ( ! is_wp_error($file)) {
            wp_enqueue_script(
                $handle,
                $file,
                [],
                $version,
                false
            );

            $wp_globals = apply_filters('wp_globals', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wp_rest'),
            ]);

            wp_set_script_translations($handle, 'textdomain', get_stylesheet_directory() . '/lang/');

            wp_localize_script(
                $handle,
                'wp_globals',
                $wp_globals
            );
        }
    }
}

add_action('wp_enqueue_scripts', 'THEMENAME_assets', 100);

if ( ! function_exists('THEMENAME_admin_assets')) {
    /**
     * Admin assets.
     *
     * @throws JsonException
     */
    function THEMENAME_admin_assets()
    {
        $version = wp_get_theme()->get('Version');

        $file = THEMENAME_get_manifest_file('admin.min.css');
        if ( ! is_wp_error($file)) {
            wp_enqueue_style(
                'themename-styles',
                $file,
                [],
                $version,
                'all'
            );
        }

        $handle = 'themename-admin-scripts';
        $file   = THEMENAME_get_manifest_file('admin.min.js');
        if ( ! is_wp_error($file)) {
            wp_enqueue_script(
                'themename-scripts',
                $file,
                ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'],
                $version,
                false
            );

            $wp_globals = apply_filters('wp_globals', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wp_rest'),
            ]);

            wp_set_script_translations($handle, 'textdomain', get_stylesheet_directory() . '/lang/');

            wp_localize_script(
                $handle,
                'wp_globals',
                $wp_globals
            );
        }
    }
}

add_action('admin_enqueue_scripts', 'THEMENAME_admin_assets', 100);

if ( ! function_exists('THEMENAME_disable_emojis')) {
    /**
     * Disable emojis.
     */
    function THEMENAME_disable_emojis()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', 'THEMENAME_disable_emojis_tinymce');
    }
}

add_action('init', 'THEMENAME_disable_emojis');

if ( ! function_exists('THEMENAME_start_cleanup')) {
    /**
     * Start cleanup.
     */
    function THEMENAME_start_cleanup()
    {
        // Initialize the cleanup
        \add_action('init', 'THEMENAME_cleanup_head');
    }
}

add_action('after_setup_theme', 'THEMENAME_start_cleanup');
