<?php

class HappyForms_Part_Divider_Dummy extends HappyForms_Form_Part {

	public $type = 'divider_dummy';

	public function __construct() {
		$this->label = __( 'Separator', 'happyforms' );
		$this->description = __( 'For adding a horizontal rule to visually separate fields.', 'happyforms' );
	}

}
