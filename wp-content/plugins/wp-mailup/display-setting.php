<?php
if (is_user_logged_in()) {
    $image_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . 'images/';
    $wpmailup = unserialize(get_option('wpmailup'));

    if(isset($_POST['feedbacksubmit'])){
        $feedbackContent = "<style>table{border-collapse: collapse; font-size: 12px;} th{ border: 1px solid rgba(0, 0, 0, .5); padding: 10px; text-align: left;} td{ border: 1px solid rgba(0, 0, 0, .5); padding: 10px;}</style>";
        $feedbackContent .= "<h1>Segnalazione errore plugin mailup</h1><br/><table><tr><th>Chiave</th><th>Valore</th></tr>";
        $feedbackFields = array("error", "event", "os", "browser");
        foreach($feedbackFields as $feedbackField){
            $feedbackContent .= "<tr>";
            if(isset($_POST[$feedbackField])){
                $feedbackContent .= "<td>{$feedbackField}</td><td>{$_POST[$feedbackField]}</td>";
            }
            $feedbackContent .= "</tr>";
        }
        $phpVersion = phpversion();
        $feedbackContent .= "<tr><td>php version</td><td>{$phpVersion}</td></tr>";
        $currentUser = wp_get_current_user();
        if($currentUser) {
            $feedbackContent .= "<tr><td>User id</td><td>{$currentUser->ID}</td></tr>";
            $feedbackContent .= "<tr><td>User name</td><td>{$currentUser->display_name}</td></tr>";
        } else {
            $feedbackContent .= "<tr><td>User id</td><td>User not found</td></tr>";
        }
        $canManageOptions = current_user_can( 'manage_options' ) ? "SI" : "NO";
        $feedbackContent .= "<tr><td>Can manage options</td><td>{$canManageOptions}</td></tr>";
	$pluginData = get_plugin_data(realpath(dirname(__FILE__) . "/wp-mailup.php"));
        $feedbackContent .= "<tr><td>Mailup version</td><td>{$pluginData["Version"]}</td></tr>";
        $feedbackContent .= "</table>";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        wp_mail( "api@mailup.com", "Segnalazione errore plugin mailup", $feedbackContent, $headers );
    }
} else {
    exit('<pre>NOT AUTHORIZED</pre>');
}
if(!current_user_can('manage_options')) {
    ?>
    <div class="wrap">
        <h2><?php _e('WP-MailUp Settings'); ?></h2>
        <!--<div class="updated below-h2" id="message">
    	<?php _e('Message sows here.'); ?>
    </div>-->
        <div class="wrap-content">
            <?php _e('You need to be administrator to edit wp mailup settings.'); ?>
        </div>
    </div>
    <?php

} else {
    ?>
    <div class="wrap">
        <h2><?php _e('WP-MailUp Settings'); ?></h2>
        <!--<div class="updated below-h2" id="message">
    	<?php _e('Message sows here.'); ?>
    </div>-->
        <div class="wrap-content">

            <form style="line-height: 30px; background-color: #c0392b; display: none; color: #fff; padding: 5px 15px;"
                  id="feedback" action="" method="post">
                <?php _e("Something is not working, please send use analytical data so we can fix the problem"); ?>
                <input type="hidden" name="error" value="">
                <input type="hidden" name="event" value="">
                <input type="hidden" name="os" value="">
                <input type="hidden" name="browser" value="">
                <button name="feedbacksubmit" class="button"><?php _e("Send"); ?></button>
            </form>
            <?php
            if (isset($_POST['feedbacksubmit'])) {
                ?>
                <p style="line-height: 30px; background-color: #27ae60; color: #fff; padding: 5px 15px;">
                    <?php _e("Thanks for feedback"); ?>
                </p>
                <?php
            }
            ?>
            <form name="mailUpSettingForm" id="mailUpSettingForm" action="" method="post" onsubmit="return false;">
                <div id="firstpane" class="menu_list">
                    <h3>
					<span style="vertical-align:middle;display:inline-block;float:left;">

                    <span class="about-mailup">
                    	Configure your newsletter subscription signup form, then manage your campaigns in <a
                            href="http://www.mailup.com?utm_source=wordpress&utm_medium=textlink&utm_campaign=plugin"
                            target="_blank">MailUp</a>.<br>
                    	<a href="http://help.mailup.com/display/mailupUserGuide/WordPress?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-mailup-settings"
                           target="_blank">Video Tutorials</a>
                    	&nbsp;|&nbsp;
                    	<a href="http://lp.mailup.com/en/free-trial/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-mailup-settings"
                           target="_blank">Free Trial</a>
                    	&nbsp;|&nbsp;
                    	<a href="http://www.mailup.com?utm_source=wordpress&utm_medium=textlink&utm_campaign=plugin"
                           target="_blank">About MailUp</a>
                    </span>

                    </span>
                        <img border="0" style="display:inline-block;float:right; margin-top:-40px;"
                             src="<?php echo $image_path; ?>Logo_transparent.png"/>
                        <br class="clear"/>
                    </h3>
                    <p class="menu_head">
                        <?php _e('General settings'); ?>
                        <img class="help_icon" src="<?php echo $image_path; ?>HelpIcon.gif"/>
                        <img class="toggle_icon" src="<?php echo $image_path; ?>bullet_toggle_plus.png"/>
                        <br class="clear"/>
                    </p>
                    <div class="menu_body">
                        <table class="setting-table setting-general">
                            <tbody>
                            <tr>
                                <td colspan="2">To use this plugin, you need a MailUp account (<a
                                        href="http://lp.mailup.com/en/free-trial/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-mailup-settings"
                                        target="_blank" class="nostyle">free trial</a>) and your <a
                                        href="http://help.mailup.com/display/mailupapi/MailUp+API+Credentials"
                                        target="_blank" class="nostyle">MailUp API credentials</a>.
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label for="console-host"><?php _e('MailUp console host'); ?>
                                        : </label></td>
                                <td class="setting-target"><input name="console-host" id="console-host"
                                                                  class="setting-text general-data" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label for="subscribe-path"><?php _e('Subscribe path'); ?>
                                        : </label></td>
                                <td class="setting-target"><input name="subscribe-path" id="subscribe-path"
                                                                  class="setting-text  general-data" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label for="plugin-title"><?php _e('Plugin title'); ?>
                                        : </label>
                                </td>
                                <td class="setting-target"><input name="plugin-title" id="plugin-title"
                                                                  class="setting-text  general-data" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label for="plugin-description"><?php _e('Description'); ?>
                                        : </label></td>
                                <td class="setting-target"><input name="plugin-description" id="plugin-description"
                                                                  class="setting-text  general-data" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label><?php _e('Submit button'); ?>: </label></td>
                                <td class="setting-target"><input name="submit-button" id="submit-button"
                                                                  class="setting-text  general-data" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label
                                        for="css-combination"><?php _e('Select preloaded CSS for widget'); ?>: </label>
                                </td>
                                <td class="setting-target">
                                    <select name="css-combination" id="css-combination" class="select-box">
                                        <option value="style1">Default</option>
                                        <option value="style5">Compact</option>
                                        <option value="style2">Rounded corners</option>
                                        <option value="style3">Rounded corners black</option>
                                        <option value="style6">MailUp green</option>
                                        <option value="style7">MailUp orange</option>
                                        <option value="style4">No style</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label
                                        for="text-inside"><?php _e('Use placeholders instead of labels'); ?>:</label>
                                </td>
                                <td class="setting-target"><input name="text-inside" id="text-inside" value="yes"
                                                                  class="setting-checkbox" type="checkbox"/></td>
                            </tr>

                            <tr>
                                <td class="setting-label"><label
                                        for="request-confirm"><?php _e('Request confirmation by email'); ?>:</label>
                                </td>
                                <td class="setting-target"><input name="request-confirm" id="request-confirm"
                                                                  value="yes"
                                                                  class="setting-checkbox" type="checkbox"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label
                                        for="wpml"><?php _e('I am using WPML to translate this website'); ?>:</label>
                                </td>
                                <td class="setting-target"><input name="wpml" id="wpml" value="yes"
                                                                  class="setting-checkbox"
                                                                  type="checkbox"/></td>
                            </tr>

                            </tbody>
                        </table>
                        <div style="clear:both;"></div>
                    </div>
                    <p class="menu_head">
                        <?php _e('Recipient list'); ?>
                        <img class="help_icon" src="<?php echo $image_path; ?>HelpIcon.gif"/>
                        <img class="toggle_icon" src="<?php echo $image_path; ?>bullet_toggle_plus.png"/>
                        <br class="clear"/>
                    </p>
                    <div class="menu_body">
                        <table class="setting-table setting-userlists">
                            <tbody>
                            <tr>
                                <td colspan="2"><?php _e('Site visitors will be subscribed to the following list:'); ?></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label for="list-id"><?php _e('List ID'); ?>:</label></td>
                                <td class="setting-target"><input name="list-id" id="list-id" class="setting-text"
                                                                  type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label
                                        for="list-displayed-name"><?php _e('Displayed list name'); ?>:</label></td>
                                <td class="setting-target"><input name="list-displayed-name" id="list-displayed-name"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label><?php _e('Group ID'); ?>:</label></td>
                                <td class="setting-target"><input name="group-id" id="group-id" class="setting-text"
                                                                  type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="clear:both;"></div>
                    </div>
                    <p class="menu_head">
                        <?php _e('Recipient fields'); ?>
                        <img class="help_icon" src="<?php echo $image_path; ?>HelpIcon.gif"/>
                        <img class="toggle_icon" src="<?php echo $image_path; ?>bullet_toggle_plus.png"/>
                        <br class="clear"/>
                    </p>
                    <div class="menu_body">
                        <table class="setting-table setting-userfields" style="width: 650px;">
                            <tbody>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Show</th>
                                <th>Req.</th>
                                <th>Field code</th>
                                <th>Displayed name</th>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Email:</label></td>
                                <td class="setting-target"><input name="email-show" id="email-show"
                                                                  class="setting-checkbox"
                                                                  type="checkbox" value="yes"/></td>
                                <td class="setting-target"><input name="email-required" id="email-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input readonly="readonly" name="email-fieldcode"
                                                                  id="email-fieldcode" class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>" value="Email"/>
                                </td>
                                <td class="setting-target"><input name="email-displayed-name" id="email-displayed-name"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Mobile number:</label></td>
                                <td class="setting-target"><input name="mobile-show" id="mobile-show"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="mobile-required" id="mobile-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input readonly="readonly" name="mobile-fieldcode"
                                                                  id="mobile-fieldcode" class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>" value="sms"/>
                                </td>
                                <td class="setting-target"><input name="mobile-displayed-name"
                                                                  id="mobile-displayed-name"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Custom field 1:</label></td>
                                <td class="setting-target"><input name="extfield1-show" id="extfield1-show"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield1-required" id="extfield1-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield1-fieldcode" id="extfield1-fieldcode"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                                <td class="setting-target"><input name="extfield1-displayed-name"
                                                                  id="extfield1-displayed-name" class="setting-text"
                                                                  type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Custom field 2:</label></td>
                                <td class="setting-target"><input name="extfield2-show" id="extfield2-show"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield2-required" id="extfield2-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield2-fieldcode" id="extfield2-fieldcode"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                                <td class="setting-target"><input name="extfield2-displayed-name"
                                                                  id="extfield2-displayed-name" class="setting-text"
                                                                  type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Custom field 3:</label></td>
                                <td class="setting-target"><input name="extfield3-show" id="extfield3-show"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield3-required" id="extfield3-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield3-fieldcode" id="extfield3-fieldcode"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                                <td class="setting-target"><input name="extfield3-displayed-name"
                                                                  id="extfield3-displayed-name" class="setting-text"
                                                                  type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Custom field 4:</label></td>
                                <td class="setting-target"><input name="extfield4-show" id="extfield4-show"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield4-required" id="extfield4-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield4-fieldcode" id="extfield4-fieldcode"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                                <td class="setting-target"><input name="extfield4-displayed-name"
                                                                  id="extfield4-displayed-name" class="setting-text"
                                                                  type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Custom field 5:</label></td>
                                <td class="setting-target"><input name="extfield5-show" id="extfield5-show"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield5-required" id="extfield5-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="extfield5-fieldcode" id="extfield5-fieldcode"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                                <td class="setting-target"><input name="extfield5-displayed-name"
                                                                  id="extfield5-displayed-name" class="setting-text"
                                                                  type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-target"><label>Date Picker:</label></td>
                                <td class="setting-target"><input name="date-show" id="date-show"
                                                                  class="setting-checkbox"
                                                                  type="checkbox" value="yes"/></td>
                                <td class="setting-target"><input name="date-required" id="date-required"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                                <td class="setting-target"><input name="date-fieldcode" id="date-fieldcode"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                                <td class="setting-target"><input name="date-displayed-name" id="date-displayed-name"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>

                            </tbody>
                        </table>
                        <div style="clear:both;"></div>
                    </div>
                    <p class="menu_head">
                        <?php _e('Messages'); ?>
                        <img class="help_icon" src="<?php echo $image_path; ?>HelpIcon.gif"/>
                        <img class="toggle_icon" src="<?php echo $image_path; ?>bullet_toggle_plus.png"/>
                        <br class="clear"/>
                    </p>
                    <div class="menu_body">
                        <table class="setting-table setting-userlists">
                            <tbody>
                            <tr>
                                <td class="setting-label"><label>Success:</label></td>
                                <td class="setting-target"><input name="success-message" id="success-message"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Generic error:</label></td>
                                <td class="setting-target"><input name="generic-error" id="generic-error"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Invalid email address:</label></td>
                                <td class="setting-target"><input name="invalid-address" id="invalid-address"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Invalid phone number:</label></td>
                                <td class="setting-target"><input name="invalid-phone" id="invalid-phone"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>

                            <tr>
                                <td class="setting-label"><label>Recipient already exists:</label></td>
                                <td class="setting-target"><input name="already-present" id="already-present"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Field required:</label></td>
                                <td class="setting-target"><input name="field-required" id="field-required"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Condition 1 not agreed to:</label></td>
                                <td class="setting-target"><input name="terms-not-agreed" id="terms-not-agreed"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Condition 2 not agreed to:</label></td>
                                <td class="setting-target"><input name="terms2-not-agreed" id="terms2-not-agreed"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="clear:both;"></div>
                    </div>
                    <p class="menu_head">
                        <?php _e('Terms and conditions'); ?>
                        <img class="help_icon" src="<?php echo $image_path; ?>HelpIcon.gif"/>
                        <img class="toggle_icon" src="<?php echo $image_path; ?>bullet_toggle_plus.png"/>
                        <br class="clear"/>
                    </p>
                    <div class="menu_body">
                        <table class="setting-table setting-userlists">
                            <tbody>
                            <tr>
                                <td class="setting-label" nowrap="nowrap"><label>Require acceptance of condition 1:   </label></td>
                                <td class="setting-target"><input name="terms-confirm" id="terms-confirm"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Condition 1 to be accepted:</label></td>
                                <td class="setting-target"><input name="acceptance-msg" id="acceptance-msg"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>

                            <tr>
                                <td class="setting-label" valign="top"><label>Text to be displayed:</label></td>
                                <td class="setting-target"><textarea name="terms-n-con" id="terms-n-con" cols="50"
                                                                     rows="10"></textarea></td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="clear:both;"></div>
						
						<table class="setting-table setting-userlists">
                            <tbody>
                            <tr>
                                <td class="setting-label" nowrap="nowrap"><label>Require acceptance of condition 2:</label></td>
                                <td class="setting-target"><input name="terms2-confirm" id="terms2-confirm"
                                                                  class="setting-checkbox" type="checkbox" value="yes"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="setting-label"><label>Condition 2 to be accepted:</label></td>
                                <td class="setting-target"><input name="acceptance2-msg" id="acceptance2-msg"
                                                                  class="setting-text" type="text"
                                                                  maxlength="<?php echo $text_field_maxlength; ?>"
                                                                  size="<?php echo $text_field_size; ?>"/></td>
                            </tr>

                            <tr>
                                <td class="setting-label" valign="top"><label>Text to be displayed:</label></td>
                                <td class="setting-target"><textarea name="terms2-n-con" id="terms2-n-con" cols="50"
                                                                     rows="11"></textarea></td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="clear:both;"></div>
                    </div>	
				
                    <div class="setting-save-container">
                        <input id="setting-save-btn" type="submit" value="Save" class="button-primary"/>
                        &nbsp;
                        <input id="setting-reset-btn" type="button" value="Reset" class="button-secondary"/>
                        &nbsp;
                        <input id="setting-test-btn" type="button" value="Test" class="button-secondary"/>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </form>
            <img id="loading-img" style="display:none;vertical-align:middle;padding:4px;background:none;"
                 src="<?php echo WP_PLUGIN_URL . '/wp-mailup/images/indicator.white.gif'; ?>"
                 border="0"/>
            <span id="save-message"><noscript
                    style="color:#F00;"><?php _e('** Please enable javascript to work with this form.'); ?></noscript></span>
        </div>
        <div class="wrap-right postbox" style="display:none">
            <h3>
                <?php _e('About MailUp.'); ?>
            </h3>
            <div class="inside">
                <?php
                echo '<a href="#" title="' . _('About MailUp.') . '" onclick=\'window.open("' . $mailup_path . 'help/mailup.html' . '", "_blank", "height=700,width=700,location=0,left=10,top=10,resizable=0,scrollbars=yes,toolbar=0", true);\'>' . _('About MailUp.') . '</a>';
                ?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <script type="text/javascript">
        var jQ = jQuery.noConflict();
        var feedback = jQuery("#feedback");
        jQ(document).ready(function () {

            feedback.find("input[name='browser']").val(getWebBrowser());
            feedback.find("input[name='os']").val(window.navigator.platform);

            /*
             collapse and expand box
             */
            jQ(".menu_head").click(function () {
                try {
                    jQ(this).siblings('.menu_head').children('.toggle_icon').attr("src", "<?php echo $image_path; ?>bullet_toggle_plus.png");
                    var state = jQ(this).children('.toggle_icon').attr("src");
                    state = state.split('/');
                    state = state.pop();
                    if (state == 'bullet_toggle_plus.png') {
                        jQ(this).children('.toggle_icon').attr("src", "<?php echo $image_path; ?>bullet_toggle_minus.png");
                    }
                    else {
                        jQ(this).children('.toggle_icon').attr("src", "<?php echo $image_path; ?>bullet_toggle_plus.png");
                    }
                    jQ(this).next("div.menu_body").slideToggle(300).siblings("div.menu_body").slideUp("slow");
                } catch (Exception) {
                    feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + Exception);
                    feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'jQ(".menu_head").click');
                    feedback.slideDown("slow");
                }

            });

            /*
             show status if input changes
             */
            jQ("input,textarea,select").change(function () {
                try {
                    jQ("#save-message").css("color", "#F00").html("<?php _e('** Settings have changed. Click <b>Save</b> to save the new settings.'); ?>");
                    jQ('#setting-test-btn').fadeOut();
                } catch (Exception) {
                    feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + Exception);
                    feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'jQ(".menu_head").click');
                    feedback.slideDown("slow");
                }
            });

            /*
             show help pages
             */
            jQ('.help_icon').click(function () {
                try {
                    //alert(jQ.trim(jQ(this).parent().text()));
                    var section = jQ.trim(jQ(this).parent().text());
                    switch (section) {
                        case 'General settings':
                            window.open("http://help.mailup.com/display/mailupUserGuide/WordPress#WordPress-Generalsettings", "_blank", "", true);
                            break;
                        case 'Recipient list':
                            window.open("http://help.mailup.com/display/mailupUserGuide/WordPress#WordPress-Recipientlist", "_blank", "", true);
                            break;
                        case 'Recipient fields':
                            window.open("http://help.mailup.com/display/mailupUserGuide/WordPress#WordPress-PersonalDataFields", "_blank", "", true);
                            break;
                        case 'Messages':
                            window.open("http://help.mailup.com/display/mailupUserGuide/WordPress#WordPress-ErrorMessages", "_blank", "", true);
                            break;
                        case 'Terms and conditions':
                            window.open("http://help.mailup.com/display/mailupUserGuide/WordPress#WordPress-Termsandconditions", "_blank", "", true);
                            break;                     
						default:
                    }
                } catch (Exception) {
                    feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + Exception);
                    feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'jQ(".menu_head").click');
                    feedback.slideDown("slow");
                }
            });

            /*
             Settings form submit
             */
            jQ("form#mailUpSettingForm").submit(function () {
                try {
                    viewInfoIcon('loading');

                    /*
                     validation starts
                     */

                    if (jQ.trim(jQ('#list-id').val()) == "") // List ID can not be empty
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>List ID</b> field is mandatory'); ?>");
                        jQ('#list-id').css("borderColor", "#F00");
                        return false;
                    }
                    if (!jQ('#email-required').is(':checked') && !jQ('#mobile-required').is(':checked')) // Either email or sms field is required for the xmlSubscribe.aspx API
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** Either <b>Email</b> or <b>Mobile number</b> must be used'); ?>");
                        return false;
                    }
                    if (jQ('#email-required').is(':checked') && !jQ('#email-show').is(':checked')) // If email field is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Email</b> must be displayed since it is required'); ?>");
                        return false;
                    }

                    if (jQ('#mobile-required').is(':checked') && !jQ('#mobile-show').is(':checked')) // If sms field is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Mobile number</b> must be displayed since it is required'); ?>");
                        return false;
                    }

                    if (jQ('#date-required').is(':checked') && !jQ('#date-show').is(':checked')) // If date field is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Custom Data</b> must be displayed since it is required'); ?>");
                        return false;
                    }

                    if (jQ('#extfield1-required').is(':checked') && !jQ('#extfield1-show').is(':checked')) // If additional field 1 is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Custom field 1</b> must be displayed since it is required'); ?>");
                        return false;
                    }

                    if (jQ('#extfield2-required').is(':checked') && !jQ('#extfield2-show').is(':checked')) // If additional field 2 is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Custom field 2</b> must be displayed since it is required'); ?>");
                        return false;
                    }

                    if (jQ('#extfield3-required').is(':checked') && !jQ('#extfield3-show').is(':checked')) // If additional field 3 is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Custom field 3</b> must be displayed since it is required'); ?>");
                        return false;
                    }

                    if (jQ('#extfield4-required').is(':checked') && !jQ('#extfield4-show').is(':checked')) // If additional field 4 is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Custom field 4</b> must be displayed since it is required'); ?>");
                        return false;
                    }

                    if (jQ('#extfield5-required').is(':checked') && !jQ('#extfield5-show').is(':checked')) // If additional field 5 is required, must be 'check' show also
                    {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>Custom field 5</b> must be displayed since it is required'); ?>");
                        return false;
                    }


                    /*
                     validation ends
                     */


                    /*
                     Process form input and save through Ajax
                     */
                    form_values = jQuery(this).serialize();
                    jQ.post('<?php echo $_SERVER['PHP_SELF']; ?>?formData=save', form_values, function (returned_data) {
                        if (returned_data !== "OK") {
                            feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + returned_data);
                            feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'Save form submitted');
                            feedback.slideDown("slow");
                        }
                        jQ("#save-message").css("color", "#00F").html("<?php _e('* Settings saved successfully.'); ?>");
                        jQ('#setting-test-btn').fadeIn();
                        fillFormData();
                    });
                    jQ('.setting-text').css("borderColor", "#DFDFDF");
                } catch (Exception) {
                    feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + Exception);
                    feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'jQ(".menu_head").click');
                    feedback.slideDown("slow");
                }

                return false;
            });

            function fillFormData() {
                jQ.post('<?php echo $_SERVER['PHP_SELF']; ?>', {"formData": "load"}, function (data) {
                    //console.log(data);
                    if (data == null) {
                        feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + "Data returned from db is null");
                        feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'fillFormData()');
                        feedback.slideDown("slow");
                    } else {
                        /*
                         Fill the form data
                         */
                        jQ('#console-host').val(data.consoleHost);
                        jQ('#subscribe-path').val(data.subscribePath);
                        jQ('#plugin-title').val(data.pluginTitle);
                        jQ('#plugin-description').val(data.pluginDescription);
                        /* jQ('#external-css').val(data.externalCss); */
                        jQ('#css-combination').val(data.cssCombination);
                        if (data.wpml == 'yes')
                            jQ('#wpml').attr("checked", true);
                        jQ('#submit-button').val(data.submitButton);
                        if (data.textInside == 'yes')
                            jQ('#text-inside').attr("checked", true);
                        if (data.requestConfirm == 'yes')
                            jQ('#request-confirm').attr("checked", true);
                        jQ('#list-id').val(data.listId);
                        jQ('#list-displayed-name').val(data.listDisplayedName);
                        jQ('#group-id').val(data.groupId);
                        if (data.emailShow == 'yes')
                            jQ('#email-show').attr("checked", true);
                        if (data.emailRequired == 'yes')
                            jQ('#email-required').attr("checked", true);
                        if (data.dateShow == 'yes')
                            jQ('#date-show').attr("checked", true);
                        if (data.dateRequired == 'yes')
                            jQ('#date-required').attr("checked", true);
                        jQ('#date-fieldcode').val(data.dateFieldcode);
                        jQ('#date-displayed-name').val(data.dateDisplayedName);
                        //jQ('#email-fieldcode').val(data.emailFieldcode);
                        jQ('#email-displayed-name').val(data.emailDisplayedName);
                        if (data.extfield1Show == 'yes')
                            jQ('#extfield1-show').attr("checked", true);
                        if (data.extfield1Required == 'yes')
                            jQ('#extfield1-required').attr("checked", true);
                        jQ('#extfield1-fieldcode').val(data.extfield1Fieldcode);
                        jQ('#extfield1-displayed-name').val(data.extfield1DisplayedName);
                        if (data.extfield2Show == 'yes')
                            jQ('#extfield2-show').attr("checked", true);
                        if (data.extfield2Required == 'yes')
                            jQ('#extfield2-required').attr("checked", true);
                        jQ('#extfield2-fieldcode').val(data.extfield2Fieldcode);
                        jQ('#extfield2-displayed-name').val(data.extfield2DisplayedName);
                        if (data.extfield3Show == 'yes')
                            jQ('#extfield3-show').attr("checked", true);
                        if (data.extfield3Required == 'yes')
                            jQ('#extfield3-required').attr("checked", true);
                        jQ('#extfield3-fieldcode').val(data.extfield3Fieldcode);
                        jQ('#extfield3-displayed-name').val(data.extfield3DisplayedName);
                        if (data.extfield4Show == 'yes')
                            jQ('#extfield4-show').attr("checked", true);
                        if (data.extfield4Required == 'yes')
                            jQ('#extfield4-required').attr("checked", true);
                        jQ('#extfield4-fieldcode').val(data.extfield4Fieldcode);
                        jQ('#extfield4-displayed-name').val(data.extfield4DisplayedName);
                        if (data.extfield5Show == 'yes')
                            jQ('#extfield5-show').attr("checked", true);
                        if (data.extfield5Required == 'yes')
                            jQ('#extfield5-required').attr("checked", true);
                        jQ('#extfield5-fieldcode').val(data.extfield5Fieldcode);
                        jQ('#extfield5-displayed-name').val(data.extfield5DisplayedName);
                        if (data.mobileShow == 'yes')
                            jQ('#mobile-show').attr("checked", true);
                        if (data.mobileRequired == 'yes')
                            jQ('#mobile-required').attr("checked", true);
                        //jQ('#mobile-fieldcode').val(data.mobileFieldcode);
                        jQ('#mobile-displayed-name').val(data.mobileDisplayedName);
                        jQ('#success-message').val(data.successMessage);
                        jQ('#generic-error').val(data.genericError);
                        jQ('#invalid-address').val(data.invalidAddress);
                        jQ('#invalid-phone').val(data.invalidPhone);
                        jQ('#already-present').val(data.alreadyPresent);
                        jQ('#field-required').val(data.fieldRequired);
						
                        jQ('#terms-not-agreed').val(data.termsNotAgreed);
                        if (data.termsConfirm == 'yes')
                            jQ('#terms-confirm').attr("checked", true);
                        jQ('#terms-n-con').val(data.termsNcon);
                        jQ('#acceptance-msg').val(data.acceptanceMsg);
						
						jQ('#terms2-not-agreed').val(data.terms2NotAgreed);
                        if (data.terms2Confirm == 'yes')
                            jQ('#terms2-confirm').attr("checked", true);
                        jQ('#terms2-n-con').val(data.terms2Ncon);
                        jQ('#acceptance2-msg').val(data.acceptance2Msg);
						
                        viewInfoIcon('stop');
                    }
                }, "json");
            }

            /*
             Fill the form with saved data during page load
             */
            fillFormData();

            /*
             Reset the previous options
             */
            jQ('#setting-reset-btn').click(function () {
                try {
                    if (confirm('<?php _e('Do you want to reset the settings?'); ?>')) {
                        jQ('#console-host').val('');
                        jQ('#subscribe-path').val('/frontend/xmlSubscribe.aspx');
                        jQ('#plugin-title').val('Newsletter subscription');
                        jQ('#plugin-description').val('Our monthly newsletter with a selection of the best posts');
                        jQ('#css-combination').val('style1');
                        jQ('#submit-button').val('SIGN UP');
                        jQ('#text-inside').attr("checked", false);
                        jQ('#request-confirm').attr("checked", false);
                        jQ('#wpml').attr("checked", false);

                        jQ('#list-id').val('1');
                        jQ('#list-displayed-name').val('Newsletter subscribers');
                        jQ('#group-id').val('');

                        jQ('#email-show').attr("checked", true);
                        jQ('#email-required').attr("checked", true);
                        jQ('#email-displayed-name').val('Email');

                        jQ('#mobile-show').attr("checked", true);
                        jQ('#mobile-required').attr("checked", true);
                        jQ('#mobile-displayed-name').val('Mobile number');


                        jQ('#date-show').attr("checked", false);
                        jQ('#date-required').attr("checked", false);
                        jQ('#date-fieldcode').val('Campo6');
                        jQ('#date-displayed-name').val('Date');

                        jQ('#extfield1-show').attr("checked", false);
                        jQ('#extfield1-required').attr("checked", false);
                        jQ('#extfield1-fieldcode').val('Campo1');
                        jQ('#extfield1-displayed-name').val('Name');

                        jQ('#extfield2-show').attr("checked", false);
                        jQ('#extfield2-required').attr("checked", false);
                        jQ('#extfield2-fieldcode').val('Campo2');
                        jQ('#extfield2-displayed-name').val('Last Name');

                        jQ('#extfield3-show').attr("checked", false);
                        jQ('#extfield3-required').attr("checked", false);
                        jQ('#extfield3-fieldcode').val('Campo3');
                        jQ('#extfield3-displayed-name').val('');

                        jQ('#extfield4-show').attr("checked", false);
                        jQ('#extfield4-required').attr("checked", false);
                        jQ('#extfield4-fieldcode').val('Campo4');
                        jQ('#extfield4-displayed-name').val('');

                        jQ('#extfield5-show').attr("checked", false);
                        jQ('#extfield5-required').attr("checked", false);
                        jQ('#extfield5-fieldcode').val('Campo5');
                        jQ('#extfield5-displayed-name').val('');

                        jQ('#success-message').val('Operation completed');
                        jQ('#generic-error').val('Generic error');
                        jQ('#invalid-address').val('Invalid email address');
                        jQ('#invalid-phone').val('Invalid mobile phone number');
                        jQ('#already-present').val('Already a subscriber!');
                        jQ('#field-required').val('is required');
						
                        jQ('#terms-not-agreed').val('Please agree to the terms');
                        jQ('#terms-confirm').attr("checked", false);
                        jQ('#terms-n-con').val('');
                        jQ('#acceptance-msg').val('I accept terms and conditions.');

                        jQ('#terms2-not-agreed').val('Please agree to the additional terms');
                        jQ('#terms2-confirm').attr("checked", false);
                        jQ('#terms2-n-con').val('');
                        jQ('#acceptance2-msg').val('I accept the additional terms and conditions.');
						
                        jQ("#save-message").css("color", "#00F").html("<?php _e('* Settings reset.'); ?>");
                    }
                }
                catch (Exception) {
                    feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + Exception);
                    feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'jQ(".menu_head").click');
                    feedback.slideDown("slow");
                }
            });

            /*
             Test current options
             */
            jQ('#setting-test-btn').click(function () {
                try {
                    var console_host = jQ('#console-host').val();
                    var subscribe_path = jQ('#subscribe-path').val();
                    if ((!(console_host.match(/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/)) && !(console_host.match(/^(http|https):\/\/([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/))) || (console_host == '')) {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>MailUp console host: </b> field is empty or wrong'); ?>");
                        jQ('#console-host').css("borderColor", "#F00");
                    }
                    else if (subscribe_path == '') {
                        jQ("#save-message").css("color", "#F00").html("<?php _e('** <b>MailUp subscribe path: </b> field is empty'); ?>");
                        jQ('#subscribe-path').css("borderColor", "#F00");
                    }
                    else {
                        var listId = '<?php echo $wpmailup['listId']; ?>';
                        var groupId = '<?php echo $wpmailup['groupId']; ?>';
                        var subUrl = '<?php echo $wpmailup['consoleHost'] . $wpmailup['subscribePath'] . '/?source=generic'; ?>';
                        var form_values = {
                            "Email": "esempio@mailup.it",
                            "List": listId,
                            "sms": "",
                            "Group": groupId,
                            "Confirm": "false",
                            "csvFldNames": "",
                            "csvFldValues": "",
                            "retCode": "1",
                            "token": "subscribe",
                            "subsUrl": subUrl,
                            "termsAccept": "yes",
                            "terms2Accept": "yes"
                        }

                        jQ.post('<?php echo WP_PLUGIN_URL . '/wp-mailup/subscribe.php'; ?>', form_values, function (returned_data) {
                            if (Number(returned_data) == -1011) {
                                jQ("#save-message").css("color", "#F00").html('<?php echo 'IP address validation is required. Please check this <a href="http://help.mailup.com/display/mailupUserGuide/WordPress#WordPress-authorizing" target="_blank">page</a>'; ?>');
                            }
                            else {
                                jQ('#console-host').css("borderColor", "");
                                jQ("#save-message").css("color", "#00F").html("<?php _e('** <b>Successfully tested</b> '); ?>");
                            }
                        });
                    }
                }
                catch (Exception) {
                    feedback.find("input[name='error']").val(feedback.find("input[name='error']").val() + "|" + Exception);
                    feedback.find("input[name='event']").val(feedback.find("input[name='event']").val() + "|" + 'jQ(".menu_head").click');
                    feedback.slideDown("slow");
                }
            });

            function viewInfoIcon(status) {
                switch (String(status)) {
                    case 'loading':
                        jQ('#loading-img').attr('src', '<?php echo WP_PLUGIN_URL . '/wp-mailup/images/indicator.white.gif'; ?>');
                        jQ('#loading-img').css('display', '');
                        break;
                    case 'info':
                        jQ('#loading-img').attr('src', '<?php echo WP_PLUGIN_URL . '/wp-mailup/images/question.gif'; ?>');
                        jQ('#loading-img').css('display', '');
                        break;
                    case 'stop':
                        jQ('#loading-img').css('display', 'none');
                    default:
                }
            }

            function getWebBrowser() {
                var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
                // Firefox 1.0+
                var isFirefox = typeof InstallTrigger !== 'undefined';
                // Safari 3.0+ "[object HTMLElementConstructor]"
                var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || (function (p) {
                        return p.toString() === "[object SafariRemoteNotification]";
                    })(!window['safari'] || safari.pushNotification);
                // Internet Explorer 6-11
                var isIE = /*@cc_on!@*/false || !!document.documentMode;
                // Edge 20+
                var isEdge = !isIE && !!window.StyleMedia;
                // Chrome 1+
                var isChrome = !!window.chrome && !!window.chrome.webstore;
                // Blink engine detection
                var isBlink = (isChrome || isOpera) && !!window.CSS;

                if (isOpera) {
                    return "Opera";
                }
                if (isFirefox) {
                    return "Firefox";
                }
                if (isSafari) {
                    return "Safari";
                }
                if (isIE) {
                    return "Internet Explorer";
                }
                if (isEdge) {
                    return "Edge";
                }
                if (isChrome) {
                    return "Chrome";
                }
                if (isBlink) {
                    return "Blink";
                }
            }

        });
    </script>
    <?php
}
?>
