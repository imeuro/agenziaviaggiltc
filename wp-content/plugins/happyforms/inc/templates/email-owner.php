<?php
/**
 *
 * Part loop
 *
 */
?>

<?php $message = array( 'parts' => $response ); ?>

<?php foreach( $form['parts'] as $part ) : ?>
	<?php if ( happyforms_email_is_part_visible( $part, $form, $message ) ) : ?>

	<b><?php echo happyforms_get_email_part_label( $message, $part, $form ); ?></b><br>
	<?php echo happyforms_get_email_part_value( $message, $part, $form ); ?>
	<br><br>

	<?php endif; ?>

<?php endforeach; ?>

<?php do_action( 'happyforms_email_owner_after' ); ?>