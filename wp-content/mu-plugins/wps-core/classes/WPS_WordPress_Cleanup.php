<?php
/**
 * WordPress Cleanup
 *
 * This file registers any custom taxonomies
 *
 * @package      Core_Mu
 * @since        1.0.0
 * @link         https://github.com/wpsmith
 * @author       Travis Smith <t@wpsmith.net>
 * @copyright    Copyright (c) 2017, Travis Smith
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPS_WordPress_Cleanup' ) ) {
	class WPS_WordPress_Cleanup extends WPS_Singleton {

		private $_wp_widgets = array(
			'WP_Widget_Pages',
			'WP_Widget_Calendar',
			'WP_Widget_Archives',
			'WP_Widget_Links',
			'WP_Widget_Meta',
			'WP_Widget_Search',
			'WP_Widget_Text',
			'WP_Widget_Categories',
			'WP_Widget_Recent_Posts',
			'WP_Widget_Recent_Comments',
			'WP_Widget_RSS',
			'WP_Widget_Tag_Cloud',
			'WP_Nav_Menu_Widget',

			// Plugins
			'Akismet_Widget',
		);

		private $_dashboard_widgets = array(
			'dashboard_activity', // Activity
			'dashboard_right_now', // Right Now
			'dashboard_recent_comments', // Recent Comments
			'dashboard_incoming_links', // Incoming Links
			'dashboard_plugins', // Plugins
			'dashboard_quick_press', // Quick Press
			'dashboard_recent_drafts', // Recent Drafts
			'dashboard_primary', // WordPress Blog
			'dashboard_secondary', // Other WordPress News

			'rg_forms_dashboard', // Gravity Forms
		);

		private $_menu = array(
			'edit.php', // Posts
			'upload.php', // Media
			'edit-comments.php', // Comments
			'edit.php?post_type=page', // Pages
			'plugins.php', // Plugins
			'themes.php', // Appearance
			'users.php', // Users
			'tools.php', // Tools
			'options-general.php', // Settings
		);

//		private $_metaboxes = array(
			// WordPress
//			'revisionsdiv',
//			'postcustom',
//			'commentstatusdiv',
//			'commentsdiv',
//			'slugdiv',
//			'authordiv',
//			'pageparentdiv',
//			'postimagediv',
//			'postexcerpt',
//			'trackbacksdiv',

			// Plugins
//			'wpseo_meta',
//		);

		public $widgets;
		public $dashboard;
		public $menu;
		public $admin_bar;

		protected function __construct( $args ) {

			// Ensure we have the proper setup
			$defaults = array(
				'widgets'   => array(),
				'dashboard' => array(),
				'menu'      => array(),
				'admin_bar' => array(),
			);
			$args     = wp_parse_args( $args, $defaults );

			// Setup
			$this->widgets   = 'all' === $args['widgets'] ? $this->_wp_widgets : $args['widgets'];
			$this->dashboard = 'all' === $args['dashboard'] ? $this->_dashboard_widgets : $args['dashboard'];
			$this->menu      = 'all' === $args['menu'] ? $this->_menu : $args['menu'];
			$this->admin_bar = $args['admin_bar'];

			// Initiate Cleansing
			$this->init();

		}

		public function init() {

			// Disable Post Formats UI
			add_filter( 'enable_post_format_ui', '__return_false' );

			// Widgets
			if ( ! empty( $this->widgets ) ) {
				add_action( 'widgets_init', array( $this, 'remove_default_wp_widgets' ), 15 );
			}

			// Dashboard
			if ( ! empty( $this->dashboard ) ) {
				add_action( 'admin_menu', array( $this, 'remove_dashboard_widgets' ), 11 );
			}

			// Admin Menu Items
			if ( ! empty( $this->menu ) ) {
				add_action( 'admin_menu', array( $this, 'remove_admin_menus' ), 11 );
			}

			// Admin Bar
			if ( ! empty( $this->admin_bar ) ) {
				add_action( 'wp_before_admin_bar_render', array( $this, 'remove_admin_bar_items' ) );
			}

		}

		public static function reset_excert_metabox( $post ) {

			add_meta_box( 'postexcerpt', __( 'Excerpt' ), 'post_excerpt_meta_box', null, 'normal', 'high' );

		}

		/**
		 * Remove default WordPress widgets
		 *
		 * @since 1.0
		 */
		public function remove_default_wp_widgets() {

			foreach ( $this->widgets as $widget ) {
				if ( in_array( $widget, $this->_wp_widgets ) ) {
					unregister_widget( $widget );
				}
			}

		}

		/**
		 * Remove extra dashboard widgets
		 *
		 * @since 1.0
		 */
		public function remove_dashboard_widgets() {

			foreach ( $this->dashboard as $widget ) {
				if ( in_array( $widget, $this->_dashboard_widgets ) ) {
					remove_meta_box( $widget, 'dashboard', 'core' );
				}
			}

		}

		/**
		 * Remove admin menu items
		 *
		 * @since 1.0
		 */
		public function remove_admin_menus() {

			foreach ( $this->menu as $menu ) {
				if ( in_array( $menu, $this->_menu ) ) {
					remove_menu_page( $menu );
				}
			}

		}

		/**
		 * Remove admin bar items
		 *
		 * @since 1.0
		 * @global array $wp_admin_bar
		 */
		public function remove_admin_bar_items() {
			global $wp_admin_bar;

			foreach ( $this->admin_bar as $ab_item ) {
				$wp_admin_bar->remove_node( $ab_item );
			}

		}


	}
}
