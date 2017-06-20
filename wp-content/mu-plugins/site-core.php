<?php
/**
 * Plugin Name: Your Site Core Functionality
 * Plugin URI: http://www.wpsmith.net/
 * Description: Designed and developed for SITE!!
 * Version: 1.0.0
 * Author: Travis Smith
 * Author URI: http://www.wpsmith.net/
 * License: GPLv2
 *
 * Copyright 2017  Travis Smith  (email : http://wpsmith.net/contact)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

define( 'SITECORE_PLUGIN_DOMAIN', 'site-core' );

/* Prevent direct access to the plugin */
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( "Sorry, you are not allowed to access this page directly.", SITECORE_PLUGIN_DOMAIN ) );
}

define( 'SITECORE_PLUGIN_NAME', __( 'SITE Core', SITECORE_PLUGIN_DOMAIN ) );
define( 'SITECORE_PLUGIN_SLUG', plugin_basename( __FILE__ ) );
define( 'SITECORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SITECORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SITECORE_DEBUG', true );

spl_autoload_register( 'site_acf_core_autoload' );
/**
 *  SPL Class Autoloader for SITE_* classes.
 *
 * @param string $class_name Class name being autoloaded.
 *
 * @link http://us1.php.net/spl_autoload_register
 * @author    Travis Smith
 * @since 0.1.0
 */
function site_acf_core_autoload( $class_name ) {

	// Do nothing if class already exists, not prefixed WPS_ or K12_
	if ( class_exists( $class_name, false ) || ( false === strpos( $class_name, 'Site_Core_' ) ) ) {
		return;
	}

	// Load file
	$file = plugin_dir_path( __FILE__ ) . "site-core/classes/$class_name.php";
	include_once( $file );

}

// Core SITE
//Site_Core_ACF::get_instance();

// ACF
require_once( 'advanced-custom-fields-pro/acf.php' );

// Load ACF Builder
require_once( 'wps-core/acf/acf-builder/lib/autoload.php' );
require_once( 'wps-core/acf/acf-builder/autoload.php' );

// Site Fields
add_action( 'init', 'siite_core_plugins_loaded', 4 );
function siite_core_plugins_loaded() {
	$fields = Site_Core_Fields::get_instance();
}