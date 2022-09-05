<?php

class HappyForms_Part_LayoutDrawerGroup extends HappyForms_Form_Part {

	public $type  = 'layout_drawer_group';
	public $group = 'drawer_group';

	public function __construct() {
		$this->label = __( 'Design', 'happyforms' );
		$this->description = '';
	}

}
