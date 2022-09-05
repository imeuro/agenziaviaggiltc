<?php

class HappyForms_Part_Attachment_Dummy extends HappyForms_Form_Part {

	public $type = 'attachment_dummy';

	public function __construct() {
		$this->label = __( 'File Upload', 'happyforms' );
		$this->description = __( 'For allowing files to be uploaded with specific requirements.', 'happyforms' );
	}

}
