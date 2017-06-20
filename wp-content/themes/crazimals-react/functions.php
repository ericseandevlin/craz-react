<?php

/**
 * autoload vendors
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * REST API endpoints
 */
require_once __DIR__ . '/includes/api.php';

/**
 * react prepare date functions
 */
require_once __DIR__ . '/includes/react.php';

/**
 * setup default redux state
 */
require_once __DIR__ . '/includes/state.php';


/**
 * theme setups
 */
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );
add_theme_support( 'menus' );

add_image_size( 'react-thumbnail', 1600, 900, true );
add_image_size( 'react-thumbnail-mob', 800, 450, true );

add_filter( 'show_admin_bar', '__return_false' );

add_action( 'wp_enqueue_scripts', 'wps_react_enqueue_scripts' );
/**
 * Enqueue theme & react scripts
 */
function wps_react_enqueue_scripts() {
	// VideoJS
	$videojs_version = '5.19';
	wp_enqueue_script( 'videojs', '//vjs.zencdn.net/5.19/video.min.js', null,  $videojs_version);
	wp_enqueue_style( 'videojs-style', '//vjs.zencdn.net/5.19/video-js.min.css', null, $videojs_version);

	// React App
	wps_react_enqueue_script( 'app', '/app/built/app.js', null );

	// Theme
	$theme = wp_get_theme();
	wps_react_enqueue_style( 'webflow', '/css/webflow.min.css' );
	wps_react_enqueue_style( 'style', '/style.css' );

	// Font
	wp_enqueue_style( 'google-font-lato', '//fonts.googleapis.com/css?family=Open+Sans:300,400,600', array(), $theme->Version );
}

/**
 * @param string $name Name of the script.
 * @param string $path Path to the local file.
 */
function wps_react_enqueue_script( $name, $path ) {
	wp_enqueue_script(
		$name,
		get_template_directory_uri() . $path,
		null,
		filemtime( get_template_directory() . $path ),
		true
	);
}

/**
 * @param string $name Name of the script.
 * @param string $path Path to the local file.
 * @param array $deps Dependent styles. Default: null.
 */
function wps_react_enqueue_style( $name, $path, $deps = null ) {
	wp_enqueue_style(
		$name,
		get_template_directory_uri() . $path,
		$deps,
		filemtime( get_template_directory() . $path )
	);
}

/**
 * unset standard wp search
 */
if ( ! is_admin() && isset( $_GET['s'] ) ) {
	unset( $_GET['s'] );
}

// Maybe deprecate the below
//posts_fields
remove_filter( 'posts_fields', 'wps_api_post_fields' );
function wps_api_post_fields( $sql ) {
	global $wpdb;
	$wpdb->query( 'SET SESSION group_concat_max_len = 10000' );

	return $sql . ", GROUP_CONCAT(pm.meta_key ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_keys, GROUP_CONCAT(pm.meta_value ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_values ";
//	return $sql . ", (select pm.* From $wpdb->postmeta AS pm WHERE pm.post_id = p.ID)";
}

//posts_fields
remove_filter( 'posts_join', 'wps_api_join' );
function wps_api_join( $sql ) {
	global $wpdb;

	return $sql . " LEFT JOIN $wpdb->postmeta pm on pm.post_id = p.ID";
//	return $sql . ", (select pm.* From $wpdb->postmeta AS pm WHERE pm.post_id = p.ID)";
}

register_nav_menus( array(
	'primary' => __( 'Primary Navigation Menu', '' ),
	'footer'  => __( 'Footer Navigation Menu', '' ),
) );