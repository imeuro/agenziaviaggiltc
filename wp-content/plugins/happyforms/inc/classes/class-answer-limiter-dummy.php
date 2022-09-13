<?php
class HappyForms_Answer_Limiter_Dummy {
	private static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		$supported_parts = $this->get_supported_parts();

		foreach ( $supported_parts as $part ) {
			add_action( "happyforms_part_customize_{$part}_before_advanced_options", array( $this, 'add_part_controls' ) );
		}

	}

	public function get_supported_parts() {
		$parts = array(
			'single_line_text',
			'multi_line_text',
			'email',
			'number',
		);

		return $parts;
	}

	public function add_part_controls() {
		?>
		<p class="input_dummy">
			<label><?php _e( 'Max times the same answer can be submitted', 'happyforms' ); ?>:</label>
			<span class="members-only"><?php _e( 'Members Only', 'happyforms') ?></span>
			<input type="number" />
		</p>
		<?php
	}

}

if ( ! function_exists( 'happyforms_answer_limiter' ) ) :

function happyforms_answer_limiter_dummy() {
	return HappyForms_Answer_Limiter_Dummy::instance();
}

endif;

happyforms_answer_limiter_dummy();
