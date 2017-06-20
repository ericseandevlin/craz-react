<?php

/**
 * Create API router with namespace 'api/v1'
 * @see https://github.com/shtrihstr/simple-rest-api
 */
$api = new Simple_REST_API\Router( 'api/v1' );

/**
 * GET /wp-json/api/v1/posts-by/category/category-name?page=1
 *
 */
$api->get( '/posts-by/category/{category}', function ( WP_REST_Request $request, WP_REST_Response $response, $category ) {

	if ( empty ( $category ) ) {
		$response->set_status( 404 );

		return $response;
	}

	if ( 0 == ( $page = absint( $request->get_param( 'page' ) ) ) ) {
		$page = 1;
	}

	$args = [
		'category_name'          => $category->slug,
		'paged'                  => $page,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];

//	add_filter( 'posts_fields', 'wps_api_post_fields' );
//	add_filter( 'posts_join', 'wps_api_join' );
	$query = new WP_Query( $args );
//	remove_filter( 'posts_fields', 'wps_api_post_fields' );
//	remove_filter( 'posts_join', 'wps_api_join' );

	$response->set_data( [
		'slugs' => wp_list_pluck( $query->posts, 'post_name' ),
		'found' => absint( $query->found_posts ),
		'pages' => absint( $query->max_num_pages ),
		'page'  => $page,
	] );

	return $response;

} )->convert( 'category', function ( $category ) {
	return get_category_by_slug( trim( $category ) );
} );


/**
 * GET /wp-json/api/v1/posts-by/tag/tag-name
 *
 */
$api->get( '/posts-by/tag/{tag}', function ( WP_REST_Request $request, WP_REST_Response $response, $tag ) {

	if ( empty ( $tag ) ) {
		$response->set_status( 404 );

		return $response;
	}

	if ( 0 == ( $page = absint( $request->get_param( 'page' ) ) ) ) {
		$page = 1;
	}

	$args = [
		'tag'                    => $tag->slug,
		'paged'                  => $page,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];

	$query = new WP_Query( $args );

	$response->set_data( [
		'slugs' => wp_list_pluck( $query->posts, 'post_name' ),
		'found' => absint( $query->found_posts ),
		'pages' => absint( $query->max_num_pages ),
		'page'  => $page,
	] );

	return $response;

} )->convert( 'tag', function ( $tag ) {
	return get_term_by( 'slug', trim( $tag ), 'post_tag' );
} );


/**
 * GET /wp-json/api/v1/posts-by/author/author-name
 */
$api->get( '/posts-by/author/{author}', function ( WP_REST_Request $request, WP_REST_Response $response, $author ) {

	if ( empty ( $author ) ) {
		$response->set_status( 404 );

		return $response;
	}

	if ( 0 == ( $page = absint( $request->get_param( 'page' ) ) ) ) {
		$page = 1;
	}

	$args = [
		'author_name'            => $author->user_nicename,
		'paged'                  => $page,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];

	$query = new WP_Query( $args );

	$response->set_data( [
		'slugs' => wp_list_pluck( $query->posts, 'post_name' ),
		'found' => absint( $query->found_posts ),
		'pages' => absint( $query->max_num_pages ),
		'page'  => $page,
	] );

	return $response;

} )->convert( 'author', function ( $author ) {
	return get_user_by( 'slug', trim( $author ) );
} );


/**
 * GET /wp-json/api/v1/posts-by/date/desc
 */
$api->get( '/posts-by/date/{order}', function ( WP_REST_Request $request, WP_REST_Response $response, $order ) {
	$post_data = null;

	if ( 0 == ( $page = absint( $request->get_param( 'page' ) ) ) ) {
		$page = 1;
	}

	$args = [
		'orderby'                => 'date',
		'order'                  => $order,
		'paged'                  => $page,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];

	if ( 1 === $page && $slug = $request->get_param( 'post' ) ) {
		$post_data = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $post_data ) {
			$args['post__not_in'] = array( $post_data->ID );
		}
	}

	$query = new WP_Query( $args );
	if ( $post_data ) {
		array_unshift( $query->posts, $post_data );
	}

	$response->set_data( [
		'slugs' => wp_list_pluck( $query->posts, 'post_name' ),
		'found' => absint( $query->found_posts ),
		'pages' => absint( $query->max_num_pages ),
		'page'  => $page,
	] );

	return $response;

} )->convert( 'order', function ( $order ) {
	$order = mb_strtoupper( $order );

	return in_array( $order, [ 'ASC', 'DESC' ] ) ? $order : 'DESC';
} );


/**
 * GET /wp-json/api/v1/posts-by/search?query=abc
 *
 */
$api->get( '/posts-by/search', function ( WP_REST_Request $request, WP_REST_Response $response ) {
	$s = $request->get_param( 'query' );

	if ( 0 == ( $page = absint( $request->get_param( 'page' ) ) ) ) {
		$page = 1;
	}

	$args = [
		's'                      => $s,
		'paged'                  => $page,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];

	$query = new WP_Query( $args );

	$response->set_data( [
		'slugs' => wp_list_pluck( $query->posts, 'post_name' ),
		'found' => absint( $query->found_posts ),
		'pages' => absint( $query->max_num_pages ),
		'page'  => $page,
	] );

	return $response;
} );


