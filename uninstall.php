<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$wpsaf = json_decode(get_option('wpsaf_options'));
if ($wpsaf->delete == 1) :
    delete_option('wpsaf_options');

    $upload_dir = wp_get_upload_dir();
    $tmp = $upload_dir['basedir'] . '/wpsaf.script.js';
    if (file_exists($tmp)) {
        unlink($tmp);
    }

    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpsafelink");
endif;