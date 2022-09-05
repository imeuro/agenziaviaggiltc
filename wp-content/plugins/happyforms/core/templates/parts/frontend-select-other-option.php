<?php

$selected = false;
$text_value = '';
$value = happyforms_get_part_value( $part, $form );

if ( is_array( $value ) ) {
	if ( 999 === $value[0] ) {
		$selected = true;

		if ( isset( $value[1] ) ) {
			$text_value = $value[1];
		}
	}
}


?>

<div class="happyforms-part__option happyforms-part-option happyforms-part-option--other" id="<?php echo $part['id']; ?>_other">
	<input type="text" name="<?php happyforms_the_part_name( $part, $form ); ?>" placeholder="<?php echo $part['other_option_placeholder']; ?>" aria-labelledby="hf-label-<?php happyforms_the_part_name( $part, $form ); ?>" value="<?php echo $text_value; ?>" class="happyforms-select-dropdown-other <?php echo ( $selected ) ? 'hf-show' : ''; ?>" <?php happyforms_the_part_attributes( $part, $form ); ?> <?php happyforms_parts_autocorrect_attribute( $part ); ?> />
</div>
