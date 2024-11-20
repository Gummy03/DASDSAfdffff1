<?php
/*
	@package : themeson.com
	Author : Themeson
	Don't touch baby!
 */

$plugin_data = get_plugin_data(WPSAF_FILE);

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<style>
    ul.wpsafmenu {
        background: #fff;
        padding: 0 10px;
        border-bottom: 1px solid #d1d2d3;
    }

    ul.wpsafmenu li {
        list-style: none;
        display: inline-block;
        padding-top: 8px;
        margin: 0 5px 0 0;
    }

    ul.wpsafmenu li span {
        font-size: 14px;
        padding: 10px 15px;
        text-decoration: none;
        display: block;
        outline: 0;
        cursor: pointer;
        -webkit-border-radius: 12px;
        -moz-border-radius: 12px;
        border-radius: 5px 5px 0 0;
        margin-bottom: -1px
    }

    ul.wpsafmenu li span.actived {
        background: #f1f2f3;
        font-weight: bold;
        border: 1px solid #d1d2d3;
        border-bottom: 1px solid #f1f2f3;
    }

    ul.wpsafmenu li a:active {
        outline: none;
    }

    ul.wpsafmenu li #human {
        position: relative;
        padding-top: 5px;
    }

    ul.wpsafmenu li strong {
        position: absolute;
        left: 0px;
        bottom: -2px;
        font-size: 10px;
        color: red;
    }

    a:active {
        outline: none;
    }

    #safe_lists a {
        text-decoration: none;
        color: #000;
    }

    #safe_lists td {
        position: relative;
    }

    a.elips {
        width: auto;
        max-width: 100%;
        position: absolute;
        left: 10px;
        right: 10px;
        top: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<div class="wrap">
    <h1>
        <?php echo $plugin_data['Name']; ?> <small><code>version <?php echo $plugin_data['Version']; ?></code></small>

        <?php
        $token = [];
        if ($cek) {
            $token = json_decode(json_encode($wpsaf), true);
            $token['license'] = $this->ceklis('', true);
        }
        $token['domain'] = get_bloginfo('url');
        ?>
        <a href="https://themeson.com/support"
           data-link="https://themeson.com/support?token=<?php echo $this->encrypt_link(json_encode($token)); ?>"
           class="button button-primary">Help Support &raquo;</a>
    </h1>
    <p><?php echo $plugin_data['Description']; ?></p>

    <?php if ($cek) : ?>
        <h2 class="nav-tab-wrapper" style="margin-bottom: 20px;">
            <a href="admin.php?page=wp-safelink&tb=" id="generate"
               class="nav-tab <?php if (!isset($_GET['tb']) || (isset($_GET['tb']) && $_GET['tb'] == '')) echo 'nav-tab-active'; ?>">Generate Link</a>
            <a href="admin.php?page=wp-safelink&tb=autog" id="autog"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'autog') echo 'nav-tab-active'; ?>">Auto Generate Link</a>
            <a href="admin.php?page=wp-safelink&tb=setting" id="setting"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'setting') echo 'nav-tab-active'; ?>">General Settings</a>
            <a href="admin.php?page=wp-safelink&tb=template" id="template"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'template') echo 'nav-tab-active'; ?>">Templates</a>
            <a href="admin.php?page=wp-safelink&tb=captcha" id="captcha"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'captcha') echo 'nav-tab-active'; ?>">Captcha</a>
            <a href="admin.php?page=wp-safelink&tb=campaign" id="campaign"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'campaign') echo 'nav-tab-active'; ?>">Advertisements</a>
            <a href="admin.php?page=wp-safelink&tb=adb" id="adb"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'adb') echo 'nav-tab-active'; ?>">Anti Adblock</a>
            <a href="admin.php?page=wp-safelink&tb=adlinkfly" id="adlinkfly"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'adlinkfly') echo 'nav-tab-active'; ?>">Adlinkfly</a>

            <?php do_action('wp_safelink_tab_menu'); ?>

            <a href="admin.php?page=wp-safelink&tb=lic" id="lic"
               class="nav-tab <?php if (isset($_GET['tb']) && $_GET['tb'] == 'lic') echo 'nav-tab-active'; ?>">License</a>
        </h2>
    <?php endif; ?>

    <div id="lic" <?php echo ($cek && $_GET['tb'] != 'lic') ? 'style="display:none"' : ""; ?> class="tabcon">
        <div class="wp-pattern-example">
            <h3>License</h3>
            <form action="?page=wp-safelink&tb=lic" method="post">
                <table class="form-table">
                    <tr>
                        <td width="200px" valign="top" style="padding-top:8px;">Domain</td>
                        <td><input type="text" size="40" name="domain" <?php echo 'value="' . $domen . '" readonly'; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="padding-top:8px;">License Key:</td>
                        <td><input type="text" size="40"
                                   autocomplete="off" <?php if ($cek) echo 'value="' . $this->ceklis('key') . '" readonly';
                            else echo 'name="lisensi"'; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="http://themeson.com/license" target="_blank">Get License Key</a></td>
                        <td>
                            <input type="submit" name="submit"
                                   class="button-primary" <?php if ($cek) echo 'disabled'; ?> value="Validate License">
                            &nbsp; &nbsp;

                            <?php if ($cek) { ?><input name="sub" type="submit" class="button"
                                                       value="Change License"> <?php } ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php if ($cek) : ?>
        <div id="generate" <?php if (isset($_GET['tb']) && $_GET['tb'] != '') echo 'style="display:none"'; ?> class="tabcon">
            <div class="wp-pattern-example">
                <h3>Generate Link</h3>
                <form action="?page=wp-safelink" method="post">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td><input value="" type="text" size="70" name="linkd"
                                       placeholder="https://www.google.com"/>
                                <input name="generate" type="submit" class="button button-primary button-large"
                                       value="Generate"/>
                            </td>
                        </tr>
                        <?php if (isset($generated3) && $generated3 != '') { ?>
                            <tr>
                                <td>
                                    <p><br/>Target Link : <code>
                                            <a href="<?php _e($linkd); ?>"
                                               target="_blank"><?php _e($linkd); ?></a></code></p>
                                    <p>Your Safelink : <code>
                                            <a href="<?php _e($generated3); ?>"
                                               target="_blank"><?php _e($generated3); ?></a></code>
                                        <b>OR</b> <code><a href="<?php _e($encrypt_link); ?>"
                                                           target="_blank"><?php _e($encrypt_link); ?></a></code></p>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </form>
                <div style="width:auto;padding:15px;margin:10px 0;background:#fff;">
                    <table id="safe_lists" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th width="15%">Date Added</th>
                            <th width="20%">Safelink (long)</th>
                            <th width="20%">Safelink (short)</th>
                            <th width="20%">Target URL</th>
                            <th width="5%">View</th>
                            <th width="5%">Click</th>
                            <th width="1%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <form action="" method="post">
            <div class="wp-pattern-example">
                <div id="setting" <?php if (isset($_GET['tb']) && $_GET['tb'] != 'setting') echo 'style="display:none"'; ?> class="tabcon">
                    <input name="save" type="submit" class="button button-primary button-large" value="Save"/>&nbsp;
                    <input name="reset" type="submit" class="button button-large" value="Reset"/>

                    <h3>Permalink</h3>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td width="200px"><strong>Permalink</strong></td>
                            <td>
                                <input type="radio"
                                       name="wpsaf[permalink]" <?php if ($wpsaf->permalink == 1) echo "checked"; ?>
                                       value="1" id="permalink1">
                                <label for="permalink1"><code><?php _e(home_url()); ?>/</code><input
                                            style="text-align:center" value="<?php echo $wpsaf->permalink1; ?>"
                                            type="text" size="12" name="wpsaf[permalink1]"/>
                                    <code>/safelink_code</code></label><br/>
                                <input type="radio"
                                       name="wpsaf[permalink]" <?php if ($wpsaf->permalink == 2) echo "checked"; ?>
                                       value="2" id="permalink2">
                                <label for="permalink2"><code><?php _e(home_url()); ?>/?</code><input
                                            style="text-align:center" value="<?php echo $wpsaf->permalink2; ?>"
                                            type="text" size="12" name="wpsaf[permalink2]"/>
                                    <code>=safelink_code</code></label><br/>
                                <input type="radio"
                                       name="wpsaf[permalink]" <?php if ($wpsaf->permalink == 3) echo "checked"; ?>
                                       value="3" id="permalink3">
                                <label for="permalink3"><code><?php _e(home_url()); ?>/?safelink_code</code></label>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <h3>Content </h3>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td valign="top" width="200px"><strong>Content</strong></td>
                            <td><select name="wpsaf[content]" id="cont">
                                    <?php
                                    $conts = array('Random All Posts', 'Random Spesific Post by Id');
                                    foreach ($conts as $n => $c) {
                                        $s = $n == $wpsaf->content ? 'selected' : '';
                                        echo '<option value="' . $n . '" ' . $s . '>' . $c . '</option>';
                                    }
                                    ?>
                                </select><br/>
                                <div id="contentidt" <?php if ($wpsaf->content != 1) echo 'style="display:none"'; ?>>
                                    Post ID (Separated by commas): <code>Eg: 1,20,34,45</code> <br/>
                                    <input name="wpsaf[contentid]" size="30" type="text"
                                           value="<?php echo $wpsaf->contentid; ?>"></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <h3>Second Safelink</h3>
                    <p>Now you can open the second safelink for more convert the ads</p>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td valign="top" width="200px"><strong>Second Safelink URL (with http:// or
                                    https://)</strong></td>
                            <td>
                                <input type="text" value="<?php echo $wpsaf->second_safelink_url; ?>"
                                       placeholder="Place your another safelink website with full url"
                                       name="wpsaf[second_safelink_url]" id="second_safelink_url" class="regular-text">
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <h3>Delete all data</h3>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td valign="" width="200px"><b>Delete all data when wp safelink is delete</b></td>
                            <td>
                                <input <?php if ($wpsaf->delete == 1) echo 'checked'; ?> type="radio"
                                                                                         name="wpsaf[delete]" value="1"
                                                                                         id="delete1"><label
                                        for="delete1">Yes</label>
                                <input <?php if (empty($wpsaf->delete) || $wpsaf->delete == 2) echo 'checked'; ?>
                                        type="radio" name="wpsaf[delete]" value="2" id="delete0"><label
                                        for="delete0">No</label>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <h3>WP Safelink Client Integrator</h3>
                    <p>You can download the plugin <a href="https://themeson.com/member/" target="_blank">WP Safelink
                            (Client Version)</a> at our member area</p>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td width="200px"><strong>Copy this code and paste into WP Safelink Client version to
                                    integrate your client site</strong></td>
                            <td><textarea rows="10" name="code_integrator" readonly="readonly"
                                          onclick="this.focus();this.select()" class="large-text code"><?php
                                    $wpsaf->license = $this->ceklis('', true);
                                    $code = base64_encode(json_encode($wpsaf));
                                    echo $code;
                                    ?></textarea></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="template-content" <?php if (isset($_GET['tb']) && $_GET['tb'] != 'template') echo 'style="display:none"'; ?>
                     class="tabcon">
                    <input name="save" type="submit" class="button button-primary button-large" value="Save"/>&nbsp;
                    <input name="reset" type="submit" class="button button-large" value="Reset"/>
                    <h3>Template </h3>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td width="200px"><strong>Template</strong></td>
                            <td><select name="wpsaf[template]">
                                    <?php $temps = glob(WPSAF_DIR . 'template/*.php');
                                    foreach ($temps as $t) {
                                        $t = explode('/', $t);
                                        $t = $t[count($t) - 1];
                                        $t = str_replace('.php', '', $t);
                                        $s = $wpsaf->template == $t ? 'selected' : '';
                                        echo '<option value="' . $t . '" ' . $s . '>' . $t . '</option>';
                                    }
                                    ?></select>
                            </td>
                        </tr>
                        <tr class="template-disclaimer">
                            <td colspan="2" style="background: #FFF9C4;border: 1px solid #ddd;">
                                <p style="color: red"><strong>If you are using template2 or template3 you need to
                                        integrate WP Safelink Functions into your template</strong></p>
                                <p><strong>1.</strong> Paste this code above at header.php on your website : <code>&lt;?php
                                        if(function_exists('newwpsafelink_top')) newwpsafelink_top();?&gt;</code></p>
                                <p><strong>2.</strong> Paste this code bellow on at footer.php your website : <code>&lt;?php
                                        if(function_exists('newwpsafelink_bottom')) newwpsafelink_bottom();?&gt;</code>
                                </p>
                                <p>For tutorial you can check this link <a
                                            href="https://kb.themeson.com/knowledge-base/integrate-wp-safelink-to-custom-theme"
                                            target="_blank">Integrate WP Safelink to Custom Theme
                                    </a></p>
                            </td>
                        </tr>
                        <tr class="template-3-only">
                            <td valign="" width="200px"><b>Skip verification page (For template3)</b></td>
                            <td>
                                <input <?php if ($wpsaf->skipverification == 1) echo 'checked'; ?> type="radio"
                                                                                                   name="wpsaf[skipverification]"
                                                                                                   value="1"
                                                                                                   id="skipverification1"><label
                                        for="skipverification1">Yes</label>
                                <input <?php if ($wpsaf->skipverification == 2) echo 'checked'; ?> type="radio"
                                                                                                   name="wpsaf[skipverification]"
                                                                                                   value="2"
                                                                                                   id="skipverification0"><label
                                        for="skipverification0">No</label>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Time Delay</strong></td>
                            <td><input value="<?php echo $wpsaf->delay; ?>" type="number" min="0" max="99"
                                       name="wpsaf[delay]"/> seconds
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top"><b>Time Delay Display Text</b></td>
                            <td>
                                <textarea type="text" placeholder="" size="40" name="wpsaf[delaytext]" rows="4"
                                          class="large-text code"><?php echo $wpsaf->delaytext; ?></textarea>
                                <p>*Use syntax <code>{time}</code></p>
                                <p>*HTML Support</p>
                            </td>
                        </tr>
                        <tr class="template-1-only">
                            <td><b>Logo Image</b></td>
                            <td>
                                <input type="text" value="<?php echo $wpsaf->logo; ?>" name="wpsaf[logo]" id="logo"
                                       class="regular-text">
                                <input type="button" name="upload-btn" id="upload-logo" class="logo button-secondary"
                                       value="Upload Image">
                            </td>
                        </tr>
                        <tr class="template-1-only">
                            <td></td>
                            <td><img src="<?php _e($wpsaf->logo); ?>" style="max-width:300px;max-height:100px;"
                                     id="preview-logo"></td>
                        </tr>
                        <tr>
                          <td width="200px"><strong>Action Button Type</strong></td>
                          <td><select name="wpsaf[action_type]" class="action_type">
                                    <option value="image" <?php echo $wpsaf->action_type == 'image' ? 'selected' : ''; ?>>Image</option>
                                    <option value="button" <?php echo $wpsaf->action_type == 'button' ? 'selected' : ''; ?>>Button</option>
                            </select>
                          </td>
                        </tr>
                        <tr class="button-button-only">
                          <td><b>Image Button (Human Verification)</b></td>
                          <td><input value="<?php echo $wpsaf->button4 ?: 'IM NOT ROBOT'; ?>" type="text" name="wpsaf[button4]"/></td>
                        </tr>
                        <tr class="button-button-only">
                          <td><b>Image Button 1 (Generate Link)</b></td>
                          <td><input value="<?php echo $wpsaf->button1 ?: 'CLICK 2X FOR GENERATE LINK'; ?>" type="text" name="wpsaf[button1]"/></td>
                        </tr>
                        <tr class="button-button-only">
                          <td><b>Image Button 2 (Please Wait)</b></td>
                          <td><input value="<?php echo $wpsaf->button2 ?: 'PLEASE WAIT ...'; ?>" type="text" name="wpsaf[button2]"/></td>
                        </tr>
                        <tr class="button-button-only">
                          <td><b>Image Button 3 (Target Link)</b></td>

                          <td><input value="<?php echo $wpsaf->button3 ?: 'DOWNLOAD LINK'; ?>" type="text" name="wpsaf[button3]"/></td>
                        </tr>

                        <tr class="button-image-only">
                            <td><b>Image Button (Human Verification)</b></td>
                            <td>
                                <input type="text"
                                       value="<?php echo !empty($wpsaf->image4) ? $wpsaf->image4 : WPSAF_URL . '/assets/human-verification4.png'; ?>"
                                       name="wpsaf[image4]" id="image4" class="regular-text">
                                <input type="button" name="upload-btn" id="upload-btn4" class="image4 button-secondary"
                                       value="Upload Image">
                            </td>
                        </tr>
                        <tr class="button-image-only">
                            <td></td>
                            <td>
                                <img src="<?php echo !empty($wpsaf->image4) ? $wpsaf->image4 : WPSAF_URL . '/assets/human-verification4.png'; ?>"
                                     style="max-width:300px;max-height:100px;" id="preview-image4"></td>
                        </tr>
                        <tr class="button-image-only">
                            <td><b>Image Button 1 (Generate Link)</b></td>
                            <td>
                                <input type="text" value="<?php echo $wpsaf->image1; ?>" name="wpsaf[image1]"
                                       id="image1" class="regular-text">
                                <input type="button" name="upload-btn" id="upload-btn1" class="image1 button-secondary"
                                       value="Upload Image">
                            </td>
                        </tr>
                        <tr class="button-image-only">
                            <td></td>
                            <td><img src="<?php _e($wpsaf->image1); ?>" style="max-width:300px;max-height:100px;"
                                     id="preview-image1"></td>
                        </tr>
                        <tr class="button-image-only">
                            <td><b>Image Button 2 (Please Wait)</b></td>
                            <td>
                                <input type="text" value="<?php _e($wpsaf->image2); ?>" name="wpsaf[image2]" id="image2"
                                       class="regular-text">
                                <input type="button" name="upload-btn" id="upload-btn2" class="image2 button-secondary"
                                       value="Upload Image">
                            </td>
                        </tr>
                        <tr class="button-image-only">
                            <td></td>
                            <td><img src="<?php _e($wpsaf->image2); ?>" style="max-width:300px;max-height:100px;"
                                     id="preview-image2"></td>
                        </tr>
                        <tr class="button-image-only">
                            <td><b>Image Button 3 (Target Link)</b></td>
                            <td>
                                <input type="text" value="<?php _e($wpsaf->image3); ?>" name="wpsaf[image3]" id="image3"
                                       class="regular-text">
                                <input type="button" name="upload-btn" id="upload-btn3" class="image3 button-secondary"
                                       value="Upload Image">
                            </td>
                        </tr>
                        <tr class="button-image-only">
                            <td></td>
                            <td><img src="<?php echo $wpsaf->image3; ?>" style="max-width:300px;max-height:100px;"
                                     id="preview-image3"></td>
                        </tr>
                        </tbody>
                    </table>

                    <script type="text/javascript">
                        jQuery(function ($) {
                            if ($('select[name="wpsaf[template]"').val() === 'template2' || $('select[name="wpsaf[template]"').val() === 'template3') {
                                $('.template-disclaimer').css('display', 'table-row');
                            } else {
                                $('.template-disclaimer').css('display', 'none');
                            }
                            if ($('select[name="wpsaf[template]"').val() === 'template3') {
                                $('.template-3-only').css('display', 'table-row');
                            } else {
                                $('.template-3-only').css('display', 'none');
                            }
                            if ($('select[name="wpsaf[template]"').val() === 'template1') {
                                $('.template-1-only').css('display', 'table-row');
                            } else {
                                $('.template-1-only').css('display', 'none');
                            }

                            $('select[name="wpsaf[template]"').change(function () {
                                var selected = $(this).val();
                                if (selected === 'template2' || selected === 'template3') {
                                    $('.template-disclaimer').css('display', 'table-row');
                                } else {
                                    $('.template-disclaimer').hide();
                                }

                                if (selected === 'template3') {
                                    $('.template-3-only').css('display', 'table-row');
                                } else {
                                    $('.template-3-only').css('display', 'none');
                                }

                                if (selected === 'template1') {
                                    $('.template-1-only').css('display', 'table-row');
                                } else {
                                    $('.template-1-only').css('display', 'none');
                                }
                            });


                            if ($('select[name="wpsaf[action_type]"').val() === 'button') {
                                $('.button-button-only').css('display', 'table-row');
                                $('.button-image-only').css('display', 'none');
                            } else {
                                $('.button-button-only').css('display', 'none');
                                $('.button-image-only').css('display', 'table-row');
                            }

                            $('select[name="wpsaf[action_type]"').change(function () {
                                var selected = $(this).val();

                                if (selected === 'button') {
                                    $('.button-button-only').css('display', 'table-row');
                                    $('.button-image-only').css('display', 'none');
                                } else {
                                    $('.button-button-only').css('display', 'none');
                                    $('.button-image-only').css('display', 'table-row');
                                }
                            });

                        });
                    </script>
                </div>
                <div id="campaign" <?php if (isset($_GET['tb']) && $_GET['tb'] != 'campaign') echo 'style="display:none"'; ?> class="tabcon">
                    <input name="save" type="submit" class="button button-primary button-large" value="Save"/>&nbsp;
                    <input name="reset" type="submit" class="button button-large" value="Reset"/>
                    <h3>Advertisement</h3>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td width="200px"><b>Advertisement Top (Before Button)<b></td>
                            <td><textarea cols="70" rows="5" name="wpsaf[ads1]"><?php _e($wpsaf->ads1); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px"><b>Advertisement Top (After Button)<b></td>
                            <td><textarea cols="70" rows="5"
                                          name="wpsaf[ads1_after]"><?php _e($wpsaf->ads1_after); ?></textarea></td>
                        </tr>
                        <tr>
                            <td><b>Advertisement Bottom (Before Button)</b></td>
                            <td><textarea cols="70" rows="5"
                                          name="wpsaf[ads2_before]"><?php _e($wpsaf->ads2_before); ?></textarea></td>
                        </tr>
                        <tr>
                            <td><b>Advertisement Bottom (After Button)</b></td>
                            <td><textarea cols="70" rows="5" name="wpsaf[ads2]"><?php _e($wpsaf->ads2); ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="captcha" <?php if (isset($_GET['tb']) && $_GET['tb'] != 'captcha') echo 'style="display:none"'; ?> class="tabcon">
                    <input name="save" type="submit" class="button button-primary button-large" value="Save"/>&nbsp;
                    <input name="reset" type="submit" class="button button-large" value="Reset"/>

                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td valign="" width="200px"><b>Captcha Provider</b></td>
                            <td>
                                <select id="captcha_provider" name="wpsaf[captcha_provider]" id="cont">
                                    <option <?php selected($wpsaf->captcha_provider, 'recaptcha'); ?> value="recaptcha">
                                        reCaptcha
                                    </option>
                                    <option <?php selected($wpsaf->captcha_provider, 'hcaptcha'); ?> value="hcaptcha">
                                        hCaptcha
                                    </option>
                                </select>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <div id="recaptcha-section"
                         style="<?php echo empty($wpsaf->captcha_provider) || $wpsaf->captcha_provider == 'recaptcha' ? 'display: block;' : 'display: none;'; ?>">
                        <h3>reCAPTCHA v2</h3>
                        <p>You can get the recaptcha site key and secret key from <a
                                    href="https://www.google.com/recaptcha/" target="_blank">https://www.google.com/recaptcha/</a>.
                        </p>
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <td valign="" width="200px"><b>Enable reCAPTCHA v2</b></td>
                                <td>
                                    <input <?php if ($wpsaf->recaptcha_enable == 1) echo 'checked'; ?> type="radio"
                                                                                                       name="wpsaf[recaptcha_enable]"
                                                                                                       value="1"
                                                                                                       id="recaptcha_enable1"><label
                                            for="recaptcha_enable1">Yes</label>
                                    <input <?php if (empty($wpsaf->recaptcha_enable) || $wpsaf->recaptcha_enable == 2) echo 'checked'; ?>
                                            type="radio" name="wpsaf[recaptcha_enable]" value="2"
                                            id="recaptcha_enable0"><label for="activerecaptcha0">No</label>
                                </td>
                            </tr>
                            <tr>
                                <td><b>reCAPTCHA Site Key</b></td>
                                <td><input value="<?php echo $wpsaf->recaptcha_site_key; ?>" type="text" placeholder=""
                                           size="40" name="wpsaf[recaptcha_site_key]"/>
                            </tr>
                            <tr>
                                <td><b>reCAPTCHA Secret Key</b></td>
                                <td><input value="<?php echo $wpsaf->recaptcha_secret_key; ?>" type="text"
                                           placeholder="" size="40" name="wpsaf[recaptcha_secret_key]"/>
                            </tr>
                            <tr>
                                <td><b>reCAPTCHA Alert Verification Text</b></td>
                                <td>
                                    <input value="<?php echo !empty($wpsaf->recaptcha_text) ? $wpsaf->recaptcha_text : "Please complete reCAPTCHA verification"; ?>"
                                           type="text" placeholder="" size="40" name="wpsaf[recaptcha_text]"/>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="hcaptcha-section"
                         style="<?php echo $wpsaf->captcha_provider == 'hcaptcha' ? 'display: block;' : 'display: none;'; ?>">
                        <h3>hCaptcha v2</h3>
                        <p>You can get the hcaptcha site key and secret key from <a href="https://www.hcaptcha.com/"
                                                                                    target="_blank">https://www.hcaptcha.com/</a>.
                        </p>
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <td valign="" width="200px"><b>Enable hCaptcha v2</b></td>
                                <td>
                                    <input <?php if ($wpsaf->hcaptcha_enable == 1) echo 'checked'; ?> type="radio"
                                                                                                      name="wpsaf[hcaptcha_enable]"
                                                                                                      value="1"
                                                                                                      id="hcaptcha_enable1"><label
                                            for="hcaptcha_enable1">Yes</label>
                                    <input <?php if (empty($wpsaf->hcaptcha_enable) || $wpsaf->hcaptcha_enable == 2) echo 'checked'; ?>
                                            type="radio" name="wpsaf[hcaptcha_enable]" value="2"
                                            id="hcaptcha_enable0"><label for="activehcaptcha0">No</label>
                                </td>
                            </tr>
                            <tr>
                                <td><b>hCaptcha Site Key</b></td>
                                <td><input value="<?php echo $wpsaf->hcaptcha_site_key; ?>" type="text" placeholder=""
                                           size="40" name="wpsaf[hcaptcha_site_key]"/>
                            </tr>
                            <tr>
                                <td><b>hCaptcha Secret Key</b></td>
                                <td><input value="<?php echo $wpsaf->hcaptcha_secret_key; ?>" type="text" placeholder=""
                                           size="40" name="wpsaf[hcaptcha_secret_key]"/>
                            </tr>
                            <tr>
                                <td><b>hCaptcha Alert Verification Text</b></td>
                                <td>
                                    <input value="<?php echo !empty($wpsaf->hcaptcha_text) ? $wpsaf->hcaptcha_text : "Please complete Captcha verification"; ?>"
                                           type="text" placeholder="" size="40" name="wpsaf[hcaptcha_text]"/>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <script type="text/javascript">
                        jQuery(function ($) {
                            $('#captcha_provider').change(function () {
                                var val = $('#captcha_provider').val();
                                if (val == 'recaptcha') {
                                    $('#recaptcha-section').show();
                                    $('#hcaptcha-section').hide();
                                } else if (val == 'hcaptcha') {
                                    $('#hcaptcha-section').show();
                                    $('#recaptcha-section').hide();
                                }
                            })
                        });
                    </script>
                </div>
                <div id="adb" <?php if (isset($_GET['tb']) && $_GET['tb'] != 'adb') echo 'style="display:none"'; ?> class="tabcon">
                    <input name="save" type="submit" class="button button-primary button-large" value="Save"/>&nbsp;
                    <input name="reset" type="submit" class="button button-large" value="Reset"/>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td width="200px"><b>Status</b></td>
                            <td><select name="wpsaf[adb]">
                                    <option value="1" <?php if ($wpsaf->adb == 1) echo 'selected'; ?>>Enabled</option>
                                    <option value="2" <?php if ($wpsaf->adb == 2) echo 'selected'; ?>>Disabled</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><b>Header text 1<b></td>
                            <td><textarea cols="70" rows="5" name="wpsaf[adb1]"><?php _e($wpsaf->adb1); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Header text 2</b></td>
                            <td><textarea cols="70" rows="5" name="wpsaf[adb2]"><?php _e($wpsaf->adb2); ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="adb" <?php if (isset($_GET['tb']) && $_GET['tb'] != 'adlinkfly') echo 'style="display:none"'; ?> class="tabcon">
                    <input name="save" type="submit" class="button button-primary button-large" value="Save"/>&nbsp;
                    <input name="reset" type="submit" class="button button-large" value="Reset"/>

                    <h3>Adlinkfly Integration</h3>

                    <div style="background: #FFF9C4;border: 1px solid #ddd;padding: 20px;">
                      <p style="font-weight: bold;">Full tutorial you can search on youtube at <a href="https://themeson.com/tutorial-adlinkfly" target="_blank">https://themeson.com/tutorial-adlinkfly</a> and
                        for demo purpose you can check this link <a href="https://demo-adlinkfly.themeson.com" target="_blank">https://demo-adlinkfly.themeson.com</a>
                      </p>
                    </div>

                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td style="width: 200px"><b>Adlinkfly URL</b></td>
                            <td>
                                <input value="<?php echo $wpsaf->adlinkfly_url; ?>" type="text" placeholder=""
                                       size="40" name="wpsaf[adlinkfly_url]"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 200px"><b>htaccess File Config</b></td>
                            <td>
                               <textarea readonly class="regular-text" rows="5">RewriteEngine On
RewriteBase /
RewriteRule ^(.*)$ <?php bloginfo('url'); ?>/?adlinkfly=$1 [L,R=301,NC]</textarea>
                                <p>Paste at your Default Short URL Domain at cPanel</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="autog" <?php if (isset($_GET['tb']) && $_GET['tb'] != 'autog') echo 'style="display:none"'; ?> class="tabcon">
                    <input name="save" type="submit" class="button button-primary button-large" value="Save"/>&nbsp;
                    <input name="reset" type="submit" class="button button-large" value="Reset"/><br><br>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <td valign="" width="200px"><b>Auto Convert Link</b></td>
                            <td>
                                <input <?php if ($wpsaf->autoconvert == 1) echo 'checked'; ?> type="radio"
                                                                                              name="wpsaf[autoconvert]"
                                                                                              value="1"
                                                                                              id="autoconvert1"><label
                                        for="autoconvert1">Yes</label>
                                <input <?php if (empty($wpsaf->autoconvert) || $wpsaf->autoconvert == 2) echo 'checked'; ?>
                                        type="radio" name="wpsaf[autoconvert]" value="2" id="autoconvert0"><label
                                        for="autoconvert0">No</label>
                            </td>
                        </tr>
                        <tr>
                            <td valign="" width="200px"><b>Auto Save Safelink</b></td>
                            <td>
                                <input <?php if ($wpsaf->autosave == 1) echo 'checked'; ?> type="radio"
                                                                                           name="wpsaf[autosave]"
                                                                                           value="1"
                                                                                           id="autosave1"><label
                                        for="autosave1">Active</label>
                                <input <?php if ($wpsaf->autosave == 2) echo 'checked'; ?> type="radio"
                                                                                           name="wpsaf[autosave]"
                                                                                           value="2"
                                                                                           id="autosave0"><label
                                        for="autosave0">Non-Active</label>
                            </td>
                        </tr>
                        <tr>
                            <td valign="" width="200px"><b>Base URL Safelink</b></td>
                            <td valign="top">
                                <input value="<?php echo(empty($wpsaf->base_url) ? get_bloginfo('url') . '/' : $wpsaf->base_url); ?>"
                                       type="text" name="wpsaf[base_url]" class="regular-text" readonly/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="color:red;font-weight: bold;">WARNING: IF YOU ARE USING CLOUDFLARE MAKE SURE
                                    YOU CLEAR THE CLOUDFLARE CACHE AFTER CHANGE THE INCLUDE DOMAIN LIST OR EXCLUDE
                                    DOMAIN LIST</p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="" width="200px"><b>Auto Convert Link Method</b></td>
                            <td>
                                <input <?php if (empty($wpsaf->autoconvertmethod) || $wpsaf->autoconvertmethod == "include") echo 'checked'; ?>
                                        type="radio" name="wpsaf[autoconvertmethod]" value="include"
                                        id="autoconvertmethod1"><label for="autoconvertmethod1">Include Domain</label>
                                <input <?php if ($wpsaf->autoconvertmethod == "exclude") echo 'checked'; ?> type="radio"
                                                                                                            name="wpsaf[autoconvertmethod]"
                                                                                                            value="exclude"
                                                                                                            id="autoconvertmethod0"><label
                                        for="autoconvertmethod0">Exclude Domain</label>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" width="200px"><br/><b>Include Domain List </b><br/><small>one per
                                    line</small></td>
                            <td><textarea cols="70" rows="10" name="wpsaf[domain]"
                                          placeholder="Insert your protected url and separate with enter (one domain one line). Eg : zippyshare.com"><?php _e($wpsaf->domain); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" width="200px"><br/><b>Exclude Domain List </b><br/><small>one per
                                    line</small></td>
                            <td><textarea cols="70" rows="10" name="wpsaf[exclude_domain]"
                                          placeholder="Insert your exclude url, BEWARE it will automatically generate all links on your site and separate with enter (one domain one line). Eg : zippyshare.com"><?php _e($wpsaf->exclude_domain); ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h3>How to use</h3>
                    <p>Place this code before the <b>&lt;/body&gt;</b> tag</p>
                    <p style="color:red;"><code>&lt;script src="<?php echo home_url() ?>/wpsafelink.js"&gt;&lt;/script&gt;</code>
                    </p>

                    <h3>Auto Generate Link</h3>
                    <table class="form-table">
                        <tr>
                            <td width="200px">
                                <h4>Shortcode</h4>
                            </td>
                            <td><code>[wpsafelink=<span style="color:red">your-download-link</span>]</code>
                                eg: <code>[wpsafelink=<span style="color:red">http://www.google.com</span>]</code><br/>
                                <h4>Example:</h4><code>&lt;a href="[wpsafelink=<span style="color:red">http://www.google.com</span>]"&gt;Download
                                    Disini&lt;/a&gt;</code>
                                <br/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px">
                                <h4>PHP Code</h4>
                            </td>
                            <td><code>&lt;?php echo do_shortcode('[wpsafelink=<span
                                            style="color:red">your-download-link</span>]'); ?&gt;</code>
                                eg: <code>&lt;?php echo do_shortcode('[wpsafelink=<span style="color:red">http://www.google.com</span>]');
                                    ?&gt;</code>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php do_action('wp_safelink_tab_content', $wpsaf); ?>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php
wp_enqueue_media();
?>
<?php if (empty(isset($_GET['tb']) && $_GET['tb'])) : ?>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            $('#safe_lists').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [[0, "desc"]],
                'ajax': '<?php echo admin_url('admin-ajax.php'); ?>?action=wpsafelink_generate_link&_wpnonce=<?php echo wp_create_nonce('wpsafelink_nonce'); ?>'
            });
        });
    </script>
