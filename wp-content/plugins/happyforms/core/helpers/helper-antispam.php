<?php

if ( ! function_exists( 'happyforms_is_spambot' ) ) :

function happyforms_is_spambot() {
	$is_spambot = ( defined( 'HAPPYFORMS_IS_SPAMBOT' ) && HAPPYFORMS_IS_SPAMBOT );
	
	return $is_spambot;
}

endif;

if ( ! function_exists( 'happyforms_validate_honeypot' ) ) :

function happyforms_validate_honeypot( $form ) {
	$honeypot_name = $form['ID'] . 'single_line_text_-1';
	$names = array( 'single_line_text', 'multi_line_text', 'number' );
	$validated = true;

	foreach( $names as $key ) {
		$name = $form['ID'] . '-' . $key;

		if ( isset( $_REQUEST[$name] ) ) {
			$validated = $validated && ( empty( $_REQUEST[$name] ) );
		}
	}

	return $validated;
}

endif;

if ( ! function_exists( 'happyforms_validate_hash' ) ) :

function happyforms_validate_hash( $form ) {
	if ( ! isset( $_POST['hash'] ) ) {
		return false;
	}

	$post = array_diff_key( $_POST, array_flip( array( 'hash', 'platform_info' ) ) );
	$hash = '';

	array_walk_recursive( $post, function( $value, $key ) use( &$hash ) {
		$hash .= $value;
	} );

	$hash = preg_replace( '/[^\w\d]/m', '', $hash );
	$hash = md5( $hash );

	if ( $hash !== $_POST['hash'] ) {
		return false;
	}
	
	return true;
}

endif;

if ( ! function_exists( 'happyforms_validate_browser' ) ) :

function happyforms_validate_browser( $form ) {
	if ( ! isset( $_POST['platform_info'] ) ) {
		return false;
	}

	$platform_info = $_POST['platform_info'];
	
	// User Agent
	$user_agent = isset( $platform_info['user_agent'] ) ? $platform_info['user_agent'] : '';

	if ( preg_match( '/headless/mi', $user_agent ) ) {
		return false;
	}

	// App version
	$app_version = isset( $platform_info['app_version'] ) ? $platform_info['app_version'] : '';

	if ( preg_match( '/headless/mi', $app_version ) ) {
		return false;
	}

	// Languages
	$language = isset( $platform_info['language'] ) ? $platform_info['language'] : false;
	$languages_length = isset( $platform_info['languages_length'] ) ? $platform_info['languages_length'] : 0;

	if ( empty( $language ) || 0 == $languages_length ) {
		return false;
	}

	// Webdriver
	$webdriver = isset( $platform_info['webdriver'] ) ? $platform_info['webdriver'] : false;

	if ( $webdriver ) {
		return false;
	}

	return true;
}

endif;