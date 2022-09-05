<?php

class HappyForms_Part_Toggletip_Dummy extends HappyForms_Form_Part {

	public $type = 'toggletip_dummy';

	public function __construct() {
		$this->label = __( 'Toggletip', 'happyforms' );
		$this->description = __( 'For letting submitters reveal more information as they need it.', 'happyforms' );
	}

}
