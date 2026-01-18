<?php

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin loader
 *
 * Loads all plugin components in a clean, modular, production‑ready way.
 */
class BAGL_Loader {

    /**
     * Initialize plugin components
     */
    public static function init() {

        // Core includes
        require_once BAGL_PATH . 'includes/class-bagl-assets.php';
        require_once BAGL_PATH . 'includes/class-bagl-helpers.php';
        require_once BAGL_PATH . 'includes/class-bagl-group-extension.php';
        require_once BAGL_PATH . 'includes/class-bagl-rest.php';
        require_once BAGL_PATH . 'includes/class-bagl-map.php';

        // Initialize components
        BAGL_Assets::init();
        BAGL_Group_Extension::init();
        BAGL_REST::init();
        BAGL_Map::init();
    }
}
