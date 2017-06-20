<?php

/**
 * Plugin Name: WPS Debug
 * Plugin URI: http://www.wpsmith.net/
 * Description: WPS debugging utilities.
 * Version: 1.0.0
 * Author: Travis Smith
 * Author URI: http://www.wpsmith.net/
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

if ( ! function_exists( 'wps_is_debug' ) ) {
	function wps_is_debug() {
		return ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) );
	}
}

if ( ! function_exists( 'wps_write_log' ) ) {
	function wps_write_log( $log, $title = '' ) {
		if ($title) {
			$title .= ': ';
		}
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( $title . print_r( $log, true ) );
		} else {
			error_log( $title . $log );
		}
	}
}

if ( ! function_exists( 'wps_printr' ) ) {
	function wps_printr( $args, $name = '' ) {
		if ( apply_filters( 'wps_debug_off', false ) ) {
			return;
		}
		if ( '' != $name ) {
			echo '<strong>' . $name . '</strong><br/>';
		}
		echo '<pre>', htmlspecialchars( print_r( $args, true ) ), "</pre>\n";
	}
}

if ( ! function_exists( 'wps_die' ) ) {
	function wps_die( $args, $name = '' ) {

		wps_printr( $args, $name );
		wp_die();

	}
}
