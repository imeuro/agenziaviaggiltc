<?php
class HappyForms extends HappyForms_Core {

	public $default_notice;

	public $action_archive = 'archive';

	public $onboarding_list_url = 'https://emailoctopus.com/lists/a58bf658-425e-11ea-be00-06b4694bee2a/members/embedded/1.3/add';

	public $action_onboarding = 'happyforms-submit-onboarding';

	public function initialize_plugin() {
		parent::initialize_plugin();

		add_action( 'happyforms_do_setup_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_email_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_style_control', array( $this, 'do_control' ), 10, 3 );
		add_filter( 'happyforms_setup_controls', array( $this, 'add_dummy_setup_controls' ) );
		add_filter( 'happyforms_email_controls', array( $this, 'add_dummy_email_controls' ) );
		add_filter( 'happyforms_style_controls', array( $this, 'add_dummy_style_controls' ) );
		add_action( 'parse_request', array( $this, 'parse_archive_request' ) );
		add_action( 'admin_init', [ $this, 'register_modals' ] );
		add_action( 'load-plugins.php', array( $this, 'redirect_to_forms_page' ) );
		add_action( 'happyforms_modal_dismissed', [ $this, 'modal_dismissed' ] );
		add_action( "wp_ajax_{$this->action_onboarding}", [ $this, 'ajax_action_onboarding' ] );
		add_filter( 'happyforms_dashboard_modal_settings', [ $this, 'get_dashboard_modal_settings' ] );

		if ( is_admin() ) {
			require_once( happyforms_get_integrations_folder() . '/classes/class-integrations-page-controller.php' );
		}

		$this->register_dummy_parts();
	}

	public function register_dummy_parts() {
		$part_library = happyforms_get_part_library();

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-website-url-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_WebsiteUrl_Dummy', 3 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-attachment-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Attachment_Dummy', 6 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-phone-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Phone_Dummy', 11 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-date-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Date_Dummy', 12 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-scale-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Scale_Dummy', 14 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-signature-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Signature_Dummy', 19 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-rating-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Rating_Dummy', 20 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-optin-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_OptIn_Dummy', 22 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-scrollable-terms-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_ScrollableTerms_Dummy', 23 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-payments-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Payments_Dummy', 24 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-layout-drawer-group.php' );
		$part_library->register_part( 'HappyForms_Part_LayoutDrawerGroup', 25 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-layout-title-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_LayoutTitle_Dummy', 26 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-placeholder-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Placeholder_Dummy', 27 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-media-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Media_Dummy', 28 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-toggletip-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Toggletip_Dummy', 29 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-divider-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Divider_Dummy', 30 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-page-break-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_PageBreak_Dummy', 31 );

		require_once( happyforms_get_include_folder() . '/classes/class-answer-limiter-dummy.php' );
	}

	public function add_dummy_setup_controls( $controls ) {
		$controls[11] = array(
			'type' => 'upsell',
			'label' => __( 'Upgrade', 'happyforms' ),
			'field' => '',
			'id' => 'happyforms-redirect-upsell',
		);

		$controls[1449] = array(
			'type' => 'text_dummy',
			'dummy_id' => 'redirect_url',
			'label' => __( 'Redirect to this page address (URL) after submission', 'happyforms' ),
		);

		$controls[1450] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'shuffle_parts',
			'label' => __( 'Shuffle order of fields', 'happyforms' ),
		);

