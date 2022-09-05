<?php
class HappyForms_Integrations_Page_Controller {
	private static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		add_filter( 'happyforms_integrations_page_method', array( $this, 'set_admin_page_method' ) );
		add_filter( 'happyforms_integrations_page_url', array( $this, 'set_admin_page_url' ) );
		add_filter( 'happyforms_coupons_page_method', array( $this, 'set_coupon_page_method' ) );
		add_filter( 'happyforms_coupons_page_url', array( $this, 'set_coupon_page_url' ) );
		add_action( 'happyforms_add_meta_boxes', array( $this, 'set_metaboxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'coupons_add_screen_options' ), 20, 2 );
	}

	public function set_metaboxes() {
		$screen = get_plugin_page_hookname( plugin_basename( $this->set_admin_page_url() ), 'happyforms' );

		$widgets = [
			[ 'id' => 'active-campaign', 'label' => __( 'ActiveCampaign', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'aweber', 'label' => __( 'AWeber', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'constant-contact', 'label' => __( 'Constant Contact', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'convertkit', 'label' => __( 'ConvertKit', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'mailchimp', 'label' => __( 'Mailchimp', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'mailerlite', 'label' => __( 'MailerLite', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'sendfox', 'label' => __( 'SendFox', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'sendgrid', 'label' => __( 'SendGrid', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'sendinblue', 'label' => __( 'Sendinblue', 'happyforms' ), 'context' => 'normal' ],
			[ 'id' => 'recaptcha', 'label' => __( 'reCAPTCHA', 'happyforms' ), 'context' => 'side' ],
			[ 'id' => 'stripe', 'label' => __( 'Stripe', 'happyforms' ), 'context' => 'side' ],
			[ 'id' => 'paypal', 'label' => __( 'PayPal', 'happyforms' ), 'context' => 'side' ],
			[ 'id' => 'zapier', 'label' => __( 'Zapier', 'happyforms' ), 'context' => 'column3' ],
			[ 'id' => 'integromat', 'label' => __( 'Integromat', 'happyforms' ), 'context' => 'column3' ],
			[ 'id' => 'integrately', 'label' => __( 'Integrately', 'happyforms' ), 'context' => 'column3' ],
			[ 'id' => 'google-analytics', 'label' => __( 'Google Analytics', 'happyforms' ), 'context' => 'column4' ],
		];

		foreach( $widgets as $widget ) {
			add_meta_box(
				'happyforms-integrations-widget-' . $widget['id'],
				$widget['label'], function() use ( $widget ) {
					require( happyforms_get_integrations_folder() . '/templates/widget-' . $widget['id'] . '-dummy.php' );
				},
				$screen, $widget['context']
			);
		}
	}

	public function set_admin_page_method() {
		return array( $this, 'integrations_page' );
	}

	public function set_admin_page_url() {
		return 'happyforms-integrations';
	}

	public function integrations_page() {
		wp_enqueue_script( 'dashboard' );
		add_filter( 'admin_footer_text', 'happyforms_admin_footer' );

		require_once( happyforms_get_integrations_folder() . '/templates/admin-integrations.php' );
	}

	public function set_coupon_page_method() {
		return array( $this, 'coupons_page' );
	}

	public function set_coupon_page_url() {
		return 'happyforms-coupons';
	}

	public function coupons_page() {
		wp_enqueue_script( 'dashboard' );
		add_filter( 'admin_footer_text', 'happyforms_admin_footer' );

		require_once( happyforms_get_integrations_folder() . '/templates/admin-coupons.php' );
	}

	public function admin_enqueue_scripts() {
		if ( ! isset( $_GET['page'] ) || 'happyforms-integrations' !== $_GET['page'] ) {
			return;
		}

		wp_enqueue_style(
			'happyforms-integrations',
			happyforms_get_plugin_url() . 'integrations/assets/css/admin.css'
		);
	}

	public function coupons_add_screen_options( $show, $screen ) {
		return 'forms_page_happyforms-coupons' == $screen->id ? true : $show;
	}



}

if ( ! function_exists( 'happyforms_get_integrations_page_controller' ) ):

function happyforms_get_integrations_page_controller() {
	return HappyForms_Integrations_Page_Controller::instance();
}

endif;

happyforms_get_integrations_page_controller();
