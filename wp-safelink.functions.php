<?php
/*
	@package : themeson.com
	author : Themeson
	Don't touch baby!
*/

use ReCaptcha\ReCaptcha;

function newpsafelink_data()
{
    $linktarget = apply_filters('wp_safelink_code', '');
    if (isset($_POST['newwpsafelink'])) {
        $linktarget = json_decode(base64_decode($_POST['newwpsafelink']), true);
    }

    return $linktarget;
}

/**
 * The function for integrate wp safelink into your theme at header section
 */
function newwpsafelink_top()
{
    $code = newpsafelink_data();
    if ($code) {
        $wpsaf = json_decode(get_option('wpsaf_options'));

        if (isset($_POST['g-recaptcha-response'])) {
            $recaptcha = new ReCaptcha($wpsaf->recaptcha_secret_key);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response']);
            if (!$resp->isSuccess()) {
                echo '<script type="text/javascript">alert("' . (!empty($wpsaf->recaptcha_text) ? $wpsaf->recaptcha_text : "Please complete reCAPTCHA verification") . '");</script>';
                $_REQUEST['humanverification'] = 1;
            }
        }

        $code['image4'] = $wpsaf->image4;
        $code['delaytext'] = str_replace('<span id=\"wpsafe-time\">', '<span id="wpsafe-time">', $code['delaytext']);
        $code['ads1'] = str_replace('\"', '"', $code['ads1']);
        ?>
        <style>
            .wpsafe-top {
                clear: both;
                width: auto;
                text-align: center;
                margin-bottom: 20px;
            }

            .wpsafe-top img {
                display: block;
                margin: 0 auto;
            }

            .wpsafe-bottom {
                clear: both;
                width: auto;
                text-align: center;
                margin-top: 0;
            }

            .wpsafe-bottom img {
                display: block;
                margin: 0 auto;
            }

            #wpsafe-generate {
                display: none;
            }

            #wpsafe-wait2 {
                display: none;
            }

            #wpsafe-link {
                display: none;
            }

            .adb {
                display: none;
                position: fixed;
                width: 100%;
                height: 100%;
                left: 0;
                top: 0;
                bottom: 0;
                background: rgba(51, 51, 51, 0.9);
                z-index: 10000;
                text-align: center;
                color: #111;
            }

            .adbs {
                margin: 0 auto;
                width: auto;
                min-width: 400px;
                position: fixed;
                z-index: 99999;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                padding: 20px 30px 30px;
                background: rgba(255, 255, 255, 0.9);
                -webkit-border-radius: 12px;
                -moz-border-radius: 12px;
                border-radius: 12px;
            }

            #wpsafe-link img, #wpsafe-wait2 img {
                display: block;
                margin: 0 auto;
            }

            .safelink-recatpcha {
                text-align: center;
            }

            .safelink-recatpcha > div {
                display: inline-block;
            }
        </style>
        <div class="wpsafe-top text-center">
            <div><?php echo wp_kses_stripslashes($code['ads1']); ?></div>
            <?php if (isset($_REQUEST['humanverification'])) : ?>
                <?php
                $posts = array();
                if ($wpsaf->content == '0') {
                    $args = array(
                        'post_type' => 'post',
                        'orderby' => 'rand',
                        'posts_per_page' => 1,
                    );
                    $post_all = get_posts($args);
                    $posts = $post_all[0];
                } else if ($wpsaf->content == '1') {
                    $ID = explode(',', $wpsaf->contentid);
                    shuffle($ID);
                    foreach ($ID as $id) {
                        $posts = get_post($id);
                        break;
                    }
                }
                ?>
                <form id="wpsafelink-landing" name="dsb" action="<?php echo get_permalink($posts->ID) ?>" method="post">
                    <input type="hidden" name="newwpsafelink"
                           value="<?php echo base64_encode(json_encode(newpsafelink_data())); ?>">

                    <?php if ($wpsaf->captcha_provider == 'recaptcha' && $wpsaf->recaptcha_enable == 1): ?>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <div class="safelink-recatpcha">
                            <div class="g-recaptcha" data-sitekey="<?php echo $wpsaf->recaptcha_site_key; ?>"
                                 data-callback="wpsafelink_recaptcha"></div>
                        </div>

                        <script type="text/javascript">
                            window.RECAPTCHA_SAFELINK = 'recaptcha';
                        </script>
                    <?php endif; ?>

                    <?php if ($wpsaf->captcha_provider == 'hcaptcha' && $wpsaf->hcaptcha_enable == 1): ?>
                        <script src="https://hcaptcha.com/1/api.js" async defer></script>
                        <div class="safelink-recatpcha">
                            <div id="hcaptcha" class="h-captcha"
                                 data-sitekey="<?php echo $wpsaf->hcaptcha_site_key; ?>"></div>
                        </div>

                        <script type="text/javascript">
                            window.HCAPTCHA_SAFELINK = 'hcaptcha';
                        </script>
                    <?php endif; ?>

                    <a href="<?php bloginfo('url'); ?>" style="cursor:pointer;" onclick="return wpsafehuman()"
                       id="wpsafelinkhuman">
                        <?php if ($wpsaf->action_type == 'button') : ?>
                            <button class="btn btn-primary"><?php echo $wpsaf->button4; ?></button>
                        <?php else : ?>
                            <img
                                src="<?php echo !empty($code['image4']) ?: WPSAF_URL . '/assets/human-verification4.png'; ?>"
                                alt="human verification"/>
                        <?php endif; ?>
                    </a>
                </form>
            <?php else: ?>
                <div id="wpsafe-wait1"><?php echo wp_kses_stripslashes($code['delaytext']); ?></div>
                <div id="wpsafe-generate">
                    <a href="#wpsafegenerate" onclick="wpsafegenerate()">
                        <?php if ($wpsaf->action_type == 'button') : ?>
                            <button class="btn btn-primary"><?php echo $wpsaf->button1; ?></button>
                        <?php else : ?>
                            <img src="<?php echo !empty($code['image1']) ?: WPSAF_URL . '/assets/generate4.png'; ?>"
                                 alt="<?php echo $wpsaf->button1; ?>"/>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>

            <div><?php echo wp_kses_stripslashes($wpsaf->ads1_after); ?></div>
        </div>

        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function () {
                if (document.getElementById('wpsafelinkhuman'))
                    document.getElementById('wpsafelinkhuman').style.display = "block";
            });

            function wpsafehuman() {
                if (window.RECAPTCHA_SAFELINK && window.RECAPTCHA_SAFELINK === 'recaptcha') {
                    const response = grecaptcha.getResponse();
                    if (response.length === 0) {
                        alert("<?php echo !empty($wpsaf->recaptcha_text) ? $wpsaf->recaptcha_text : "Please complete reCAPTCHA verification"; ?>");
                        return false;
                    }
                }
                if (window.HCAPTCHA_SAFELINK && window.HCAPTCHA_SAFELINK === 'hcaptcha') {
                    const hcaptchaVal = document.getElementsByName("h-captcha-response")[0].value;
                    if (!hcaptchaVal) {
                        alert("<?php echo !empty($wpsaf->hcaptcha_text) ? $wpsaf->hcaptcha_text : "Please complete Captcha verification"; ?>");
                        return false;
                    }
                }
                document.getElementById('wpsafelink-landing').submit();
                return false;
            }
        </script>
        <?php
    }
}

