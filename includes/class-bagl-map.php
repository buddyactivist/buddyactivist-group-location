<?php

defined( 'ABSPATH' ) || exit;

/**
 * Handles the global map shortcode and JS initialization.
 */
class BAGL_Map {

    /**
     * Register shortcode
     */
    public static function init() {
        add_shortcode( 'bagl_group_map', array( __CLASS__, 'render_global_map' ) );
    }

    /**
     * Render the global map container and enqueue JS
     */
    public static function render_global_map( $atts ) {

        // Load JS for global map
        wp_enqueue_script(
            'bagl-group-global-map',
            BAGL_URL . 'assets/js/group-global-map.js',
            array( 'bagl-leaflet', 'bagl-markercluster' ),
            '1.0.0',
            true
        );

        // Pass REST URL to JS
        wp_localize_script( 'bagl-group-global-map', 'BAGL_MAP', array(
            'rest_url' => esc_url_raw( rest_url( 'bagl/v1/groups' ) ),
        ) );

        // Map container
        return '<div id="bagl-group-global-map" style="width:100%;height:600px;"></div>';
    }
}
