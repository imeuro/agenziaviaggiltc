<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<div class="happyforms-part-wrap">
		<?php if ( 'as_placeholder' !== $part['label_placement'] ) : ?>
			<?php happyforms_the_part_label( $part, $form ); ?>
		<?php endif; ?>

		<?php happyforms_print_part_description( $part ); ?>

		<div class="happyforms-part__el">
			<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>

			<?php
			$textarea_rows = apply_filters( 'happyforms_long_text_field_rows', 5, $part, $form );
			?>
			<textarea id="<?php happyforms_the_part_id( $part, $form ); ?>" name="<?php happyforms_the_part_name( $part, $form ); ?>" rows="<?php echo $textarea_rows; ?>" placeholder="<?php echo esc_attr( $part['placeholder'] ); ?>" <?php happyforms_the_part_attributes( $part, $form ); ?> <?php happyforms_parts_autocorrect_attribute( $part ); ?> ><?php happyforms_the_part_value( $part, $form ); ?></textarea>
			<?php if ( 'as_placeholder' === $part['label_placement'] ) : ?>
				<?php happyforms_the_part_label( $part, $form ); ?>
			<?php endif; ?>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>
		</div>
	</div>
</div>