/**
 * The function for integrate wp safelink into your theme at footer section
 */
function newwpsafelink_bottom()
{
    $wpsaf = json_decode(get_option('wpsaf_options'));
    $code = newpsafelink_data();
    if ($code) {
        $code['ads2'] = str_replace('\"', '"', $code['ads2']);
        ?>
        <div class="wpsafe-bottom text-center" id="wpsafegenerate">
            <div><?php echo wp_kses_stripslashes($wpsaf->ads2_before); ?></div>
            <div id="wpsafe-wait2">
                <?php if ($wpsaf->action_type == 'button') : ?>
                    <button class="btn btn-primary"><?php echo $wpsaf->button2; ?></button>
                <?php else : ?>
                    <img src="<?php echo !empty($code['image2']) ?: WPSAF_URL . '/assets/wait4.png'; ?>"
                         alt="<?php echo $wpsaf->button2 ?>" id="image2"/>
                <?php endif; ?>
            </div>
            <div id="wpsafe-link">
                <a onclick="window.open('<?php echo $code['linkr']; ?>', '_self')" rel="nofollow"
                   style="cursor:pointer">
                    <?php if ($wpsaf->action_type == 'button') : ?>
                        <button class="btn btn-primary"><?php echo $wpsaf->button3; ?></button>
                    <?php else : ?>
                        <img src="<?php echo !empty($code['image3']) ?: WPSAF_URL . '/assets/target4.png'; ?>"
                             alt="<?php echo $wpsaf->button3 ?>" id="image3"/>
                    <?php endif; ?>
                </a>
            </div>
            <div><?php echo wp_kses_stripslashes($code['ads2']); ?></div>
        </div>

        <?php if ($code['adb'] == '1') : ?>
            <div class="adb" id="adb">
                <div class="adbs">
                    <h3><?php echo $code['adb1']; ?></h3>
                    <p><?php echo $code['adb2']; ?></p>
                </div>
            </div>
        <?php endif; ?>
        <script type="text/javascript">
            let wpsafelinkCount = <?php echo $code['delay']; ?>;

            <?php if ($code['adb'] == '1') : ?>
            async function detectAdBlock() {
                let adBlockEnabled = false
                const googleAdUrl = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'
                try {
                    await fetch(new Request(googleAdUrl)).catch(_ => adBlockEnabled = true)
                } catch (e) {
                    adBlockEnabled = true
                } finally {
                    if (adBlockEnabled) adBlockDetected();
                }
            }

            detectAdBlock()

            function adBlockDetected() {
                document.getElementById("adb").setAttribute("style", "display:block");
                wpsafelinkCount = 10000;
            }
            <?php endif; ?>

            <?php if(!isset($_REQUEST['humanverification'])) : ?>
            let wpsafelinkCounter = setInterval(timer, 1000);

            function timer() {
                wpsafelinkCount = wpsafelinkCount - 1;
                if (wpsafelinkCount <= 0) {
                    document.getElementById('wpsafe-wait1').style.display = 'none';
                    document.getElementById('wpsafe-generate').style.display = 'block';
                    clearInterval(wpsafelinkCounter);
                    return;
                }
                document.getElementById("wpsafe-time").innerHTML = wpsafelinkCount;
            }

            function wpsafegenerate() {
                document.getElementById('wpsafegenerate').focus();
                document.getElementById('wpsafe-link').style.display = 'none';
                document.getElementById('wpsafe-wait2').style.display = 'block';

                setInterval(function () {
                    document.getElementById('wpsafe-wait2').style.display = 'none';
                }, 2000);
                setInterval(function () {
                    document.getElementById('wpsafe-link').style.display = 'block';
                }, 2000);
            }
            <?php endif; ?>
        </script>
        <?php
    }
}

/**
 * Detect client ip address
 *
 * @return array|false|string
 */
function wpSafelinkGetClientIP()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


/**
 * Auto integrate to popular theme
 *
 */
add_action('generate_after_header', 'newwpsafelink_top', 1, 10);
add_action('generate_before_footer', 'newwpsafelink_bottom', 1, 10);