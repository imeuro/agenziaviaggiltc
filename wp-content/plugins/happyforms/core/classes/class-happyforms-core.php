<?php

class HappyForms_Core {

	/**
	 * The parameter key used to connotate
	 * HappyForms Customize screen sessions.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	private $customize_mode = 'happyforms';

	/**
	 * The form shortcode name.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	private $shortcode = 'form';

	private $branded_shortcode = 'happyforms';

	/**
	 * URL of plugin landing page.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	private $landing_page_url = 'https://www.happyforms.io';

	/**
	 * Whether or not frontend styles were loaded.
	 */
	private $frontend_styles = false;

	/**
	 * Whether or not frontend color styles were loaded.
	 */
	private $frontend_color_styles = false;

	/**
	 * Action: initialize admin and frontend logic.
	 *
	 * @since 1.0
	 *
	 * @hooked action plugins_loaded
	 *
	 * @return void
	 */
	public function initialize_plugin() {
		require_once( happyforms_get_core_folder() . '/classes/class-cache.php' );
		require_once( happyforms_get_core_folder() . '/helpers/helper-misc.php' );
		require_once( happyforms_get_core_folder() . '/helpers/helper-antispam.php' );
		require_once( happyforms_get_core_folder() . '/helpers/helper-styles.php' );

		if ( is_admin() ) {
			require_once( happyforms_get_core_folder() . '/classes/class-admin-notices.php' );
			require_once( happyforms_get_core_folder() . '/classes/class-dashboard-modals.php' );
		}

		require_once( happyforms_get_core_folder() . '/classes/class-validation-messages.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-tracking.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-assets.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-controller.php' );
		require_once( happyforms_get_include_folder() . '/classes/class-message-controller.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-email-message.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-part-library.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-styles.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-setup.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-email.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-messages.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-session.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-happyforms-widget.php' );
		require_once( happyforms_get_core_folder() . '/helpers/helper-form-templates.php' );

		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Option limiting
		require_once( happyforms_get_core_folder() . '/classes/class-form-option-limiter.php' );

		// Shuffle
		require_once( happyforms_get_core_folder() . '/classes/class-form-shuffle.php' );

		// Gutenberg block
		if ( happyforms_is_gutenberg() ) {
			require_once( happyforms_get_core_folder() . '/classes/class-block.php' );
		}

		// Punycode support
		require_once( happyforms_get_core_folder() . '/classes/class-email-encoder.php' );

		// Admin hooks
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'submenu_file', array( $this, 'submenu_file' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'current_screen', array( $this, 'admin_screens' ) );
		add_action( 'media_buttons', array( $this, 'insert_editor_buttons' ) );
		add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );

		// Widget
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// Common hooks
		add_shortcode( $this->branded_shortcode, array( $this, 'handle_shortcode' ) );
		add_shortcode( $this->shortcode, array( $this, 'handle_shortcode' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );

		add_action( 'admin_print_footer_scripts', array( $this, 'print_shortcode_template' ) );

		// Exclude 3rd party assets
		add_action( 'wp_print_scripts', array( $this, 'exclude_scripts' ), PHP_INT_MAX );
		add_action( 'wp_print_footer_scripts', array( $this, 'exclude_scripts' ), PHP_INT_MAX );

		// Preview scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_preview' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_scripts_preview' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );

		// Hide legacy widget in new block-based Widgets screen
		add_filter( 'widget_types_to_hide_from_legacy_widget_block', array( $this, 'hide_legacy_form_widget' ) );

		// Deactivation
		require_once( happyforms_get_core_folder() . '/classes/class-deactivation.php' );
	}

	public function customize_preview_init() {
		require_once( happyforms_get_core_folder() . '/classes/class-admin-notices.php' );

		add_action( 'happyforms_form_before', array( happyforms_get_admin_notices(), 'display_preview_notices' ), 20 );
	}

	public function hide_legacy_form_widget( $widget_types ) {
		$widget_types[] = 'happyforms_widget';

		return $widget_types;
	}

