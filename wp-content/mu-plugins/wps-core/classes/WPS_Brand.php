<?php

/**
 * WPS Core Branding Class
 *
 * @since 0.0.6
 *
 * @package   WPS_Core
 * @author    Travis Smith <t@wpsmith.net>
 * @license   GPL-2.0+
 * @link      http://wpsmith.net
 * @copyright 2014 Travis Smith, WP Smith, LLC
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPS_Brand' ) ) {
	/**
	 *  Branding Class
	 *
	 * @package WPS_Core
	 * @author Travis Smith <t@wpsmith.net>
	 */
	class WPS_Brand extends WPS_Singleton {

		/**
		 *  Class Args.
		 *
		 * @var array
		 *
		 * @since 1.0.0
		 */
		public $args = array();

		/**
		 *  Constructor.
		 *
		 * @since 1.0.0
		 */
		protected function __construct( $args = array() ) {

			if ( ! empty( $args ) ) {
				$this->args = $args;
			}
			add_action( 'login_enqueue_scripts', array( $this, 'login_styles' ) );

			add_filter( 'login_headerurl', array( 'WPS_Brand', 'login_headerurl' ) );
			add_filter( 'login_headertitle', array( 'WPS_Brand', 'login_headertitle' ) );
		}

		/**
		 *  Returns current home url.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url Default URI.
		 *
		 * @return string           Home URI.
		 */
		public static function login_headerurl( $url ) {
			return get_bloginfo( 'url' );
		}

		/**
		 *  Returns current home url.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url URI.
		 *
		 * @return string            Home URI.
		 */
		public static function login_headertitle() {
			return get_bloginfo( 'name' ) . ' &#124; ' . get_bloginfo( 'description' );
		}

		/**
		 * Implodes array to be key=>value string
		 *
		 * @param array $array Array to implode.
		 *
		 * @return string Imploded array.
		 */
		public static function implode( $array ) {
			$r = '';
			foreach ( $array as $key => $value ) {
				$r .= sprintf( '%s: %s ', $key, $value );
			}

			return trim( $r );
		}

		/**
		 *  Custom inline login styles.
		 *
		 * @since 1.0.0
		 */
		public function login_styles() {

			// @todo Check login.css exists in theme, if so load it!
			if ( ! empty( $this->args ) && is_string( $this->args['login_style'] ) ) {
				printf( '<style type="text/css">body.login div#login h1 a { %s }</style>', $this->args['login_style'] );
			} else {
				$defaults = array(
					'background-size' => '90px 90px',
					'height'          => '90px',
					'width'           => '320px',
					'padding-bottom'  => '30px',
				);

				$login_style = ! empty( $args ) && $args['login_style'] ? wp_parse_args( $args['login_style'], $defaults ) : $defaults;

				printf(
					'<style type="text/css">body.login div#login h1 a { %s; }</style>',
					WPS_Brand::implode( $login_style )
				);
			}
		}

		/**
		 * Determines whether current page is the login page.
		 *
		 * @since 1.0.0
		 */
		public static function is_login_page() {
			return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php', ) );
		}

	}
}
