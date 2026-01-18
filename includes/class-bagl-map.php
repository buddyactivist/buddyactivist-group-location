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
     * Render the global map container + search bar + enqueue JS
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

        // Load JS for search
        wp_enqueue_script(
            'bagl-group-global-search',
            BAGL_URL . 'assets/js/group-global-search.js',
            array( 'bagl-group-global-map' ),
            '1.0.0',
            true
        );

        // Load CSS
        wp_enqueue_style(
            'bagl-map-css',
            BAGL_URL . 'assets/css/map.css',
            array(),
            '1.0.0'
        );

        // Pass REST URL + map defaults
        wp_localize_script( 'bagl-group-global-map', 'BAGL_MAP', array(
            'rest_url' => esc_url_raw( rest_url( 'bagl/v1/groups' ) ),
            'center'   => array(
                'lat'  => (float) get_option( 'bagl_map_center_lat', 41.9 ),
                'lng'  => (float) get_option( 'bagl_map_center_lng', 12.5 ),
                'zoom' => (int)   get_option( 'bagl_map_zoom', 6 ),
            ),
        ) );

        // Search bar + map container
        return '
<div class="bagl-global-search-wrapper">
    <input type="text" id="bagl-global-search" placeholder="' . esc_attr__( 'Search groups or places...', 'buddyactivist-group-location' ) . '">
</div>
<div id="bagl-group-global-map"></div>
';
    }
}
