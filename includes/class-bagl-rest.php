<?php

defined( 'ABSPATH' ) || exit;

/**
 * REST API endpoint for global group map.
 * Returns all groups with coordinates and metadata.
 */
class BAGL_REST {

    /**
     * Register REST routes
     */
    public static function init() {
        add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
    }

    /**
     * Define /bagl/v1/groups endpoint
     */
    public static function register_routes() {

        register_rest_route( 'bagl/v1', '/groups', array(
            'methods'             => 'GET',
            'callback'            => array( __CLASS__, 'get_groups' ),
            'permission_callback' => '__return_true', // public endpoint
        ) );
    }

    /**
     * Return all groups with coordinates
     */
    public static function get_groups( WP_REST_Request $request ) {

        $groups = groups_get_groups( array(
            'per_page'    => 999,
            'show_hidden' => false,
        ) );

        $data = array();

        foreach ( $groups['groups'] as $group ) {

            $coords = BAGL_Helpers::get_group_coordinates( $group->id );

            // Skip groups without coordinates
            if ( empty( $coords['lat'] ) || empty( $coords['lng'] ) ) {
                continue;
            }

            $address = BAGL_Helpers::get_group_full_address( $group->id );
            if ( '' === $address ) {
                $address = __( 'No address available', 'buddyactivist-group-location' );
            }

            $type = bp_groups_get_group_type( $group->id );
            $icon = BAGL_Helpers::get_group_type_icon( $type );

            // Group avatar (logo)
            $avatar = bp_core_fetch_avatar( array(
                'item_id' => $group->id,
                'object'  => 'group',
                'type'    => 'thumb',
                'html'    => false,
            ) );

            $data[] = array(
                'id'      => (int) $group->id,
                'name'    => $group->name,
                'lat'     => (float) $coords['lat'],
                'lng'     => (float) $coords['lng'],
                'type'    => $type,
                'icon'    => esc_url_raw( $icon ),
                'avatar'  => esc_url_raw( $avatar ),
                'address' => $address,
                'link'    => esc_url_raw( bp_get_group_permalink( $group ) ),
            );
        }

        return rest_ensure_response( $data );
    }
}
