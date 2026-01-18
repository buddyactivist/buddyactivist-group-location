<?php

defined( 'ABSPATH' ) || exit;

/**
 * Admin settings page for global map defaults.
 */
class BAGL_Admin {

    /**
     * Initialize admin menu + settings
     */
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    /**
     * Add settings page under "Settings"
     */
    public static function add_menu() {
        add_options_page(
            __( 'Group Location Settings', 'buddyactivist-group-location' ),
            __( 'Group Location', 'buddyactivist-group-location' ),
            'manage_options',
            'bagl-settings',
            array( __CLASS__, 'render_page' )
        );
    }

    /**
     * Register settings
     */
    public static function register_settings() {

        register_setting(
            'bagl_settings_group',
            'bagl_map_center_lat',
            array(
                'type'              => 'number',
                'sanitize_callback' => 'floatval',
                'default'           => 41.9,
            )
        );

        register_setting(
            'bagl_settings_group',
            'bagl_map_center_lng',
            array(
                'type'              => 'number',
                'sanitize_callback' => 'floatval',
                'default'           => 12.5,
            )
        );

        register_setting(
            'bagl_settings_group',
            'bagl_map_zoom',
            array(
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 6,
            )
        );
    }

    /**
     * Render settings page
     */
    public static function render_page() {

        $lat  = get_option( 'bagl_map_center_lat', 41.9 );
        $lng  = get_option( 'bagl_map_center_lng', 12.5 );
        $zoom = get_option( 'bagl_map_zoom', 6 );
        ?>

        <div class="wrap">
            <h1><?php esc_html_e( 'Group Location Settings', 'buddyactivist-group-location' ); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'bagl_settings_group' ); ?>

                <table class="form-table">

                    <tr>
                        <th scope="row">
                            <label for="bagl_map_center_lat">
                                <?php esc_html_e( 'Default map center latitude', 'buddyactivist-group-location' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="number" step="0.000001" name="bagl_map_center_lat" id="bagl_map_center_lat"
                                   value="<?php echo esc_attr( $lat ); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="bagl_map_center_lng">
                                <?php esc_html_e( 'Default map center longitude', 'buddyactivist-group-location' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="number" step="0.000001" name="bagl_map_center_lng" id="bagl_map_center_lng"
                                   value="<?php echo esc_attr( $lng ); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="bagl_map_zoom">
                                <?php esc_html_e( 'Default map zoom', 'buddyactivist-group-location' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="number" min="1" max="19" name="bagl_map_zoom" id="bagl_map_zoom"
                                   value="<?php echo esc_attr( $zoom ); ?>" class="small-text">
                        </td>
                    </tr>

                </table>

                <?php submit_button(); ?>
            </form>
        </div>

        <?php
    }
}
