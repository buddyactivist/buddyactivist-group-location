<?php

defined( 'ABSPATH' ) || exit;

/**
 * Helper functions for group location data.
 * Clean, safe, production‑ready utilities.
 */
class BAGL_Helpers {

    /**
     * Get group coordinates (lat/lng)
     */
    public static function get_group_coordinates( $group_id ) {
        return array(
            'lat' => groups_get_groupmeta( $group_id, 'bagl_lat', true ),
            'lng' => groups_get_groupmeta( $group_id, 'bagl_lng', true ),
        );
    }

    /**
     * Build a formatted full address string
     * Street + Number
     * ZIP + City
     * State, Country
     */
    public static function get_group_full_address( $group_id ) {

        $street  = groups_get_groupmeta( $group_id, 'bagl_address_street', true );
        $number  = groups_get_groupmeta( $group_id, 'bagl_address_number', true );
        $zip     = groups_get_groupmeta( $group_id, 'bagl_address_zip', true );
        $city    = groups_get_groupmeta( $group_id, 'bagl_address_city', true );
        $state   = groups_get_groupmeta( $group_id, 'bagl_address_state', true );
        $country = groups_get_groupmeta( $group_id, 'bagl_address_country', true );

        $line1 = trim( "$street $number" );
        $line2 = trim( "$zip $city" );
        $line3 = trim( "$state, $country" );

        return trim( "$line1\n$line2\n$line3" );
    }

    /**
     * Get the marker icon for a group type
     * Falls back to default icon if missing
     */
    public static function get_group_type_icon( $type ) {

        $default = BAGL_URL . 'assets/img/default-marker.png';

        if ( empty( $type ) ) {
            return $default;
        }

        // In future versions: icons configurable from admin
        $path = BAGL_PATH . "assets/img/marker-{$type}.png";
        $url  = BAGL_URL  . "assets/img/marker-{$type}.png";

        return file_exists( $path ) ? $url : $default;
    }
}
