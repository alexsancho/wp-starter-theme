<?php
/**
 * Theme helper functions
 */

/**
 * Removes the emoji plugin from tinymce.
 *
 * @param mixed $plugins Installed tinymce plugins.
 *
 * @return array
 */
function THEMENAME_disable_emojis_tinymce(mixed $plugins) : array
{
    if (is_array($plugins)) {
        return array_diff($plugins, ['wpemoji']);
    }

    return [];
}

/**
 *  WordPress cleanup function.
 */
function THEMENAME_cleanup_head()
{
    // EditURI link
    remove_action('wp_head', 'rsd_link');

    // Category feed links
    remove_action('wp_head', 'feed_links_extra', 3);

    // Post and comment feed links
    remove_action('wp_head', 'feed_links', 2);

    // Windows Live Writer
    remove_action('wp_head', 'wlwmanifest_link');

    // Index link
    remove_action('wp_head', 'index_rel_link');

    // Previous link
    remove_action('wp_head', 'parent_post_rel_link', 10);

    // Start link
    remove_action('wp_head', 'start_post_rel_link', 10);

    // Canonical
    remove_action('wp_head', 'rel_canonical', 10);

    // Shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);

    // Links for adjacent posts
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

    // WP version
    remove_action('wp_head', 'wp_generator');

    // rest api link
    remove_action('wp_head', 'rest_output_link_wp_head', 10);

    // embed links
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
}

if ( ! function_exists('THEMENAME_get_manifest_file')) {
    /**
     * Gets the current version of a file from the manifest.json file.
     *
     * @param string      $filename     The non-hashed filename (e.g. style.css).
     * @param string|null $manifestPath Full path to the manifest file. If not set,
     *                             assumes the file is located in
     *                             ../dist/manifest.json.
     *
     * @return string|WP_Error The path to the current corresponding file
     *                          (e.g. /wp-content/themes/your-theme/style.12345.css).
     *                          In the event of a failure, an instance of \WP_Error
     *                          will be returned with more details.
     * @throws JsonException
     */
    function THEMENAME_get_manifest_file(string $filename, string $manifestPath = null) : WP_Error|string
    {
        // Set the default path if one isn't provided.
        if ($manifestPath === null) {
            $manifestPath = get_stylesheet_directory() . '/dist/manifest.json';
        }

        // Check the file exists before we try to load it.
        if ( ! is_file($manifestPath)) {
            return new WP_Error('manifest', 'The Manifest file can not be found.', $manifestPath);
        }

        $manifest = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);

        // Attempt to match the requested file.
        if ( ! array_key_exists($filename, $manifest)) {
            return new WP_Error('manifest', 'The requested file could not be matched.', $filename);
        }

        return get_stylesheet_directory_uri() . '/' . $manifest[$filename];
    }
}
