<?php 
add_action( 'wp_enqueue_scripts', 'accelerate_ltc_enqueue_styles' );
function accelerate_ltc_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
} 
?>