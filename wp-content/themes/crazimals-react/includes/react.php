<?php

/**
 * Serialize and save post content to object
 */
add_action( 'save_post', 'react_post_save_callback' );
function react_post_save_callback( $post_id ) {

	$post = get_post( $post_id );
	if ( in_array( get_post_type( $post ), [ 'post', 'page' ] ) ) {

		require_once __DIR__ . '/classes/react-html-serializer.php';

		// logout user
		$current_user = wp_get_current_user();
		$user_id      = 0;
		if ( $current_user instanceof WP_User ) {
			$user_id = $current_user->ID;
			wp_set_current_user( 0 );
		}

		$content = apply_filters( 'the_content', $post->post_content );

		// try get post summary
		$summary = false;
		if ( in_array( get_post_type( $post ), [ 'post' ] ) ) {
			$extended = get_extended( $post->post_content );
			if ( ! empty( $extended['extended'] ) ) {
				$summary = apply_filters( 'the_content', $extended['main'] );
			} else {
				$summary = '';
			}
		}

		// restore user
		if ( $user_id > 0 ) {
			wp_set_current_user( $user_id );
		}

		$serializer = new React_Html_Serializer();

		$tree = $serializer->serialize( $content );

		update_post_meta( $post_id, 'react-html-tree', $tree );

		if ( $summary !== false ) {
			$summary_tree = null;
			if ( '' === $summary ) {
				$summary_tree = array_slice( $tree, 0, 2 );
			} else {
				$serializer   = new React_Html_Serializer();
				$summary_tree = $serializer->serialize( $summary );
			}
			update_post_meta( $post_id, 'react-html-summary-tree', $summary_tree );
		}
	}
}

/**
 * Parse <img /> params
 *
 * @param $img_html
 *
 * @return array|null
 */
function react_get_image_params( $img_html ) {
	$matches = [];
	preg_match_all( '/(alt|title|src|srcset|class|id)=["\']([^"\']*)["\']/i', $img_html, $matches );
	$attributes = $matches[1];
	$values     = $matches[2];
	$params     = [];
	foreach ( $attributes as $index => $attribute ) {
		if ( 'class' == $attribute ) {
			$attribute = 'className';
		} elseif ( 'srcset' == $attribute ) {
			$attribute = 'srcSet';
		}

		$params[ $attribute ] = $values[ $index ];
	}

	return count( $params ) == 0 ? null : $params;
}

function wps_react_get_menu( $menu ) {
	$menu_obj   = wp_get_nav_menu_object( $menu );
	$menu_items = wp_get_nav_menu_items( $menu_obj->term_id );
	_wp_menu_item_classes_by_context( $menu_items );
	$sorted_menu_items = $menu_items_with_children = array();

	foreach ( (array) $menu_items as $menu_item ) {
//		wps_write_log( $menu_item, '$menu_item' );
		$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
		if ( $menu_item->menu_item_parent ) {
			$menu_items_with_children[ $menu_item->menu_item_parent ] = true;
		}
	}

	// Add the menu-item-has-children class where applicable
	if ( $menu_items_with_children ) {
		foreach ( $sorted_menu_items as &$menu_item ) {
			if ( isset( $menu_items_with_children[ $menu_item->ID ] ) ) {
				$menu_item->classes[] = 'menu-item-has-children';
			}
		}
	}

	$menu_items        = array_values( array_map( 'wps_react_get_post', $menu_items ) );
	$sorted_menu_items = array_values( array_map( 'wps_react_get_post', $sorted_menu_items ) );

//	unset( $menu_items, $menu_item );

	/**
	 * Filters the sorted list of menu item objects before generating the menu's HTML.
	 *
	 * @since 3.1.0
	 *
	 * @param array $sorted_menu_items The menu items, sorted by each menu item's menu order.
	 * @param stdClass $args An object containing wp_nav_menu() arguments.
	 */
//	$sorted_menu_items = apply_filters( 'wp_nav_menu_objects', $sorted_menu_items, [] );

	return array(
		'id'     => $menu_obj->term_id,
		'name'   => $menu_obj->name,
		'slug'   => $menu_obj->slug,
		'link'   => get_term_link( $menu_obj->term_id, 'nav_menu' ),
		'_data'  => $menu_obj,
		'_items' => $menu_items,
		'items'  => $sorted_menu_items,
	);
}

