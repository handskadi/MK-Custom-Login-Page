<?php
/*
Plugin Name: MK Custom Login Page
Description: Customize the WordPress login page with a custom logo, site name, background color, and redirect page.
Version: 1.0
Author: Mohamed KADI
Author URI: https://mohamedkadi.com
Plugin URI: https://mohamedkadi.com/project/mk-custom-login-page
License: GPL v2 or later
Tested up to: 6.4.3
Requires at least: 6.0.0
*/

// Add settings page
function mk_custom_login_page_settings_menu() {
    add_options_page( 'Custom Login Page Settings', 'Custom Login Page', 'manage_options', 'mk-custom-login-page-settings', 'mk_custom_login_page_settings_page' );
}
add_action( 'admin_menu', 'mk_custom_login_page_settings_menu' );

// Initialize settings
function mk_custom_login_page_settings_init() {
    register_setting( 'mk-custom-login-page-settings-group', 'mk_login_logo' );
    register_setting( 'mk-custom-login-page-settings-group', 'mk_login_site_name' );
    register_setting( 'mk-custom-login-page-settings-group', 'mk_login_background_color' );
    register_setting( 'mk-custom-login-page-settings-group', 'mk_login_redirect_page' );
}
add_action( 'admin_init', 'mk_custom_login_page_settings_init' );

// Settings page content
function mk_custom_login_page_settings_page() {
    ?>
    <div class="wrap">
        <h1>Custom Login Page Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'mk-custom-login-page-settings-group' ); ?>
            <?php do_settings_sections( 'mk-custom-login-page-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Logo URL:</th>
                    <td><input type="text" name="mk_login_logo" value="<?php echo esc_attr( get_option('mk_login_logo') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Site Name:</th>
                    <td><input type="text" name="mk_login_site_name" value="<?php echo esc_attr( get_option('mk_login_site_name') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Background Color:</th>
                    <td><input type="text" name="mk_login_background_color" value="<?php echo esc_attr( get_option('mk_login_background_color', '#f2f2f2') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Redirect Page After Login:</th>
                    <td><input type="text" name="mk_login_redirect_page" value="<?php echo esc_attr( get_option('mk_login_redirect_page') ); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Enqueue custom styles for login page
function mk_custom_login_page_styles() {
    wp_enqueue_style( 'mk-custom-login-page-style', plugin_dir_url( __FILE__ ) . 'css/style.css' );
}
add_action('login_enqueue_scripts', 'mk_custom_login_page_styles');

// Change login logo URL
function mk_custom_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'mk_custom_login_logo_url');

// Change login logo title
function mk_custom_login_logo_url_title() {
    $site_name = get_option('mk_login_site_name', get_bloginfo('name'));
    return $site_name;
}
add_filter('login_headertext', 'mk_custom_login_logo_url_title');

// Redirect after login
function mk_custom_login_redirect($redirect_to, $request, $user) {
    // Is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        // check for admins
        if (in_array('administrator', $user->roles)) {
            //Redirect them to the default place
            return $redirect_to;
        } else {
            return home_url();
        }
    } else {
        return $redirect_to;
    }
}
add_filter('login_redirect', 'mk_custom_login_redirect', 10, 3);
