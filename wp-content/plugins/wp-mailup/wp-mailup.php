<?php
/**
 * @package WP-MailUp
 */
/*
	Plugin name: WP-MailUp
	Plugin URI: http://wordpress.org/extend/plugins/wp-mailup/
	Description: Wordpress plugin for MailUp email marketing system.
	Version: 1.4.12
	Author: MailUp and N. I. Biz Soft Solutions
	Author URI: http://www.mailup.com/
	License: GPLv2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $wpdb, $wpmailup, $mailup_path;

/*
	Admin menu
*/

require_once(dirname(__FILE__).'/ajax.functions.php');

function wpmailup_admin_menu()
{
	$page_title = 'MailUp Pluging Settings';
	$menu_title = 'WP MailUp';
	$capability = 'manage_options';
	$menu_slug = 'wp-mailup-settings';
	$function = 'wp_mailup_admin';
	$position = '';
	
	/* Add Option page submenu */
	$parent_slug = 'options-general.php';
	$page = add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
	add_action( 'admin_print_styles-' . $page, 'wpmailup_enqueue_style');
}
add_action('admin_menu', 'wpmailup_admin_menu');

function wpmailup_enqueue_style()
{
	wp_enqueue_style( "mailUpStylesheet" );
}

function wp_mailup_admin()
{
	global $wpmailup, $mailup_path;
	/*
		some admin page settings
	*/
	
	$text_field_size = 35;
	$text_field_maxlength = 80;
	include(dirname(__FILE__).'/display-setting.php');
}

function wpmailup_init()
{
	global $wpmailup, $mailup_path;
	define('WPMAILUP', 'wpmailup');
	$wpmailup = array();
	$mailup_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
	wp_register_style( 'mailUpStylesheet', $mailup_path . 'mailup.css' );
	
	/*
		Load jquery from Google's CDN
	*/
	if (!is_admin()) {
		/*wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'http://code.jquery.com/jquery-1.6.1.js');*/
		wp_enqueue_script( 'jquery' );
	}
	
	/*
		Load language file
	*/
	if(!load_plugin_textdomain('/wp-content/languages/')) {
		load_plugin_textdomain(WP_PLUGIN_URL . '/wp-mailup/languages/');
	}
}
add_action('init', 'wpmailup_init');


/* add_action('wp_head', create_function('', 'wp_enqueue_style( "mailUpStylesheet" );')); */

/*
	MailUp widget
*/
class WPMailUp_Widget extends WP_Widget {
	public function __construct() {
		// widget actual processes
		//parent::WP_Widget(false, $name = 'WP MailUp');
		parent::__construct(false, $name = 'WP MailUp');
	}
	
	function form($instance) {
		// outputs the options form on admin
		$title = esc_attr($instance['title']);
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
    <?php
	}
	
	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	function widget($args, $instance) {
		// outputs the content of the widget
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		include(dirname(__FILE__).'/display-widget.php');
        echo $after_widget;
	}
}
add_action('widgets_init', 'wpmailup_register_widget');

function wpmailup_register_widget() {
	return register_widget("WPMailUp_Widget");
}

function wpmailup_active()
{
	$wpmailup['consoleHost'] = '';
	$wpmailup['subscribePath'] = '/frontend/xmlSubscribe.aspx';
	$wpmailup['pluginTitle'] = _('Newsletter subscription');
	$wpmailup['pluginDescription'] = _('Our monthly newsletter with a selection of the best posts');
	//$wpmailup['externalCss'] = $_REQUEST['external-css'];
	$wpmailup['cssCombination'] = 'style1';
	$wpmailup['submitButton'] = 'SIGN UP';
	$wpmailup['requestConfirm'] = 'yes';
	$wpmailup['textInside'] = '';
	$wpmailup['requestConfirm'] = '';
	$wpmailup['listId'] = 1;
	$wpmailup['listDisplayedName'] = 'Newsletter subscribers';
	$wpmailup['groupId'] = '';
	$wpmailup['emailShow'] = 'yes';
	$wpmailup['emailRequired'] = 'yes';
	$wpmailup['emailFieldcode'] = 'Email';
	$wpmailup['emailDisplayedName'] = _('Email');
	$wpmailup['dateShow'] = '';
	$wpmailup['dateRequired'] = '';
	$wpmailup['dateFieldcode'] = 'Campo6';
	$wpmailup['dateDisplayedName'] = '';
	$wpmailup['extfield1Show'] = 'yes';
	$wpmailup['extfield1Required'] = 'yes';
	$wpmailup['extfield1Fieldcode'] = 'Campo1';
	$wpmailup['extfield1DisplayedName'] = 'Name';
	$wpmailup['extfield2Show'] = 'yes';
	$wpmailup['extfield2Required'] = 'yes';
	$wpmailup['extfield2Fieldcode'] = 'Campo2';
	$wpmailup['extfield2DisplayedName'] = 'Last name';
	$wpmailup['extfield3Show'] = '';
	$wpmailup['extfield3Required'] = '';
	$wpmailup['extfield3Fieldcode'] = 'Campo3';
	$wpmailup['extfield3DisplayedName'] = '';
	$wpmailup['extfield4Show'] = '';
	$wpmailup['extfield4Required'] = '';
	$wpmailup['extfield4Fieldcode'] = 'Campo4';
	$wpmailup['extfield4DisplayedName'] = '';
	$wpmailup['extfield5Show'] = '';
	$wpmailup['extfield5Required'] = '';
	$wpmailup['extfield5Fieldcode'] = 'Campo5';
	$wpmailup['extfield5DisplayedName'] = '';
	$wpmailup['mobileShow'] = 'yes';
	$wpmailup['mobileRequired'] = 'yes';
	$wpmailup['mobileFieldcode'] = 'sms';
	$wpmailup['mobileDisplayedName'] = _('Mobile number');
	$wpmailup['successMessage'] = _('Operation completed');
	$wpmailup['genericError'] = _('Generic error');
	$wpmailup['invalidAddress'] = _('Invalid email address');
	$wpmailup['invalidPhone'] = _('Invalid mobile phone number');
	$wpmailup['alreadyPresent'] = _('Already a subscriber!');
	$wpmailup['fieldRequired'] = _('is required');
	
	$wpmailup['termsNotAgreed'] = _('Please agree to the terms');
	$wpmailup['termsConfirm'] = '';
	$wpmailup['termsNcon'] = '';
	$wpmailup['acceptanceMsg'] = _('I accept terms and conditions.');
	
	$wpmailup['terms2NotAgreed'] = _('Please agree to the additional terms');
	$wpmailup['terms2Confirm'] = '';
	$wpmailup['terms2Ncon'] = '';
	$wpmailup['acceptance2Msg'] = _('I accept the additional terms and conditions.');
	
	add_option('wpmailup', serialize($wpmailup));
}
register_activation_hook(__FILE__, 'wpmailup_active');

function wpmailup_deactive()
{
	delete_option('wpmailup');
}
register_deactivation_hook(__FILE__, 'wpmailup_deactive');

?>