	/**
	 * Action: initialize Customize screen logic.
	 *
	 * @since 1.0
	 *
	 * @hooked action customize_loaded_components
	 *
	 * @param array $components Array of standard Customize components.
	 *
	 * @return array
	 */
	public function initialize_customize_screen( $components ) {
		/*
		 * See "Resetting the Customizer to a Blank Slate"
		 * https://make.xwp.co/2016/09/11/resetting-the-customizer-to-a-blank-slate/
		 * https://github.com/xwp/wp-customizer-blank-slate
		 */

		// Initialize our customize screen if we're in HappyForms mode.
		if ( ! $this->is_customize_mode() ) {
			return $components;
		}

		require_once( happyforms_get_core_folder() . '/classes/class-wp-customize-form-manager.php' );

		$this->customize = new HappyForms_WP_Customize_Form_Manager();

		// Short-circuit widgets, nav-menus, etc from loading.
		return array();
	}

	/**
	 * Action: register admin menus.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_menu
	 *
	 * @return void
	 */
	public function admin_menu() {
		$form_controller = happyforms_get_form_controller();

		add_menu_page(
			__( 'Forms', 'happyforms' ),
			__( 'Forms', 'happyforms' ),
			apply_filters( 'happyforms_main_page_capabilities', 'manage_options' ),
			'happyforms', '', 'dashicons-feedback', 50
		);

		add_submenu_page(
			'happyforms',
			__( 'All Forms', 'happyforms' ),
			__( 'All Forms', 'happyforms' ),
			apply_filters( 'happyforms_forms_page_capabilities', 'manage_options' ),
			'/edit.php?post_type=happyform'
		);

		add_submenu_page(
			'happyforms',
			__( 'Add New', 'happyforms' ),
			__( 'Add New', 'happyforms' ),
			apply_filters( 'happyforms_forms_page_capabilities', 'manage_options' ),
			happyforms_get_form_edit_link( 0 )
		);

		add_submenu_page(
			'happyforms',
			__( 'Submissions', 'happyforms' ),
			__( 'Submissions', 'happyforms' ),
			apply_filters( 'happyforms_responses_page_capabilities', 'manage_options' ),
			apply_filters( 'happyforms_responses_page_url', '#responses' ),
			apply_filters( 'happyforms_responses_page_method', '' )
		);

		add_submenu_page(
			'happyforms',
			__( 'Coupons', 'happyforms' ),
			__( 'Coupons', 'happyforms' ),
			apply_filters( 'happyforms_coupons_page_capabilities', 'manage_options' ),
			apply_filters( 'happyforms_coupons_page_url', '#coupons' ),
			apply_filters( 'happyforms_coupons_page_method', '' )
		);

		add_submenu_page(
			'happyforms',
			__( 'Integrations', 'happyforms' ),
			__( 'Integrations', 'happyforms' ),
			apply_filters( 'happyforms_integrations_page_capabilities', 'manage_options' ),
			apply_filters( 'happyforms_integrations_page_url', '#integrations' ),
			apply_filters( 'happyforms_integrations_page_method', '' )
		);

		add_submenu_page(
			'happyforms',
			__( 'Settings', 'happyforms' ),
			__( 'Settings', 'happyforms' ) . apply_filters( 'happyforms_settings_page_menu_badge', '' ),
			apply_filters( 'happyforms_settings_page_capabilities', 'manage_options' ),
			apply_filters( 'happyforms_settings_page_url', '#settings' ),
			apply_filters( 'happyforms_settings_page_method', '' )
		);

		do_action( 'happyforms_add_meta_boxes' );
	}

	public function submenu_file( $submenu_file ) {
		global $submenu;

		remove_submenu_page( 'happyforms', 'happyforms' );
		remove_submenu_page( 'happyforms', 'happyforms-welcome' );

		if ( empty( $submenu['happyforms'] ) ) {
			remove_menu_page( 'happyforms' );
		}

		return $submenu_file;
	}

