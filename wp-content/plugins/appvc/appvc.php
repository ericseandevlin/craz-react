<?php
/**
 * Plugin Name: WPBakery Visual Composer - WP API Support
 * Description: Ensures shortcodes are done for the WP API.
 * Author: Travis Smith
 * Author URI: http://wpsmith.net
 * Version: 1.0.0
 * Plugin URI: https://wordpress.org/plugins/wp-api-vc/
 */


/**
 * Modify REST API content for pages to force
 * shortcodes to render since Visual Composer does not
 * do this
 */
add_action( 'rest_api_init', function () {
	foreach ( get_post_types( array( 'public' => 1, ), 'names' ) as $post_type ) {
   register_rest_field(
          $post_type,
          'content',
          array(
                 'get_callback'    => 'vc_wpapi_do_content_shortcodes',
                 'update_callback' => null,
                 'schema'          => null,
          )
       );
   register_rest_field(
          $post_type,
          'excerpt',
          array(
                 'get_callback'    => 'vc_wpapi_do_excerpt_shortcodes',
                 'update_callback' => null,
                 'schema'          => null,
          )
       );
 }
});

function vc_wpapi_do_content_shortcodes( $object, $field_name, $request ) {
   WPBMap::addAllMappedShortcodes(); // This does all the work

   global $post;
   $post = get_post( $object['id'] );
   $output['rendered'] = apply_filters( 'the_content', $post->post_content );

   return $output;
}

function vc_wpapi_do_excerpt_shortcodes( $object, $field_name, $request ) {
   WPBMap::addAllMappedShortcodes(); // This does all the work

   global $post;
   $post = get_post( $object['id'] );
   add_filter( 'the_excerpt', 'do_shortcode' );
   $output['rendered'] = apply_filters( 'the_excerpt', $post->post_content );

   return $output;
}