<?php
/*
@package : themeson.com
author : Themeson
Don't touch baby!
*/
global $WPSAF_Client;
$WPSAF_Client = new WPSAF_Client();

class WPSAF_Client
{
    private $delimeter_wp_safelink = 'wApbsCadfEeFlgiHnik';

    public function __construct()
    {
        add_action('init', array($this, 'wp_safelink_update'));
        add_action('admin_menu', array($this, 'wp_safelink_menu'));
        add_action('in_admin_footer', array($this, 'foot_admin'), 999);
        add_action("template_redirect", array($this, "doRewrite"));
    }

    public function wp_safelink_update()
    {
        $domen = str_replace(['https://', 'http://'], '', home_url());
        $plugin_update = Puc_v4_Factory::buildUpdateChecker(
            'http://update.themeson.com/?action=get_metadata&slug=' . 'wp-safelink-client' . '&data=' . base64_encode($domen),
            WPSAF_CLIENT_FILE,
            'wp-safelink-client'
        );
    }

    public function wp_safelink_menu()
    {
        add_menu_page('WP Safelink Client', 'WP Safelink Client', 'manage_options', 'wp-safelink-client', array($this, 'wp_safelink_options'), '', '25');
    }

    public function wp_safelink_options()
    {
        global $wpdb;
        if (isset($_POST['save']) && $_POST['save'] == 'Save') {
            echo '<div id="message" class="updated fade"><p><strong>Settings have been saved</strong></p></div>';

            if (!empty($_POST['code_integrator']) && base64_encode(base64_decode($_POST['code_integrator'], true)) === $_POST['code_integrator']) {
                $wpsaf = base64_decode($_POST['code_integrator']);

                update_option('wpsaf_options', $wpsaf);
                update_option('wpsaf_options_base64', $_POST['code_integrator']);
            }

            $save_client = $_POST['wpsaf_client'];
            update_option('wpsaf_options_client', json_encode($save_client));
        }

        if (isset($_POST['reset']) && $_POST['reset'] == 'Reset Settings') {
            update_option('wpsaf_options', '');
            update_option('wpsaf_options_base64', '');
        }

        $wpsaf_client = json_decode(get_option('wpsaf_options_client'));
        $base64 = get_option('wpsaf_options_base64');

        include(WPSAF_CLIENT_DIR . 'wp-safelink.options.php');
    }

    public function foot_admin()
    {
        if ($_GET['page'] == 'wp-safelink-client') {
            echo '<style>#footer-thankyou{font-size:12px !important;}</style>';
            echo '<span style="font-size:12px;margin-top:14px;padding:10px 0 0 10px;"><i><b>~ WPSafelink</b></i></span>';
            echo '<span style="font-size:12px;margin-top:14px;padding:10px 0 0 10px;color:red"><i>~ Plugin ini hanya dijual di <a href="http://themeson.com" target="_blank">Themeson.com</a>.
Jika anda menbeli dari website lain, selamat anda dapat bajakan.</i></span>';
        }
    }

    function doRewrite()
    {
        $wpsaf = json_decode(get_option('wpsaf_options'));
        $wpsaf_client = json_decode(get_option('wpsaf_options_client'));
        if (!empty($wpsaf) && (empty($wpsaf_client->active) || $wpsaf_client->active == 1)) {
            $urls = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $URI = str_replace(array('http://', 'https://'), '', home_url());
            $URI = str_replace($URI, '', $urls);
            $url = explode('/', $URI);


            if ($wpsaf_client->adlinkfly_enable == 1 && !empty($url[1]) && strlen($url[1]) <= 10) {
                $safe_id = $url[1];
                $link = rtrim($wpsaf_client->adlinkfly_domain, '/') . '/' . $safe_id;
                $redirect = $this->generateLinkTarget($link);

                header("Location: " . $redirect);
                die();
            }

            if (
                ($url[1] == $wpsaf_client->permalink1 && $url[2] != '' && $wpsaf_client->permalink == 1)
                || (isset($_GET[$wpsaf_client->permalink2]) && $_GET[$wpsaf_client->permalink2] != '' && $wpsaf_client->permalink == 2)
                || (count($_GET) > 0 && !isset($_GET[$wpsaf_client->permalink2]) && ($wpsaf_client->permalink == 4 || $wpsaf_client->permalink == 3))
            ) {
                if ($wpsaf_client->permalink == 1) {
                    $safe_id = $url[2];
                } else if ($wpsaf_client->permalink == 2) {
                    $safe_id = trim(urldecode($_GET[$wpsaf_client->permalink2]));
                } else {
                    $safe_id = explode('?', $urls)[1];
                }
                $redirect = $this->base64_url_decode($safe_id);

                header("Location: " . $redirect);
                die();
            } else {
                ob_start(array($this, 'rewrite'));
            }
        }
    }

    protected function rewrite($html)
    {
        $output = '';
        $wpsaf = json_decode(get_option('wpsaf_options'));

        $str_link = array();
        $links = array_map('trim', explode("\n", $wpsaf->domain));
        if ($wpsaf->autoconvertmethod == "exclude") {
            $links = array_map('trim', explode("\n", $wpsaf->exclude_domain));
            $links[] = get_bloginfo('url');
        }

        $html = str_get_html($html);
        foreach ($html->find('a') as $element) {
            $line = $element->href;

            if (empty($wpsaf->autoconvertmethod) || $wpsaf->autoconvertmethod == "include") {
                if ($this->strposa($line, $links)) {
                    $str_link[$line] = $this->generateLink($line);
                }
            } else if ($wpsaf->autoconvertmethod == "exclude") {
                if (!$this->strposa($line, $links)) {
                    $str_link[$line] = $this->generateLink($line);
                }
            }
        }

        $output = str_replace(array_keys($str_link), array_values($str_link), $html);
        return $output;
    }

    function generateLink($link)
    {
        $output = '';
        $wpsaf_client = json_decode(get_option('wpsaf_options_client'));

        $base_link = $this->generateLinkTarget($link);

        if ($wpsaf_client->permalink == 1) {
            $output = home_url() . '/' . $wpsaf_client->permalink1 . '/' . $this->base64_url_encode($base_link);
        } else if ($wpsaf_client->permalink == 2) {
            $output = home_url() . '/?' . $wpsaf_client->permalink2 . '=' . $this->base64_url_encode($base_link);
        } else {
            $output = home_url() . '/?' . $this->base64_url_encode($base_link);
        }

        return $output;
    }

    function generateLinkTarget($link)
    {
        $wpsaf = json_decode(get_option('wpsaf_options'));

        $base_link = rtrim($wpsaf->base_url, '/') . '/?wpsafelink=' . $this->encrypt_link($link, $this->generateRandomString());

        return $base_link;
    }

    function strposa($haystack, $needles = array())
    {
        $chr = array();
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) return true;
        }
        return false;
    }

    /**
     * Encrypt a link
     *
     * @param string $link - link to encrypt
     * @return string
     * @throws RangeException
     */
    function encrypt_link($link, $key = '')
    {
        if (empty($key))
            $key = substr($key, 0, 10);

        $link = base64_encode(openssl_encrypt($link, "AES-256-ECB", $key));
        $link = $key . $this->delimeter_wp_safelink . $link;
        return $link;
    }

    /**
     * Generate the random string
     *
     * @param int $length
     * @return string
     */
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '._-');
    }

    function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '._-', '+/='));
    }

}