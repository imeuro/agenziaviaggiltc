<?php

class HappyForms_Dashboard_Modals {

	private static $instance;

	private $modals = array();

	public $dismiss_action = 'happyforms-modal-dismiss';

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		add_action( "wp_ajax_{$this->dismiss_action}", [ $this, 'dismiss_modal' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_filter( 'happyforms_customize_enqueue_scripts', [ $this, 'customize_enqueue_scripts' ] );
		add_action( 'in_admin_footer', [ $this, 'output_modal_area' ] );
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'output_modal_area' ] );
	}

	public function get_modal_defaults() {
		$defaults = array(
			'classes' => '',
			'dismissible' => false,
		);

		return $defaults;
	}

	public function register_modal( $id, $settings = array() ) {
		if ( $this->is_dismissed( $id ) ) {
			return;
		}

		$settings = wp_parse_args( $settings, $this->get_modal_defaults() );

		$this->modals[$id] = $settings;
	}

	public function dismiss_modal() {
		if ( ! isset( $_POST['id'] ) ) {
			die( '' );
		}

		$id = $_POST['id'];

		if ( ! isset( $this->modals[$id] ) ) {
			die( '' );
		}

		if ( ! $this->is_dismissible( $id ) ) {
			die( '' );
		}

		update_option( "happyforms_modal_dismissed_{$id}", true );

		do_action( 'happyforms_modal_dismissed', $id );

		die( '' );
	}

	public function is_dismissible( $id ) {
		$settings = $this->modals[$id];
		$dismissible = isset( $settings['dismissible'] ) ? $settings['dismissible'] : false;

		return $dismissible;
	}

	public function is_dismissed( $id ) {
		return get_option( "happyforms_modal_dismissed_{$id}", false );
	}

	public function admin_enqueue_scripts() {
		$asset_file = require( happyforms_get_include_folder() . '/assets/jsx/build/admin/dashboard-modals.asset.php' );
		$dependencies = array_merge( $asset_file['dependencies'], array( 'happyforms-admin' ) );

		wp_enqueue_script(
			'happyforms-dashboard-modals',
			happyforms_get_plugin_url() . 'inc/assets/jsx/build/admin/dashboard-modals.js',
			$dependencies, $asset_file['version'], true
		);

		wp_register_style(
			'happyforms-dashboard-modals-core',
			happyforms_get_plugin_url() . 'core/assets/css/dashboard-modals.css',
			array( 'wp-components' ), happyforms_get_version()
		);

		wp_enqueue_style(
			'happyforms-dashboard-modals',
			happyforms_get_plugin_url() . 'inc/assets/css/dashboard-modals.css',
			array( 'happyforms-dashboard-modals-core' ), happyforms_get_version()
		);

		wp_localize_script( 'happyforms-dashboard-modals', '_happyFormsDashboardModalsSettings', $this->get_script_settings() );
	}

	public function customize_enqueue_scripts() {
		$asset_file = require( happyforms_get_include_folder() . '/assets/jsx/build/admin/dashboard-modals.asset.php' );

		wp_enqueue_script(
			'happyforms-dashboard-modals',
			happyforms_get_plugin_url() . 'inc/assets/jsx/build/admin/dashboard-modals.js',
			$asset_file['dependencies'], $asset_file['version'], true
		);

		wp_register_style(
			'happyforms-dashboard-modals-core',
			happyforms_get_plugin_url() . 'core/assets/css/dashboard-modals.css',
			array( 'wp-components' ), happyforms_get_version()
		);

		wp_enqueue_style(
			'happyforms-dashboard-modals',
			happyforms_get_plugin_url() . 'inc/assets/css/dashboard-modals.css',
			array( 'happyforms-dashboard-modals-core' ), happyforms_get_version()
		);

		wp_localize_script( 'happyforms-dashboard-modals', '_happyFormsDashboardModalsSettings', $this->get_script_settings() );

		$deps[] = 'happyforms-dashboard-modals';

		return $deps;
	}

	public function get_script_settings() {
		$settings = array(
			'actionModalDismiss' => $this->dismiss_action,
			'pluginURL' => happyforms_get_plugin_url(),
		);

		$settings = apply_filters( 'happyforms_dashboard_modal_settings', $settings );

		return $settings;
	}

	public function output_modal_area() {
	?>
	<div id="happyforms-modals-area"></div>
	<?php
	}

}

if ( ! function_exists( 'happyforms_get_dashboard_modals' ) ):

function happyforms_get_dashboard_modals() {
	return HappyForms_Dashboard_Modals::instance();
}

endif;

happyforms_get_dashboard_modals();