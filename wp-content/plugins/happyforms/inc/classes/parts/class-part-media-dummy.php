<?php

class HappyForms_Part_Media_Dummy extends HappyForms_Form_Part {

	public $type = 'media_dummy';

	public function __construct() {
		$this->label = __( 'Media', 'happyforms' );
		$this->description = __( 'For adding a single image, video, animated gif or audio clip.', 'happyforms' );
	}

}
