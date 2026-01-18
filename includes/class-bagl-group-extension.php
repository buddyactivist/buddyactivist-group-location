<?php

defined( 'ABSPATH' ) || exit;

/**
 * Group Extension: adds the "Location" tab to BuddyPress/BuddyBoss groups.
 */
class BAGL_Group_Extension {

    public static function init() {
        add_action( 'bp_groups_register_group_extension', array( __CLASS__, 'register' ) );
    }

    public static function register() {

        if ( ! class_exists( 'BP_Group_Extension' ) ) {
            return;
        }

        /**
         * Main extension class
         */
        class BAGL_Group_Location_Tab extends BP_Group_Extension {

            public function __construct() {

                $args = array(
                    'slug'              => 'location',
                    'name'              => __( 'Location', 'buddyactivist-group-location' ),
                    'nav_item_position' => 31,
                    'screens'           => array(
                        'create' => array(
                            'enabled' => false,
                        ),
                        'edit'   => array(
                            'enabled' => true,
                            'name'    => __( 'Location', 'buddyactivist-group-location' ),
                        ),
                        'admin'  => array(
                            'enabled' => true,
                            'name'    => __( 'Location', 'buddyactivist-group-location' ),
                        ),
                    ),
                );

                parent::init( $args );
            }

            /**
             * FRONTEND TAB DISPLAY
             */
            public function display( $group_id = null ) {

                if ( ! $group_id ) {
                    $group_id = bp_get_current_group_id();
                }

                $coords  = BAGL_Helpers::get_group_coordinates( $group_id );
                $address = BAGL_Helpers::get_group_full_address( $group_id );

                if ( empty( $coords['lat'] ) || empty( $coords['lng'] ) ) {
                    echo '<p>' . esc_html__( 'This group has no location set yet.', 'buddyactivist-group-location' ) . '</p>';
                    return;
                }

                $type = bp_groups_get_group_type( $group_id );
                $icon = BAGL_Helpers::get_group_type_icon( $type );

                wp_enqueue_script(
                    'bagl-group-single-map',
                    BAGL_URL . 'assets/js/group-single-map.js',
                    array( 'bagl-leaflet' ),
                    '1.0.0',
                    true
                );

                wp_localize_script( 'bagl-group-single-map', 'BAGL_SINGLE', array(
                    'lat'     => (float) $coords['lat'],
                    'lng'     => (float) $coords['lng'],
                    'address' => $address,
                    'icon'    => $icon,
                    'name'    => bp_get_current_group_name(),
                ) );

                if ( '' !== $address ) {
                    echo '<div class="bagl-location-address">' . nl2br( esc_html( $address ) ) . '</div>';
                }

                echo '<div id="bagl-group-map" style="width:100%;height:300px;"></div>';
            }

            /**
             * ADMIN SETTINGS SCREEN
             */
            public function settings_screen( $group_id = null ) {

                if ( ! $group_id ) {
                    $group_id = bp_get_current_group_id();
                }

                $street  = groups_get_groupmeta( $group_id, 'bagl_address_street', true );
                $number  = groups_get_groupmeta( $group_id, 'bagl_address_number', true );
                $zip     = groups_get_groupmeta( $group_id, 'bagl_address_zip', true );
                $city    = groups_get_groupmeta( $group_id, 'bagl_address_city', true );
                $state   = groups_get_groupmeta( $group_id, 'bagl_address_state', true );
                $country = groups_get_groupmeta( $group_id, 'bagl_address_country', true );
                $coords  = BAGL_Helpers::get_group_coordinates( $group_id );
                ?>

                <div class="bagl-location-settings">

                    <p>
                        <label><?php esc_html_e( 'Street', 'buddyactivist-group-location' ); ?></label><br>
                        <input type="text" name="bagl_address_street" value="<?php echo esc_attr( $street ); ?>" class="widefat">
                    </p>

                    <p>
                        <label><?php esc_html_e( 'Street number', 'buddyactivist-group-location' ); ?></label><br>
                        <input type="text" name="bagl_address_number" value="<?php echo esc_attr( $number ); ?>" class="small-text">
                    </p>

                    <p>
                        <label><?php esc_html_e( 'ZIP / Postal code', 'buddyactivist-group-location' ); ?></label><br>
                        <input type="text" name="bagl_address_zip" value="<?php echo esc_attr( $zip ); ?>" class="small-text">
                    </p>

                    <p>
                        <label><?php esc_html_e( 'City', 'buddyactivist-group-location' ); ?></label><br>
                        <input type="text" name="bagl_address_city" value="<?php echo esc_attr( $city ); ?>" class="regular-text">
                    </p>

                    <p>
                        <label><?php esc_html_e( 'State / Province / Region', 'buddyactivist-group-location' ); ?></label><br>
                        <input type="text" name="bagl_address_state" value="<?php echo esc_attr( $state ); ?>" class="regular-text">
                    </p>

                    <p>
                        <label><?php esc_html_e( 'Country', 'buddyactivist-group-location' ); ?></label><br>
                        <input type="text" name="bagl_address_country" value="<?php echo esc_attr( $country ); ?>" class="regular-text">
                    </p>

                    <input type="hidden" name="bagl_lat" id="bagl_lat" value="<?php echo esc_attr( $coords['lat'] ); ?>">
                    <input type="hidden" name="bagl_lng" id="bagl_lng" value="<?php echo esc_attr( $coords['lng'] ); ?>">

                    <?php wp_nonce_field( 'bagl_save_location_' . $group_id, 'bagl_location_nonce' ); ?>

                    <div id="bagl-admin-map" style="width:100%;height:300px;"></div>

                </div>

                <?php
            }

            /**
             * SAVE SETTINGS
             */
            public function settings_screen_save( $group_id = null ) {

                if ( ! $group_id ) {
                    $group_id = bp_get_current_group_id();
                }

                // Nonce check
                if (
                    ! isset( $_POST['bagl_location_nonce'] ) ||
                    ! wp_verify_nonce( $_POST['bagl_location_nonce'], 'bagl_save_location_' . $group_id )
                ) {
                    return;
                }

                // Permission check
                if (
                    ! bp_current_user_can( 'bp_moderate' ) &&
                    ! groups_is_user_admin( get_current_user_id(), $group_id )
                ) {
                    return;
                }

                $fields = array(
                    'bagl_address_street',
                    'bagl_address_number',
                    'bagl_address_zip',
                    'bagl_address_city',
                    'bagl_address_state',
                    'bagl_address_country',
                    'bagl_lat',
                    'bagl_lng',
                );

                foreach ( $fields as $field ) {

                    if ( ! isset( $_POST[ $field ] ) ) {
                        continue;
                    }

                    $value = wp_unslash( $_POST[ $field ] );

                    // Coordinates must be floats
                    if ( in_array( $field, array( 'bagl_lat', 'bagl_lng' ), true ) ) {
                        $value = floatval( $value );
                    } else {
                        $value = sanitize_text_field( $value );
                    }

                    groups_update_groupmeta( $group_id, $field, $value );
                }
            }
        }

        bp_register_group_extension( 'BAGL_Group_Location_Tab' );
    }
}