		$controls[1500] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'captcha',
			'label' => __( 'Use reCAPTCHA', 'happyforms' ),
		);

		$controls[1650] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'save_abandoned_responses',
			'label' => __( 'Save incomplete and abandoned submissions', 'happyforms' ),
		);

		$controls[1655] = array(
			'type' => 'number_dummy',
			'dummy_id' => 'abandoned_resume_response_expire',
			'label' => __( 'Let submitters save a draft for set number of days', 'happyforms' ),
		);

		$controls[1800] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'preview_before_submit',
			'label' => __( 'Require submitters to review a submission', 'happyforms' ),
		);

		$controls[2301] = array(
			'type' => 'number_dummy',
			'dummy_id' => 'restrict_entries',
			'label' => __( 'Max number of submissions', 'happyforms' ),
		);

		$controls[3190] = array(
			'type' => 'number_dummy',
			'dummy_id' => 'delete_submission_days',
			'label' => __( "Erase submitter's personal data after set number of days", 'happyforms' ),
		);

		$controls[3200] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'block_emails',
			'label' => __( 'Show an error message if field contains words in Disallowed Comment Keys', 'happyforms' ),
		);

		return $controls;
	}

	public function add_dummy_email_controls( $controls ) {
		$controls[500] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'email_mark_and_reply',
			'label' => __( 'Include reply link', 'happyforms' ),
		);

		$controls[531] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'alert_email_include_referral_url',
			'label' => __( 'Include referral web address', 'happyforms' ),
		);

		$controls[630] = array(
			'type' => 'email-parts-list_dummy',
			'dummy_id' => 'confirmation_email_respondent_address',
			'label' => __( 'To email address', 'happyforms' ),
		);

		$controls[1660] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'abandoned_resume_send_alert_email',
			'label' => __( 'Send abandonment email', 'happyforms' ),
		);

		return $controls;
	}

	public function add_dummy_style_controls( $controls ) {
		$controls[5791] = array(
			'type' => 'panel_dummy',
			'dummy_id' => 'checkboxes_radios',
			'label' => __( 'Checkboxes & Radios', 'happyforms' ),
		);

		$controls[5792] = array(
			'type' => 'panel_dummy',
			'dummy_id' => 'rating',
			'label' => __( 'Rating', 'happyforms' ),
		);

		$controls[5793] = array(
			'type' => 'panel_dummy',
			'dummy_id' => 'separators',
			'label' => __( 'Separators', 'happyforms' ),
		);

		$controls[8560] = array(
			'type' => 'panel_dummy',
			'dummy_id' => 'multistep',
			'label' => __( 'Multi Step', 'happyforms' ),
		);

		return $controls;
	}

	public function do_control( $control, $field, $index ) {
		$type = $control['type'];

		if ( 'checkbox_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/checkbox_dummy.php' );
		}

		if ( 'number_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/number_dummy.php' );
		}

		if ( 'email-parts-list_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/email-parts-list-dummy.php' );
		}

		if ( 'panel_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/panel_dummy.php' );
		}

		if ( 'text_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/text_dummy.php' );
		}
	}

	public function admin_enqueue_scripts() {
		parent::admin_enqueue_scripts();

		wp_enqueue_style(
			'happyforms-free-admin',
			happyforms_get_plugin_url() . 'inc/assets/css/admin.css',
			array( 'thickbox' ), happyforms_get_version()
		);

		wp_enqueue_script(
			'happyforms-free-admin',
			happyforms_get_plugin_url() . 'inc/assets/js/admin/dashboard.js',
			array( 'happyforms-admin' ), happyforms_get_version(), true
		);

		wp_enqueue_style(
			'happyforms-dashboard-modals-upgrade',
			happyforms_get_plugin_url() . 'inc/assets/css/dashboard-modals.css',
			array( 'happyforms-dashboard-modals' ), happyforms_get_version()
		);

		$this->enqueue_onboarding_modal();
		$this->enqueue_upgrade_modal();
	}

	public function parse_archive_request() {
		global $pagenow;

		if ( 'edit.php' !== $pagenow ) {
			return;
		}

		$form_post_type = happyforms_get_form_controller()->post_type;

		if ( ! isset( $_GET['post_type'] ) || $form_post_type !== $_GET['post_type'] ) {
			return;
		}

		if ( ! isset( $_GET[$this->action_archive] ) ) {
			return;
		}

		$form_id = $_GET[$this->action_archive];
		$form_controller = happyforms_get_form_controller();
		$message_controller = happyforms_get_message_controller();
		$form = $form_controller->get( $form_id );

		if ( ! $form ) {
			return;
		}

		$message_controller->export_archive( $form );
	}

	public function is_new_user( $forms ) {
		if ( 1 !== count( $forms ) ) {
			return false;
		}

		$form = $forms[0];

		if ( 'Sample Form' === $form['post_title'] ) {
			return true;
		}

		return false;
	}

	public function register_modals() {
		$modals = happyforms_get_dashboard_modals();

		$modals->register_modal( 'upgrade' );
		$modals->register_modal( 'onboarding', [ 'dismissible' => true ] );
	}

	public function redirect_to_forms_page() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		if ( 'edit-happyform' === $screen->id ) {
			return;
		}

		if ( happyforms_get_dashboard_modals()->is_dismissed( 'onboarding' ) ) {
			return;
		}

		$tracking = happyforms_get_tracking();
		$status = $tracking->get_status();

		if ( 1 < intval( $status['status'] ) ) {
			return;
		}

		$url = admin_url( 'edit.php?post_type=happyform' );
		wp_safe_redirect( $url );

		exit;
	}

	public function enqueue_onboarding_modal() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		if ( 'edit-happyform' !== $screen->id ) {
			return;
		}

		$modals = happyforms_get_dashboard_modals();

		if ( $modals->is_dismissed( 'onboarding' ) ) {
			return;
		}

		wp_add_inline_script(
			'happyforms-dashboard-modals',
			"( function( $ ) { $( function() { happyForms.modals.openOnboardingModal(); } ); } )( jQuery );"
		);
	}

	public function enqueue_upgrade_modal() {
		global $pagenow;

		$message_post_type = happyforms_get_message_controller()->dummy_type;
		$current_post_type = get_current_screen()->post_type;

		$is_activity_screen = (
			in_array( $pagenow, array( 'edit.php', 'post.php' ) )
			&& ( $current_post_type === $message_post_type )
		);

		$is_integrations_screen = (
			isset( $_GET['page'] )
			&& 'happyforms-integrations' === $_GET['page']
		);

		$is_coupons_screen = (
			isset( $_GET['page'] )
			&& 'happyforms-coupons' === $_GET['page']
		);

		if ( ! $is_activity_screen && ! $is_integrations_screen && ! $is_coupons_screen ) {
			return;
		}

		ob_start();
		?>

		( function( $ ) {

		happyForms.modals.closeModal = function() {
			window.location.href = '<?php echo get_admin_url() . 'edit.php?post_type=happyform'; ?>';
		}

		$( function() {
			happyForms.modals.openUpgradeModal();
		} );

		} )( jQuery );

		<?php
		$script = ob_get_clean();

		wp_add_inline_script( 'happyforms-dashboard-modals', $script );
	}

	public function modal_dismissed( $id ) {
		if ( 'onboarding' === $id ) {
			happyforms_get_tracking()->update_status( 2 );
		}
	}

	public function ajax_action_onboarding() {
		$email = isset( $_POST['email'] ) ? $_POST['email'] : '';
		$email = trim( $email );

		// Submit to EmailOctopus
		if ( ! empty( $email ) ) {
			wp_remote_post( $this->onboarding_list_url, array(
				'body' => array(
					'field_0' => $email,
				),
			) );
		}
	}

	public function get_dashboard_modal_settings( $settings ) {
		$settings['onboardingModalAction'] = $this->action_onboarding;
		$settings['onboardingModalNonce'] = wp_create_nonce( $this->action_onboarding );

		return $settings;
	}

	public function admin_screens() {
		parent::admin_screens();

		global $pagenow;

		$message_post_type = happyforms_get_message_controller()->dummy_type;
		$current_post_type = get_current_screen()->post_type;

		if ( in_array( $pagenow, array( 'edit.php', 'post.php' ) )
			&& ( $current_post_type === $message_post_type ) ) {

			require_once( happyforms_get_include_folder() . '/classes/class-message-admin.php' );
		}
	}

}
