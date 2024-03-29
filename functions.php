<?php
/**
 * Initialize the theme settings loading.
 */

// Require all function files under /lib.
$lib_path = __DIR__ . '/lib/';

// List your /lib files here.
$includes = [
    'helpers.php', // Helper functions
    'extras.php',  // Custom functions
    'setup.php',   // Theme setup
    'images.php',  // Image functions
];

// Loop through the includes and require them as part of the functions.
foreach ( $includes as $file ) {
    $file_path = $lib_path . $file;
    if ( is_file( $file_path ) ) {
        require $file_path;
    }
}
