<?php
	$submit_button_extra_class = '';
	if( happyforms_get_form_property( $form, 'add_submit_button_class' ) == 1 ) {
		$submit_button_extra_class = happyforms_get_form_property( $form, 'submit_button_html_class' );
	} ?>
<div class="happyforms-form__part happyforms-part happyforms-part--submit">
	<?php do_action( 'happyforms_form_submit_before', $form ); ?>
	<button type="submit" class="happyforms-submit happyforms-button--submit <?php echo $submit_button_extra_class; ?>"><?php echo esc_attr( happyforms_get_form_property( $form, 'submit_button_label' ) ); ?></button>
	<?php do_action( 'happyforms_form_submit_after', $form ); ?>
</div>