/**
 * GET /wp-json/api/v1/post/{slug-or-id}?context=view|feed
 *
 */
$api->get( '/post/{post}', function ( WP_REST_Request $request, WP_REST_Response $response, $post ) {
	return wps_react_get_post_request( $response, $post, 'post' );
} )->convert( 'post', function ( $post ) {
	return wps_react_convert_post( $post, 'post' );
} );


/**
 * GET /wp-json/api/v1/page/{slug-or-id}?context=view|feed
 *
 */
$api->get( '/page/{page}', function ( WP_REST_Request $request, WP_REST_Response $response, $page ) {
	return wps_react_get_post_request( $response, $page, 'page' );
} )->convert( 'page', function ( $page ) {
	return wps_react_convert_post( $page, 'page' );
} );


/**
 * GET /wp-json/api/v1/authors
 *
 */
$api->get( '/authors', function () {

	$users = get_users( [
		'who'                 => 'authors',
		'has_published_posts' => true
	] );

	if ( is_multisite() ) {
		foreach ( get_super_admins() as $super_admin ) {
			$user = get_user_by( 'login', $super_admin );
			if ( count_user_posts( $user->ID, 'post' ) > 0 ) {
				$users[] = $user;
			}
		}
	}

	$authors = array_values( array_map( function ( $user ) {
		return [
			'id'     => $user->ID,
			'slug'   => esc_attr( $user->user_nicename ),
			'name'   => esc_html( $user->display_name ),
			'avatar' => react_get_image_params( get_avatar( $user->user_email, 48 ) ),
			'link'   => get_author_posts_url( $user->ID ),
		];
	}, $users ) );

	return [ 'authors' => $authors ];

} );


/**
 * GET /wp-json/api/v1/categories
 *
 */
$api->get( '/categories', function () {

	$categories = array_values( array_map( function ( $term ) {
		return [
			'id'   => $term->term_id,
			'name' => $term->name,
			'slug' => $term->slug,
			'link' => get_category_link( $term->term_id )
		];
	}, get_terms( 'category', [ 'update_term_meta_cache' => false, 'parent' => 0 ] ) ) );

	return [ 'categories' => $categories ];
} );


/**
 * GET /wp-json/api/v1/tags
 *
 */
$api->get( '/tags', function () {

	$tags = array_values( array_map( function ( $term ) {
		return [
			'id'   => $term->term_id,
			'name' => $term->name,
			'slug' => $term->slug,
			'link' => get_category_link( $term->term_id )
		];
	}, get_terms( 'post_tag', [ 'update_term_meta_cache' => false ] ) ) );

	return [ 'tags' => $tags ];
} );

/**
 * GET /wp-json/api/v1/menus
 *
 */
$api->get( '/menus', function ( WP_REST_Request $request, WP_REST_Response $response ) {
//	$response->set_data( [
//		'slugs' => wp_list_pluck( $query->posts, 'post_name' ),
//		'found' => absint( $query->found_posts ),
//		'pages' => absint( $query->max_num_pages ),
//		'page'  => $page,
//	] );

	$locations = get_nav_menu_locations();
	$terms     = get_terms( 'nav_menu', [
		'update_term_meta_cache' => false,
		'hide_empty'             => false,
		'include'                => array_values( $locations ),
	] );

//	$menus = wps_react_map_terms( function ( $term ) {
//		return wps_react_get_menu( $term );
//	},  $terms);
	$slugs = wp_list_pluck( $terms, 'term_id' );

	$menus = array_map( function ( $term ) {
		return wps_react_get_menu( $term );
	}, array_combine( $slugs, $terms ) );

	$response->set_data( [ 'menus' => $menus, 'locations' => $locations, ] );

	return $response;

} );

/**
 * GET /wp-json/api/v1/menus/locations
 *
 */
$api->get( '/menus/locations', function ( WP_REST_Request $request, WP_REST_Response $response ) {

	$locations = get_nav_menu_locations();

//	wps_write_log( $locations, '$locations' );

	$response->set_data( [ 'locations' => $locations ] );

	return $response;

} );

/**
 * GET /wp-json/api/v1/menu
 *
 */
$api->get( '/menu/{menu}', function ( WP_REST_Request $request, WP_REST_Response $response, $menu ) {

	if ( empty ( $menu ) ) {
		$response->set_status( 404 );

		return $response;
	}

	$response->set_data( [ 'menu' => $menu ] );

	return $response;

} )->convert( 'menu', function ( $menu ) {
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu ] ) ) {
		return wps_react_get_menu( $locations[ $menu ] );
	}

	return array(
		'id'    => null,
		'name'  => null,
		'slug'  => null,
		'link'  => null,
		'items' => null,
	);
} );