<?php endif; ?>
<script>
    jQuery(document).ready(function ($) {
        $(".wpsafmenu span").click(function () {
            var idm = $(this).attr('id');
            var idm = idm.replace("#", "");
            $(".wpsafmenu span").removeClass('actived');
            $(".wpsafmenu span#" + idm).addClass('actived');
            $("div.tabcon").hide();
            $("div#" + idm).show();
            return false;
        });
        $('#cont').on('change', function () {
            var va = this.value;
            if (va == 1) {
                $("#contentidt").show();
            } else {
                $("#contentidt").hide();
            }
        })
        $('#upload-btn1').click(function (e) {
            var wsclass = $(this).attr('class');
            var wsclass = wsclass.split(' ')[0];
            e.preventDefault();
            var image = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    console.log(uploaded_image);
                    var image_url = uploaded_image.toJSON().url;
                    $('#' + wsclass).val(image_url);
                    $('#preview-' + wsclass).attr("src", image_url);
                });
        });
        $('#upload-btn2').click(function (e) {
            var wsclass = $(this).attr('class');
            var wsclass = wsclass.split(' ')[0];
            e.preventDefault();
            var image = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    console.log(uploaded_image);
                    var image_url = uploaded_image.toJSON().url;
                    $('#' + wsclass).val(image_url);
                    $('#preview-' + wsclass).attr("src", image_url);
                });
        });
        $('#upload-btn3').click(function (e) {
            var wsclass = $(this).attr('class');
            var wsclass = wsclass.split(' ')[0];
            e.preventDefault();
            var image = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    console.log(uploaded_image);
                    var image_url = uploaded_image.toJSON().url;
                    $('#' + wsclass).val(image_url);
                    $('#preview-' + wsclass).attr("src", image_url);
                });
        });
        $('#upload-btn4').click(function (e) {
            var wsclass = $(this).attr('class');
            var wsclass = wsclass.split(' ')[0];
            e.preventDefault();
            var image = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    console.log(uploaded_image);
                    var image_url = uploaded_image.toJSON().url;
                    $('#' + wsclass).val(image_url);
                    $('#preview-' + wsclass).attr("src", image_url);
                });
        });
        $('#upload-logo').click(function (e) {
            var wsclass = $(this).attr('class');
            var wsclass = wsclass.split(' ')[0];
            e.preventDefault();
            var image = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    console.log(uploaded_image);
                    var image_url = uploaded_image.toJSON().url;
                    $('#' + wsclass).val(image_url);
                    $('#preview-' + wsclass).attr("src", image_url);
                });
        });

        $('a').click(function (e) {
            var href = $(this).attr('data-link');
            if (href) {
                e.preventDefault();

                window.open(href, '_blank').focus();
            }
        })
    });
</script>