<?php

class HappyForms_Part_Placeholder_Dummy extends HappyForms_Form_Part {

	public $type = 'placeholder_dummy';

	public function __construct() {
		$this->label = __( 'Paragraph', 'happyforms' );
		$this->description = __( 'For adding helper text, notes and formatted messages.', 'happyforms' );
	}

}