function wps_react_convert_post( $post_id, $post_type = 'post' ) {
	$post_data = null;
	if ( preg_match( '/\d+/i', $post_id ) ) {
		$post_data = get_post( absint( $post_id ), OBJECT );
	}
	if ( empty( $post_data ) ) {
		$post_data = get_page_by_path( $post_id, OBJECT, $post_type );
	}

	if ( ! empty( $post_data ) ) {
		$post_data->meta = get_post_meta( $post_data->ID );
	}

	return $post_data;
}

/**
 * @param WP_REST_Request $request
 * @param WP_REST_Response $response
 * @param $post
 *
 * @return WP_REST_Response
 */
function wps_react_get_post_request( $response, $post, $post_type ) {
	if ( empty ( $post ) ) {
		$response->set_status( 404 );

		return $response;
	}

//	$context = trim( $request->get_param( 'context' ) );
//	if ( ! in_array( $context, [ 'view', 'feed' ] ) ) {
//		$context = 'view';
//	}

	$post_data = wps_react_get_post( $post );

//	if ( 'view' == $context ) {
//		$post_data['content'] = $meta['react-html-tree'];
////		$post_data['content'] = get_post_meta( $post->ID, 'react-html-tree', true );
//	}

	$response->set_data( [ $post_type => $post_data ] );

	return $response;
}

function wps_react_get_post( $post ) {
	$categories = wp_list_pluck( (array) get_the_terms( $post, 'category' ), 'term_id' );
	$tags       = wp_list_pluck( (array) get_the_terms( $post, 'post_tag' ), 'term_id' );
	$meta       = get_post_meta( $post->ID );

	$color = "#ffffff";
	if ( ! empty( $meta['bg_color'] ) ) {
		$color = $meta['bg_color'][0];
	}
	$template = '16-9'; // '1-1', '2-3'
	if ( ! empty( $meta['template'] ) ) {
		$template = $meta['template'][0];
	}
//	if (empty($meta['react-html-tree'])) {
//		require_once __DIR__ . '/classes/react-html-serializer.php';
//		$serializer = new React_Html_Serializer();
//		$tree = $serializer->serialize( $post->post_content );
//	} else {
//		$tree = $meta['react-html-tree'];
//	}

	$r = [
		'id'         => absint( $post->ID ),
		'slug'       => $post->post_name,
		'type'       => $post->post_type,
		'title'      => get_the_title( $post ),
		'thumbnail'  => react_get_image_params( get_the_post_thumbnail( $post, 'react-thumbnail' ) ),
		'summary'    => get_post_meta( $post->ID, 'react-html-summary-tree', true ),
		'content'    => get_post_meta( $post->ID, 'react-html-tree', true ),
		'meta'       => $meta,
		'color'      => $color,
		'template'   => '_' . $template,
		'date'       => get_post_time( 'U', true, $post ),
		'link'       => get_permalink( $post ),
		'categories' => array_filter( $categories ),
		'tags'       => array_filter( $tags ),
		'author'     => absint( $post->post_author ),
		'_data'      => $post,
	];

	if ( 'nav_menu_item' === $post->post_type ) {
		$r['meta']['html'] = wp_nav_menu( array(
			'menu' => $post->ID,
			'echo' => false,
		) );
		$r['link']         = $post->url;
		$r['title']        = $post->title;
		$r['attr_title']   = esc_attr( $post->title );
		$r['classes']      = $post->classes;
		$r['order']        = $post->menu_order;
		$r['slug']         = $post->attr_title ? $post->attr_title : sanitize_title_with_dashes( $post->title );
	}

	return $r;
}

function wps_react_map_terms( $f, $terms ) {
	$keys = wp_list_pluck( $terms, 'slug' );

	return array_map( $f, $keys, array_combine( $keys, $terms ) );
}