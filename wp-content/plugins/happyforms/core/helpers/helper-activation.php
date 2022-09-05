<?php
/**
 *
 * Core activation/deactivation hooks
 *
 */
if ( ! function_exists( 'happyforms_first_run' ) ):

function happyforms_activate() {
	do_action( 'happyforms_activate' );
}

endif;

if ( ! function_exists( 'happyforms_deactivate' ) ):

function happyforms_deactivate() {
	do_action( 'happyforms_deactivate' );
}

endif;

register_activation_hook( happyforms_plugin_file(), 'happyforms_activate' );
register_deactivation_hook( happyforms_plugin_file(), 'happyforms_deactivate' );

/**
 *
 * Hooked activation/deactivation/remove routines
 *
 */
if ( ! function_exists( 'happyforms_create_samples' ) ):

function happyforms_create_samples() {
	require_once( happyforms_get_core_folder() . '/classes/class-cache.php' );
	require_once( happyforms_get_core_folder() . '/classes/class-tracking.php' );
	require_once( happyforms_get_core_folder() . '/helpers/helper-misc.php' );

	$tracking = happyforms_get_tracking();
	$status = $tracking->get_status();

	if ( 0 < intval( $status['status'] ) ) {
		return;
	}

	require_once( happyforms_get_core_folder() . '/classes/class-form-controller.php' );
	require_once( happyforms_get_core_folder() . '/classes/class-form-part-library.php' );
	require_once( happyforms_get_core_folder() . '/classes/class-form-setup.php' );
	require_once( happyforms_get_core_folder() . '/classes/class-form-styles.php' );
	require_once( happyforms_get_core_folder() . '/classes/class-validation-messages.php' );
	require_once( happyforms_get_core_folder() . '/classes/class-form-messages.php' );
	require_once( happyforms_get_core_folder() . '/classes/class-session.php' );
	require_once( happyforms_get_core_folder() . '/helpers/helper-form-templates.php' );
	require_once( happyforms_get_core_folder() . '/helpers/helper-validation.php' );

	$part_library = happyforms_get_part_library();
	$form_controller = happyforms_get_form_controller();

	// Create a new form
	$form = $form_controller->create();

	// Get the new form default data
	$form_data = $form_controller->get( $form->ID );

	$form_data['post_title'] = __( 'Sample Form', 'happyforms' );
	$form_data['submit_button_label'] = __( 'Get in touch', 'happyforms' );

	$form_parts = array(
		array(
			'type' => 'single_line_text',
			'label' => __( 'Full name', 'happyforms' ),
		),
		array(
			'type' => 'email',
			'label' => __( 'Work email', 'happyforms' ),
			'width' => 'half',
		),
		array(
			'type' => 'single_line_text',
			'label' => __( 'Job title', 'happyforms' ),
			'width' => 'half',
			'required' => false,
		),
		array(
			'type' => 'single_line_text',
			'label' => __( 'Company name', 'happyforms' ),
			'width' => 'half',
		),
		array(
			'type' => 'select',
			'label' => __( 'Industry', 'happyforms' ),
			'width' => 'half',
			'placeholder' => __( 'Choose from this list', 'happyforms' ),
			'options' => array(
				array(
					'label' => __( 'Agriculture, animals and food', 'happyforms' ),
				),
				array(
					'label' => __( 'Science, environment and construction', 'happyforms' ),
				),
				array(
					'label' => __( 'Healthcare, wellbeing and sport', 'happyforms' ),
				),
				array(
					'label' => __( 'Creative arts, fashion and media', 'happyforms' ),
				),
				array(
					'label' => __( 'Government, law and education', 'happyforms' ),
				),
				array(
					'label' => __( 'Accountancy, finance and insurance', 'happyforms' ),
				),
				array(
					'label' => __( 'Business, sales and tourism', 'happyforms' ),
				),
				array(
					'label' => __( 'Charity, social work and religion', 'happyforms' ),
				),
				array(
					'label' => __( 'Something else', 'happyforms' ),
				),
			),
		),
		array(
			'type' => 'multi_line_text',
			'label' => __( 'Tell us about your project', 'happyforms' ),
			'rows' => 10,
		),
		array(
			'type' => 'radio',
			'label' => __( 'How might you describe your company size?', 'happyforms' ),
			'options' => array(
				array(
					'label' => __( 'It\'s just me', 'happyforms' ),
				),
				array(
					'label' => __( 'There\'s two of us', 'happyforms' ),
				),
				array(
					'label' => __( 'We\'re a small group', 'happyforms' ),
				),
				array(
					'label' => __( 'We\'re a big team', 'happyforms' ),
				),
			),
		),
		array(
			'type' => 'single_line_text',
			'label' => __( 'How did you hear about us?', 'happyforms' ),
			'required' => false,
		),
		array(
			'type' => 'checkbox',
			'label' => __( 'Privacy compliance', 'happyforms' ),
			'label_placement' => 'hidden',
			'options' => array(
				array(
					'label' => __( 'I agree to the storage and handling of my data by this website', 'happyforms' ),
				),
			),
		),
	);

	foreach( $form_parts as $part_id => $part_data ) {
		$part_type = $part_data['type'];
		$part_complete_id = "{$part_type}_$part_id";
		$part_data['id'] = $part_complete_id;
		$part = $part_library->get_part( $part_type );
		$part_defaults = $part->get_customize_defaults();
		$part_data = wp_parse_args( $part_data, $part_defaults );

		if ( isset( $part_data['options'] ) ) {
			foreach( $part_data['options'] as $option_id => $option_data ) {
				$option_data['id'] = "{$part_complete_id}_{$option_id}";
				$part_data['options'][$option_id] = $option_data;
			}
		}

		$form_data['parts'][] = $part_data;
	}

	// Update the new form with default parts
	$form_data = $form_controller->update( $form_data );

	// Store an option to avoid creating new forms on reactivation
	$tracking->update_status( 1 );

	// Force a permalinks refresh
	flush_rewrite_rules();
}

endif;

add_action( 'happyforms_activate', 'happyforms_create_samples' );
