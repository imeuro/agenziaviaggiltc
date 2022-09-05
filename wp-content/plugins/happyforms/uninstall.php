<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

// Free version specific options
delete_transient( 'happyforms_review_notice_recommend' );
delete_option( 'happyforms_modal_dismissed_onboarding' );
delete_option( 'happyforms_show_powered_by' );
delete_option( '_happyforms_received_submissions' );

// Stop cleanup if upgrade version is currently active.
if ( defined( 'HAPPYFORMS_UPGRADE_VERSION' ) ) {
	return;
}

// Forms
$statuses = array( 'publish', 'trash', 'any' );

foreach( $statuses as $status ) {
	$form_ids = get_posts( array(
		'post_type' => 'happyform',
		'post_status' => $status,
		'numberposts' => -1,
		'fields' => 'ids',
	) );

	foreach( $form_ids as $form_id ) {
		wp_delete_post( $form_id, true );
	}
}

// General options
delete_option( 'widget_happyforms_widget' );
delete_option( 'happyforms-tracking' );
delete_option( 'ttf_updates_key_happyforms' );

// User meta
$users = get_users();

foreach( $users as $user ) {
	delete_user_meta( $user->ID, 'happyforms-dismissed-notices' );
	delete_transient( 'happyforms_admin_notices_' . md5( $user->user_login ) );
	delete_user_meta( $user->ID, 'happyforms-settings-sections-states' );
}

// Migrations
delete_option( 'happyforms-data-version' );

// Old deactivation flag
delete_option( '_happyforms_cleanup_on_deactivate' );