	/**
	 * Action: enqueue scripts and styles for the admin.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style(
			'happyforms-admin',
			happyforms_get_plugin_url() . 'core/assets/css/admin.css',
			array(), happyforms_get_version()
		);

		wp_enqueue_style(
			'happyforms-notices',
			happyforms_get_plugin_url() . 'core/assets/css/notice.css',
			array(), happyforms_get_version()
		);

		wp_register_script(
			'happyforms-admin',
			happyforms_get_plugin_url() . 'core/assets/js/admin/dashboard.js',
			array( 'jquery-color' ), happyforms_get_version(), true
		);

		global $pagenow;

		$data = array(
			'editLink' => admin_url( happyforms_get_form_edit_link( 'ID', 'URL' ) ),
			'shortcode' => happyforms_get_shortcode(),
		);

		if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) {
			$forms = happyforms_get_form_controller()->get();
			$fields = array( 'post_title' );
			$fields = apply_filters( 'happyforms_dashboard_form_fields', $fields );
			$fields = array_flip( $fields );
			$form_data = array();

			foreach( $forms as $form ) {
				$form_id = $form['ID'];
				$form_data[$form_id] = array_intersect_key( $form, $fields );
			}

			$data['forms'] = $form_data;
		}

		$data = apply_filters( 'happyforms_dashboard_data', $data );

		wp_localize_script( 'happyforms-admin', '_happyFormsAdmin', $data );
		wp_enqueue_script( 'happyforms-admin' );
	}

	/**
	 * Action: include custom admin screens
	 * for the Form and Message post types.
	 *
	 * @since 1.0
	 *
	 * @hooked action current_screen
	 *
	 * @return void
	 */
	public function admin_screens() {
		global $pagenow;

		$form_post_type = happyforms_get_form_controller()->post_type;
		$current_post_type = get_current_screen()->post_type;

		if ( in_array( $pagenow, array( 'edit.php', 'post.php' ) )
			&& ( $current_post_type === $form_post_type ) ) {

			require_once( happyforms_get_core_folder() . '/classes/class-form-admin.php' );
		}
	}

	/**
	 * Get basic info about the form being currently edited.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	private function get_form_data_array() {
		$forms = happyforms_get_form_controller()->get();
		$forms = array_values( wp_list_filter( $forms, array( 'post_status' => 'publish' ) ) );
		$form_data = array();

		foreach ( $forms as $form ) {
			array_push( $form_data, array( 'id' => $form['ID'], 'title' => happyforms_get_form_title( $form ) ) );
		}

		return $form_data;
	}

	/**
	 * Return whether or not we're running
	 * the Customize screen in HappyForms mode.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function is_customize_mode() {
		return (
			isset( $_REQUEST['happyforms'] )
			&& ! empty( $_REQUEST['happyforms'] )
			&& isset( $_REQUEST['form_id'] )
		);
	}

	/**
	 * Filter: register the form dropdown button
	 * for he post content editor toolbar.
	 *
	 * @since 1.0
	 *
	 * @hooked filter mce_buttons
	 *
	 * @param array $buttons The currently registered buttons.
	 *
	 * @return array
	 */
	public function tinymce_register_button( $buttons ) {
		$buttons[] = 'happyforms_form_picker';

		return $buttons;
	}

	/**
	 * Render the HappyForms shortcode.
	 *
	 * @since 1.0
	 *
	 * @param array $attrs The shortcode attributes.
	 *
	 * @return string
	 */
	public function handle_shortcode( $attrs ) {
		if ( ! isset( $attrs['id'] ) ) {
			return;
		}

		$form_id = intval( $attrs['id'] );
		$form_controller = happyforms_get_form_controller();
		$form = $form_controller->get( $form_id );

		if ( empty( $form ) ) {
			return '';
		}

		if ( happyforms_get_form_property( $form, 'modal' ) ) {
			return '';
		}

		$asset_mode = HappyForms_Form_Assets::MODE_COMPLETE;

		// Classic editor
		if ( is_admin() ) {
			$asset_mode = HappyForms_Form_Assets::MODE_ADMIN;
		}

		// Customize screen
		if ( happyforms_is_preview() ) {
			$asset_mode = HappyForms_Form_Assets::MODE_ADMIN;
		}

		// Block editor
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			$asset_mode = HappyForms_Form_Assets::MODE_ADMIN;
		}

		$asset_mode = apply_filters( 'happyforms_asset_mode', $asset_mode );

