<?php

class HappyForms_Message_Admin {

	/**
	 * The singleton instance.
	 *
	 * @since 1.13.0
	 *
	 * @var HappyForms_Message_Admin
	 */
	private static $instance;

	/**
	 * The dummy post type for submissions screen.
	 *
	 * @since 1.13.0
	 *
	 * @var array
	 */
	private $post_type;

	/**
	 * The singleton constructor.
	 *
	 * @since 1.13.0
	 *
	 * @return HappyForms_Message_Admin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.13.0
	 *
	 * @return void
	 */
	public function hook() {
		$this->post_type = happyforms_get_message_controller()->dummy_type;

		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'column_headers' ), PHP_INT_MAX );
		add_filter( "manage_edit-{$this->post_type}_sortable_columns", array( $this, 'sortable_columns' ), PHP_INT_MAX );
		add_filter( "views_edit-{$this->post_type}", array( $this, 'table_views' ) );
	}

	public function column_headers( $columns ) {
		$cb_column = $columns['cb'];
		$columns = array( 'cb' => $cb_column );

		$columns['submission'] = __( 'Submission', 'happyforms' );
		$columns['form'] = __( 'Submitted to', 'happyforms' );
		$columns['datetime'] = __( 'Submitted on', 'happyforms' );

		return $columns;
	}

	public function sortable_columns( $columns ) {
		$columns['form'] = 'form';
		$columns['datetime'] = 'datetime';

		return $columns;
	}

	public function table_views( $default_views ) {
		$link = '<a href="#" class="%s">%s <span class="count">(0)</span></a>';

		$views = array(
			'all' => sprintf( $link, 'current', __( 'All', 'happyforms' ) ),
			'unread' => sprintf( $link, '', __( 'Unread', 'happyforms' ) ),
			'read' => sprintf( $link, '', __( 'Read', 'happyforms' ) ),
			'spam' => sprintf( $link, '', __( 'Spam', 'happyforms' ) ),
			'trash' => sprintf( $link, '', __( 'Trash', 'happyforms' ) ),
		);

		return $views;
	}

}

/**
 * Initialize the HappyForms_Message_Admin class immediately.
 */
HappyForms_Message_Admin::instance();
