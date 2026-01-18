<?php

defined( 'ABSPATH' ) || exit;

/**
 * Handles all CSS/JS loading for frontend and admin.
 * Assets are loaded ONLY where needed (groups + shortcode).
 */
class BAGL_Assets {

    /**
     * Initialize hooks
     */
    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin' ) );
    }

    /**
     * Load frontend assets only on:
     * - BuddyPress/BuddyBoss group pages
     * - Pages containing the [bagl_group_map] shortcode
     */
    public static function enqueue_frontend() {

        if ( ! function_exists( 'bp_is_groups_component' ) ) {
            return;
        }

        $load = false;

        // Group pages (BuddyPress + BuddyBoss)
        if ( bp_is_groups_component() ) {
            $load = true;
        }

        // Pages with shortcode
        if ( ! $load ) {
            $post_id = get_the_ID();
            if ( $post_id && has_shortcode( get_post_field( 'post_content', $post_id ), 'bagl_group_map' ) ) {
                $load = true;
            }
        }

        if ( ! $load ) {
            return;
        }

        /**
         * Leaflet core
         */
        wp_enqueue_style(
            'bagl-leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            array(),
            '1.9.4'
        );

        wp_enqueue_script(
            'bagl-leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            array(),
            '1.9.4',
            true
        );

        /**
         * MarkerCluster (only used in global map)
         */
        wp_enqueue_style(
            'bagl-markercluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css',
            array( 'bagl-leaflet' ),
            '1.5.3'
        );

        wp_enqueue_script(
            'bagl-markercluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js',
            array( 'bagl-leaflet' ),
            '1.5.3',
            true
        );

        /**
         * Plugin CSS
         */
        wp_enqueue_style(
            'bagl-map',
            BAGL_URL . 'assets/css/map.css',
            array(),
            '1.0.0'
        );
    }

    /**
     * Load admin assets only on BuddyPress/BuddyBoss group admin screens
     */
    public static function enqueue_admin( $hook ) {

        // Load only in group admin/edit screens
        if ( false === strpos( $hook, 'bp-groups' ) ) {
            return;
        }

        wp_enqueue_style(
            'bagl-admin',
            BAGL_URL . 'assets/css/admin.css',
            array(),
            '1.0.0'
        );

        /**
         * Leaflet for admin map
         */
        wp_enqueue_style(
            'bagl-leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            array(),
            '1.9.4'
        );

        wp_enqueue_script(
            'bagl-leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            array(),
            '1.9.4',
            true
        );

        /**
         * Admin map JS
         */
        wp_enqueue_script(
            'bagl-admin-location',
            BAGL_URL . 'assets/js/admin-location.js',
            array( 'jquery', 'bagl-leaflet' ),
            '1.0.0',
            true
        );
    }
}
