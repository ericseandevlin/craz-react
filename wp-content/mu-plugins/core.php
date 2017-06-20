<?php

/**
 * Plugin Name: WPS Core
 * Plugin URI: http://www.wpsmith.net/
 * Description: WPS Core
 * Version: 1.0.0
 * Author: Travis Smith
 * Author URI: http://www.wpsmith.net/
 * Text Domain: wps-core
 * Domain Path: languages
 * License: GPLv2

    Copyright 2017  Travis Smith  (email : http://wpsmith.net/contact)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * WPS Core.
 *
 * @package      WPS_Core
 * @since        1.0.0
 * @copyright    Copyright (c) 2014-2017, Travis Smith <t@wpsmith.net>
 * @license      GPL-2.0+
 */
define( 'WPSCORE_PLUGIN_DOMAIN', 'wps-core' );
define( 'WPSCORE_PLUGIN_NAME', __( 'WPS Core', WPSCORE_PLUGIN_DOMAIN ) );
define( 'WPSCORE_PLUGIN_SLUG', plugin_basename( __FILE__  ) );

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	wp_die( __( "Sorry, you are not allowed to access this page directly.", WPSCORE_PLUGIN_DOMAIN ) );
}

spl_autoload_register( 'wps_core_autoload' );
/**
 *  SPL Class Autoloader for WPS_* classes.
 *
 * @param string $class_name Class name being autoloaded.
 *
 * @link http://us1.php.net/spl_autoload_register
 * @author    Travis Smith
 * @since 0.1.0
 */
function wps_core_autoload( $class_name ) {

// Do nothing if class already exists, not prefixed WPS_ or K12_
	if ( class_exists( $class_name, false ) || ( false === strpos( $class_name, 'WPS_' ) ) ) {
		return;
	}

// Load file
	$file = plugin_dir_path( __FILE__ ) . "wps-core/classes/$class_name.php";
	include_once( $file );

}

// Do some basic cleanup
WPS_WordPress_Cleanup::get_instance( array(
	'widgets' => array(
		'WP_Widget_Pages',
		'WP_Widget_Calendar',
		'WP_Widget_Archives',
		'WP_Widget_Links',
		'WP_Widget_Meta',
		'WP_Widget_Categories',
		'WP_Widget_RSS',
		'WP_Widget_Tag_Cloud',

		// Plugins
		'Akismet_Widget',
	),
	'dashboard' => array(
		'dashboard_right_now', // Right Now
		'dashboard_recent_comments', // Recent Comments
		'dashboard_incoming_links', // Incoming Links
		'dashboard_plugins', // Plugins
		'dashboard_recent_drafts', // Recent Drafts
		'dashboard_primary', // WordPress Blog
		'dashboard_secondary', // Other WordPress News
	),
//	'menu' => array(
//		'edit.php', // Posts
//		'upload.php', // Media
//		'edit-comments.php', // Comments
//		'edit.php?post_type=page', // Pages
//		'plugins.php', // Plugins
//		'themes.php', // Appearance
//		'users.php', // Users
//		'tools.php', // Tools
//		'options-general.php', // Settings
//	),
) );

// Login Branding
WPS_Brand::get_instance();

// WP SEO
add_filter( 'wpseo_metabox_prio', function() {
	return 'default';
});
