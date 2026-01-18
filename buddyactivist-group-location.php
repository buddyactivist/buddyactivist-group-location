<?php
/**
 * Plugin Name: BuddyActivist Group Location
 * Description: Geolocation for BuddyPress/BuddyBoss Groups.
 * Version: 1.0.0
 * Author: BuddyActivist
 * Text Domain: buddyactivist-group-location
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plugin paths
 */
define( 'BAGL_PATH', plugin_dir_path( __FILE__ ) );
define( 'BAGL_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load translations from /languages/
 */
add_action( 'plugins_loaded', 'bagl_load_textdomain' );
function bagl_load_textdomain() {
    load_plugin_textdomain(
        'buddyactivist-group-location',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
}

/**
 * Load plugin loader
 */
require_once BAGL_PATH . 'includes/class-bagl-loader.php';

/**
 * Initialize plugin when BuddyPress is ready
 */
add_action( 'bp_include', array( 'BAGL_Loader', 'init' ) );
