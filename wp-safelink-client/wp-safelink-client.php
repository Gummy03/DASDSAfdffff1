<?php
/*
Plugin Name: WP Safelink (Client Version)
Plugin URI: https://themeson.com
Description: Converter Your Download Link to Adsense
Version: 1.0.3
Author: ThemesON
Author URI:  https://themeson.com
*/
define('WPSAF_CLIENT_FILE', __FILE__);
define('WPSAF_CLIENT_URL', plugins_url('', __FILE__));
define('WPSAF_CLIENT_DIR', plugin_dir_path(__FILE__));

require(WPSAF_CLIENT_DIR . 'plugin-update-checker/plugin-update-checker.php');
require(WPSAF_CLIENT_DIR . 'simple_html_dom.php');
require(WPSAF_CLIENT_DIR . 'wp-safelink.core.php');

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpsafelink_add_settings_link' );
function wpsafelink_add_settings_link( $links ){
  $plugin_links = array(
      '<a href="' . admin_url( 'admin.php?page=wp-safelink-client') . '">' . __( 'Settings', 'wp-safelink-client' ) . '</a>',
  );

  return array_merge( $plugin_links, $links );
}

add_action( 'admin_notices', 'wpsafelink_client_notice_set_themeson' );
function wpsafelink_client_notice_set_themeson() {
    if (get_option('wpsaf_options') == '' ) {
        if((isset($_GET['page']) && $_GET['page'] == 'wp-safelink-client')) return;
        printf('<div class="updated notice_wc_themeson themeson-wp-safelink"><p><b>%s</b> &#8211; %s</p><p class="submit">%s %s</p></div>',
        __( 'Your WP Safelink Client Version not settings properly.', 'themeson-wp-safelink' ),
        __( 'You can copy paste your WP Safelink Server code into this plugin settings', 'themeson-wp-safelink'  ),
        '<a href="' . admin_url( 'admin.php?page=wp-safelink-client&tb=import" class="button-primary">' ) . __( 'Open WP Safelink Setting', 'themeson-wp-safelink' ) . '</a>',
        '<a href="https://kb.themeson.com/" class="button-primary" target="new">' . __( 'Open Documentation', 'themeson-wp-safelink' ) . '</a>');	
    }
}
