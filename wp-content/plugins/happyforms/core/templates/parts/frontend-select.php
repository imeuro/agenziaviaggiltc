<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<div class="happyforms-part-wrap">
		<?php happyforms_the_part_label( $part, $form ); ?>

		<?php happyforms_print_part_description( $part ); ?>

		<?php
		$options = happyforms_get_part_options( $part['options'], $part, $form );

		$value = happyforms_get_part_value( $part, $form );
		$default_label = '';

		if ( is_array( $value ) ) {
			$default_label = $part['other_option_label'];
			$value = $value[0];
		} else {
			if ( '' !== $value ) {
				if ( array_key_exists( $value, $options ) ) {
					$default_label = $options[$value]['label'];
				}
			}
		}

		$placeholder_text = $part['placeholder'];
		?>
		<div class="happyforms-part__el">
			<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>
			<div class="happyforms-custom-select">
				<div class="happyforms-part__select-wrap">
					<?php
						$other_select = ( !empty( $part['other_option'] ) ) ? $part['other_option_label'] : '';
					?>
					<select name="<?php happyforms_the_part_name( $part, $form ); ?>" data-serialize class='happyforms-select' required>
							<option disabled hidden <?php echo ( $value === '' ) ? ' selected' : ''; ?> value='' class="happyforms-placeholder-option"><?php echo $placeholder_text; ?></option>
						<?php $is_grouped = false; ?>
						<?php foreach ( $options as $index => $option ) : ?>
						<?php
							if ( happyforms_is_truthy( $option['is_heading'] ) ) {
								if ( $is_grouped ) : ?>
							</optgroup>
								<?php endif; ?>
							<optgroup label="<?php echo esc_attr( $option['label'] ); ?>" id="<?php echo esc_attr( $option['id'] ); ?>">
						<?php
								$is_grouped = true;
								continue;
							}

							$option_value = isset( $option['value'] ) ? $option['value'] : $index;
							$submissions_left_label = isset( $option['submissions_left_label'] ) ? ' ' . $option['submissions_left_label'] : '';
							$selected = ( $value !== '' && $value == $option_value ) ? ' selected' : '';
							$disabled = ( '' != $option['limit_submissions_amount'] && $option['submissions_left'] == 0 ) ? ' disabled' : '';
						?>
							<option value="<?php echo $option_value; ?>" <?php echo $selected; ?> <?php echo $disabled; ?> id="<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_attr( $option['label'] ); ?><?php echo $submissions_left_label; ?></option>
						<?php endforeach; ?>
						<?php if ( $is_grouped ) : ?>
							</optgroup>
						<?php endif; ?>
						<?php if ( !empty( $other_select ) ) : ?>
							<option id="other-option" value="<?php echo '999';?>"><?php echo $other_select; ?></option>
						<?php endif; ?>
					</select>
				</div>
			</div>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>

		</div>
	</div>
</div>
