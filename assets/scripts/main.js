/**
 * Theme JS building
 */

// Require 3rd party libraries
require( 'babel-polyfill' );
require( __dirname + '/modernizr.js' );

// Uncomment to include Foundation scripts.
// require( 'foundation-sites' );

// Require main style file here for concatenation.
require( __dirname + '/../styles/main.scss' );
