<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Quick_Empty_Trash {
	private $dir;
	private $file;
	private $assets_dir;
	private $assets_url;

	/**
	 * Constructor
	 * @param  string $file Plugin base file
	 * @return void
	 */
	public function __construct( $file ) {
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		// Add button to list table for all post types
		add_action( 'restrict_manage_posts', array( $this, 'add_button' ), 90 );

		// Load admin styles
		add_action( 'admin_enqueue_scripts', array( $this, 'load_styles' ) );
	}

	/**
	 * Display empty trash button on list tables
	 * @return void
	 */
	public function add_button() {
		global $typenow, $pagenow;

		// Don't show on comments list table
		if( 'edit-comments.php' == $pagenow ) return;

		// Don't show on trash page
		if( isset( $_REQUEST['post_status'] ) && $_REQUEST['post_status'] == 'trash' ) return;

		// Don't show if current user is not allowed to edit other's posts for this post type
		if ( ! current_user_can( get_post_type_object( $typenow )->cap->edit_others_posts ) ) return;

		// Don't show if there are no items in the trash for this post type
		if( 0 == intval( wp_count_posts( $typenow, 'readable' )->trash ) ) return;

		?>
		<div class="alignleft empty_trash">
			<input type="hidden" name="post_status" value="trash" />
			<?php submit_button( __( 'Empty Trash' ), 'apply', 'delete_all', false ); ?>
		</div>
		<?php
	}

	/**
	 * Load CSS
	 * @return void
	 */
	public function load_styles() {
		global $pagenow;

		if( 'edit.php' == $pagenow ) {
			wp_register_style( 'quick-empty-trash-admin', $this->assets_url . 'css/admin.css', array(), '1.0.0' );
			wp_enqueue_style( 'quick-empty-trash-admin' );
		}
	}

}