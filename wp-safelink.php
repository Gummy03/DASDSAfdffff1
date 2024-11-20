<?php
/*
Plugin Name: WP Safelink (Adsense Domain)
Plugin URI: https://themeson.com
Description: Converter Your Download Link to Adsense
Version: 4.4.2
Author: ThemesON
Author URI:  https://themeson.com
*/
define('WPSAF_FILE', __FILE__);
define('WPSAF_URL', plugins_url('', __FILE__));
define('WPSAF_DIR', plugin_dir_path(__FILE__));

require(WPSAF_DIR . 'vendor/autoload.php');
require(WPSAF_DIR . 'simple_html_dom.php');
require(WPSAF_DIR . 'plugin-update-checker/plugin-update-checker.php');

$core = file_exists(WPSAF_DIR . 'wp-safelink.main.php') ? WPSAF_DIR . 'wp-safelink.main.php' : WPSAF_DIR . 'wp-safelink.core.php';
require($core);

require(WPSAF_DIR . 'wp-safelink.ajax.php');
require(WPSAF_DIR . 'wp-safelink.functions.php');

register_activation_hook(__FILE__, 'wpsafelink_activation');
function wpsafelink_activation()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'wpsafelink';
    $sql = "CREATE TABLE $table_name (
		ID bigint(0) NOT NULL AUTO_INCREMENT, 
		date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, 
		date_view datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, 
		date_click datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, 
		safe_id varchar(8) NOT NULL,
		link longtext NOT NULL,
		view bigint(0) NOT NULL,
		click bigint(0) NOT NULL,
		UNIQUE KEY id (ID)
	) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    if (get_option('wpsaf_options', '') == '') wpsaf_default();

    // Check temp files
    $upload_dir = wp_get_upload_dir();
    $tmp = $upload_dir['basedir'] . '/wpsaf.script.js';
    if (file_exists($tmp)) {
        $data = file_get_contents($tmp);
        $cached = WPSAF_DIR . 'assets/wpsaf.script.js';

        file_put_contents($cached, $data);
        unlink($tmp);
    }
}

function wpsaf_default()
{
    $wpsaf_def = array(
        'permalink1' => 'go',
        'permalink2' => 'go',
        'permalink' => 2,
        'linkr' => 'redirect',
        'content' => 0,
        'contentid' => '',
        'template' => 'template1',
        'delay' => 20,
        'delaytext' => 'Thank you for your visit. Your links will be created in {time} seconds.',
        'logo' => WPSAF_URL . '/assets/logo.png',
        'image1' => WPSAF_URL . '/assets/generate4.png',
        'image2' => WPSAF_URL . '/assets/wait4.png',
        'image3' => WPSAF_URL . '/assets/target4.png',
        'image4' => WPSAF_URL . '/assets/human-verification4.png',
        'ads1' => '',
        'ads2' => '',
        'autosave' => 1,
        'redirect' => 1,
        'new_tab' => 1,
        'base_url' => get_bloginfo('url') . '/',
        'adb' => 1,
        'adb1' => 'Adblock Detected',
        'adb2' => 'Please disable adblock to proceed to the destination page',
        'autoconvert' => 2,
        'skipverification' => 2,
        'second_safelink_url' => '',
        'recaptcha_enable' => 2,
        'recaptcha_site_key' => '',
        'recaptcha_secret_key' => '',
        'recaptcha_text' => 'Please complete reCAPTCHA verification',
        'delete' => 2,
        'action_type' => 'button',
        'button1' => 'CLICK 2X FOR GENERATE LINK',
        'button2' => 'PLEASE WAIT ...',
        'button3' => 'DOWNLOAD LINK',
        'button4' => 'IM NOT ROBOT',
    );
    $wpsaf = json_encode($wpsaf_def);
    update_option('wpsaf_options', $wpsaf);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpsafelink_add_settings_link');
function wpsafelink_add_settings_link($links)
{
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=wp-safelink') . '">' . __('Settings', 'wp-safelink') . '</a>',
    );

    return array_merge($plugin_links, $links);
}

add_action('admin_notices', 'wpsafelink_notice_set_themeson');
function wpsafelink_notice_set_themeson()
{
    global $WPSAF;

    $lis = $WPSAF->ceklis('', true);
    if (empty($lis) && isset($_GET['page']) && ($_GET['page'] != 'wp-safelink')) {
        printf('<div class="updated notice_wc_themeson themeson-wp-safelink"><p><b>%s</b> &#8211; %s</p><p class="submit">%s %s</p></div>',
            __('Your not using the license code, your site may be broken or some functionality will not work.', 'themeson-wp-safelink'),
            __('You can find the license code from ThemesON Member Area.', 'themeson-wp-safelink'),
            '<a href="' . admin_url('admin.php?page=wp-safelink&tb=lic" class="button-primary">') . __('Enter License Code', 'themeson-wp-safelink') . '</a>',
            '<a href="http://themeson.com/license/" class="button-primary" target="new">' . __('Get License Code', 'themeson-wp-safelink') . '</a>');
    }
}

add_action('init', 'wpsafelink_remove_footer', 10);
function wpsafelink_remove_footer()
{
    global $WPSAF;
    $wpsaf = json_decode(get_option('wpsaf_options'), true);
    if (empty($wpsaf['autojavascript']) || $wpsaf['autojavascript'] == 2) {
        remove_action('wp_footer', array($WPSAF, 'footer_wp_safelink'), 999);
    }
}

register_deactivation_hook(__FILE__, 'wpsafelink_deactivation');
function wpsafelink_deactivation()
{
    global $WPSAF;

    $lis = $WPSAF->ceklis('', true);
    if (!empty($lis)) {
        $cached = WPSAF_DIR . 'assets/wpsaf.script.js';
        $data = file_get_contents($cached);

        // Store to temp files
        $upload_dir = wp_get_upload_dir();
        $tmp = $upload_dir['basedir'] . '/wpsaf.script.js';
        file_put_contents($tmp, $data);
    }
}