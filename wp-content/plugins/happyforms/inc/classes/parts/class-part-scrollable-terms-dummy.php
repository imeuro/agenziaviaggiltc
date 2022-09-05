<?php

class HappyForms_Part_ScrollableTerms_Dummy extends HappyForms_Form_Part {

	public $type = 'scrollable_terms_dummy';
	
	public function __construct() {
		$this->label = __( 'Scrollable Terms', 'happyforms' );
		$this->description = __( 'For adding terms of service text and similar that requires user to scroll to bottom.', 'happyforms' );
	}
	
}