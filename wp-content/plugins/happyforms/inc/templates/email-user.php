<?php
/**
 *
 * Email content
 *
 */
?>
<?php echo html_entity_decode( $form['confirmation_email_content'] ); ?>

<br><br>

<?php if ( happyforms_get_form_property( $form, 'confirmation_email_include_values' ) ) : ?>

	<?php $message = array( 'parts' => $response ); ?>

	<?php foreach( $form['parts'] as $part ) : ?>

		<?php if ( happyforms_email_is_part_visible( $part, $form, $message ) ) : ?>

		<b><?php echo happyforms_get_email_part_label( $message, $part, $form ); ?></b><br>
		<?php echo happyforms_get_email_part_value( $message, $part, $form ); ?>
		<br><br>

		<?php endif; ?>

	<?php endforeach; ?>

<?php endif; ?>

<?php do_action( 'happyforms_email_user_after' ); ?>