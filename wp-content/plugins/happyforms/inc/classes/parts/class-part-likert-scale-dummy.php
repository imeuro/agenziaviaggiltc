<?php

class HappyForms_Part_LikertScale_Dummy extends HappyForms_Form_Part {

	public $type = 'likert_scale_dummy';

	public function __construct() {
		$this->label = __( 'Scale', 'happyforms' );
		$this->description = __( 'For collecting ratings using a fixed numeric scale.', 'happyforms' );
	}

}
