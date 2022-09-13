<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<div class="happyforms-part-wrap">
		<?php happyforms_the_part_label( $part, $form ); ?>

		<?php happyforms_print_part_description( $part ); ?>

		<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>

		<div class="happyforms-part__el">
			<?php
			$options = happyforms_get_part_options( $part['options'], $part, $form );
			$value = happyforms_get_part_value( $part, $form );
			$checkmark_content = '<span class="happyforms-radio-circle"></span>';

			foreach( $options as $o => $option ) : ?>
				<?php
				$option_classes = 'happyforms-part__option happyforms-part-option';

				if ( happyforms_is_truthy( $option['is_heading'] ) ) {
					$option_classes .= ' option-heading';
				?>
			<div class="<?php echo $option_classes; ?>" id="<?php echo esc_attr( $option['id'] ); ?>">
				<label class="heading-label"><?php echo esc_attr( $option['label'] ); ?></label>
			</div>
				<?php
					continue;
				}
				$checked = ( $o === $value ) ? 'checked="checked"' : '';


				if ( is_string( $value ) ) {
					$checked = checked( $value, $o, false );
				}

				if ( '' != $option['limit_submissions_amount'] && 0 == $option['submissions_left'] ) {
					$option_classes .= ' disabled-option';
				}

				?>
			<div class="<?php echo $option_classes; ?>" id="<?php echo esc_attr( $option['id'] ); ?>">
				<label class="option-label">
					<input type="radio" class="happyforms-visuallyhidden" name="<?php happyforms_the_part_name( $part, $form ); ?>" value="<?php echo $o; ?>" <?php echo $checked; ?> <?php happyforms_the_part_attributes( $part, $form ); ?> <?php echo ( empty( $option['submissions_left'] ) && '' != $option['limit_submissions_amount'] ) ? 'disabled' : '' ?>>
					<span class="checkmark"><?php echo $checkmark_content; ?></span>
					<span class="label-wrap">
						<span class="label"><?php echo esc_attr( $option['label'] ); ?></span><?php echo $option['submissions_left_label']; ?>
					</span>
				</label>
				<?php if ( ! empty(  $option['description'] ) ) : ?>
				<div class="happyforms-part-option__description"><?php echo esc_attr( $option['description'] ); ?></div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>
		</div>
	</div>
</div>
