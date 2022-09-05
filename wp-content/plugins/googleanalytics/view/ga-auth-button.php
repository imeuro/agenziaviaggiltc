<?php
/**
 * GA Auth Button view.
 *
 * @package GoogleAnalytics
 */

// Template partial fallback values.
$label       = isset( $label ) ? $label : '';
$manually_id = isset( $manually_id ) ? $manually_id : '';
$button_type = isset( $button_type ) ? $button_type : '';
$url         = isset( $url ) ? $url : '';
$classes     = array();

// Determine button classes.
if ( 'auth' === $button_type ) {
	$classes[] = 'button-primary';
} else {
	$classes[] = 'button-secondary';
}
?>
<button id="ga_authorize_with_google_button"
		class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
		onclick="ga_popup.authorize( event, '<?php echo esc_attr( $url ); ?>' )"
	<?php endif; ?>
	<?php
	echo disabled(
		false === empty( $manually_id )
		|| false === Ga_Helper::are_features_enabled()
		|| true === Ga_Helper::is_curl_disabled()
	);
	?>
><?php echo esc_html( $label ); ?>
</button>