		$output = $form_controller->render( $form, $asset_mode );

		return $output;
	}

	/**
	 * Action: output scripts and styles for the forms
	 * embedded into the current post.
	 *
	 * @since 1.0
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function wp_head() {
		?>
		<!-- HappyForms global container -->
		<script type="text/javascript">HappyForms = {};</script>
		<!-- End of HappyForms global container -->
		<?php
	}

	/**
	 * Filter: Add HappyForms button markup to a markup above content editor, next to
	 * Add Media button.
	 *
	 * @since 1.1.0.
	 *
	 * @hooked filter media_buttons
	 *
	 * @param string $editor_id Editor ID.
	 *
	 * @return void
	 */
	public function insert_editor_buttons( $editor_id ) {
		global $pagenow;

		if ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow ) {
			return;
		}

		$button_html = '<a href="#" class="button happyforms-editor-button" data-title="' . __( 'Add Form', 'happyforms' ) . '"><span class="dashicons dashicons-feedback"></span><span>'. __( 'Add Form', 'happyforms' ) .'</span></a>';

		add_action( 'admin_footer', array( $this, 'output_happyforms_modal' ) );

		echo ' ' . $button_html;
	}

	public function mce_external_plugins( $plugins ) {
		if ( ! is_admin() ) {
			return $plugins;
		}

		$plugins['happyforms_shortcode'] = happyforms_get_plugin_url() . 'core/assets/js/admin/shortcode.js';

		return $plugins;
	}

	/**
	 * Render HappyForms dialog in the footer of edit post / page screen. Also
	 * prints a script block for adding shortcode to visual editor.
	 *
	 * @since 1.3.0.
	 *
	 * @hooked action admin_footer
	 *
	 * @return void
	 */
	public function output_happyforms_modal() {
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		require_once( happyforms_get_core_folder() . '/templates/admin-form-modal.php' );
	}

	public function print_shortcode_template() {
		require_once( happyforms_get_core_folder() . '/templates/admin-shortcode.php' );
	}

	public function enqueue_styles_preview() {
		if ( ! happyforms_is_preview() ) {
			return;
		}

		wp_enqueue_style(
			'happyforms-preview',
			happyforms_get_plugin_url() . 'core/assets/css/preview.css',
			array(), happyforms_get_version()
		);
	}

	/**
	 * Action: enqueue HappyForms styles and scripts
	 * for the Customizer preview part.
	 *
	 * @since  1.3
	 *
	 * @hooked action customize_preview_init
	 *
	 * @return void
	 */
	public function enqueue_scripts_preview() {
		if ( ! happyforms_is_preview() ) {
			return;
		}

		$preview_deps = apply_filters(
			'happyforms_preview_dependencies',
			array( 'backbone', 'customize-preview' )
		);

		wp_enqueue_script(
			'happyforms-preview',
			happyforms_get_plugin_url() . 'inc/assets/js/preview.js',
			$preview_deps, happyforms_get_version(), true
		);

		wp_localize_script(
			'happyforms-preview',
			'_happyformsPreviewSettings',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);

		require_once( happyforms_get_core_folder() . '/templates/preview-form-pencil.php' );
	}

	public function exclude_scripts() {
		if ( ! happyforms_is_preview() ) {
			return;
		}

		global $wp_scripts;

		$allowed_scripts = array(
			'customize-preview-widgets',
			'customize-preview-nav-menus',
			'customize-selective-refresh',
			'utils',
			'moxiejs',
		);

		foreach ( $allowed_scripts as $handle ) {
			array_merge( $allowed_scripts, $wp_scripts->registered[$handle]->deps );
		}

		foreach ( $wp_scripts->registered as $handle => $script ) {
			if ( ! wp_script_is( $handle, 'enqueued' ) ) {
				continue;
			}

			if ( ! in_array( $handle, $allowed_scripts ) ) {
				wp_dequeue_script( $handle );
			}
		}
	}

	/**
	 * Action: register the HappyForms widget.
	 *
	 * @since 1.0
	 *
	 * @hooked action widgets_init
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( 'HappyForms_Widget' );
	}

}
