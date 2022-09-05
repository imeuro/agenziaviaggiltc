<?php

class HappyForms_Part_LayoutTitle_Dummy extends HappyForms_Form_Part {

	public $type = 'layout_title_dummy';

	public function __construct() {
		$this->label = __( 'Heading', 'happyforms' );
		$this->description = __( 'For adding titles to visually separate fields.', 'happyforms' );
	}

}
