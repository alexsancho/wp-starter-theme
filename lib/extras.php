<?php
/**
 * Extra theme functionalities.
 */

/**
 * Add <body> classes
 *
 * @param array $classes Body class strings.
 *
 * @return array
 */
function THEMENAME_body_class(array $classes) : array
{
    // Add page slug if it doesn't exist
    if (is_single() || (is_page() && ! is_front_page())) {
        if ( ! in_array(basename(get_permalink()), $classes, true)) {
            $classes[] = basename(get_permalink());
        }
    }

    return $classes;
}

add_filter('body_class', 'THEMENAME_body_class');
