<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

class YM_Core_Fields extends WPS_Singleton {

	public $builder;

	protected function __construct() {

		$this->builder = new FieldsBuilder(
			'content_areas',
			array(
				'button_label' => __( 'Add Content', SITECORE_PLUGIN_DOMAIN ),
			)
		);

		$this->add_shortcodes();

		if ( did_action( 'init' ) || doing_action( 'init' ) ) {
			$this->create();
		} else {
			add_action( 'init', array( $this, 'create' ) );
		}


		add_filter( 'ym_content_before', function ( $content_before ) {
			return '<section class="block-wrap block-wrap-wp-content"><div class="block block-wp-content"><div class="block-inner"><article class="block-the-content">' . $content_before;
		} );

		add_filter( 'ym_content_after', function ( $content_after ) {
			return $content_after . '</article></div></div></section>';
		} );


		add_filter( 'the_content', array( $this, 'add_to_content' ) );

	}

	public function create() {

		$this->builder
			->addFlexibleContent( 'blocks' )
			// Hero
			->addLayout( 'hero' )
			->addFields( $this->get_content() )
			->addFields( $this->get_cta() )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )
			// Features
			->addLayout( 'features' )
			->addFields( $this->get_posts( 'feature' ) )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )

			// Services
			->addLayout( 'services' )
			->addFields( $this->get_posts( 'service' ) )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )
			
			// Logins
			->addLayout( 'logins' )
			->addFields( $this->get_posts( 'login' ) )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )

			// Featured Content
			->addLayout( 'featured_content' )
			->addFields( $this->get_posts(
				array(
					'post',
					'product',
					'infographic',
					'ebook',
					'video',
					'webinar',
				),
				__( 'Featured Content', SITECORE_PLUGIN_DOMAIN )
			) )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )
			// Resource Library
			->addLayout( 'resource_library' )
			->addFields( $this->get_posts( array(
				'infographic',
				'ebook',
				'video',
				'webinar',
			), __( 'Resource Library', SITECORE_PLUGIN_DOMAIN ) ) )
			->addFields( $this->get_cta() )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )
			// Events
			->addLayout( 'event' )
			->addFields( $this->get_posts( 'event' ) )
			->addFields( $this->get_cta() )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )
			// Call to Action
			->addLayout( 'call_to_action' )
			->addFields( $this->get_cta() )
			->addFields( $this->get_attributes() )
			// Sliders
			->addLayout( 'slider' )
			->addFields( $this->get_slides() )
			->addFields( $this->get_background() )
			->addFields( $this->get_slider_settings() )
			->addFields( $this->get_attributes() )

			// Content
			->addLayout( 'content' )
			->addFields( $this->get_content() )
			->addFields( $this->get_cta() )
			->addFields( $this->get_background() )
			->addFields( $this->get_attributes() )
			// Number boxes
			->addLayout( 'number_box' )
			->addRepeater( 'number_boxes' )
			->addImage( 'image' )
			->addNumber( 'number' )
			->addText( 'heading' )
			->endRepeater();

		$this->set_location();


		if ( did_action( 'acf/init' ) || doing_action( 'acf/init' ) ) {
			$this->init_fields();
		} else {
			add_action( 'acf/init', array( $this, 'init_fields' ) );
//			$builder = $this->builder;
//			add_action( 'acf/init', function () use ( $builder ) {
//				wps_write_log( $builder->build() );
//				acf_add_local_field_group( $builder->build() );
//			} );
		}
	}

	public function init_fields() {
		wps_write_log( $this->builder->build() );
		acf_add_local_field_group( $this->builder->build() );
	}

	public function set_location() {
		$this->builder
			->setLocation( 'post_type', '==', 'page' )
			->or( 'post_type', '==', 'post' )
			->or( 'post_type', '==', 'article' )
			->or( 'post_type', '==', 'product' )
			->or( 'post_type', '==', 'landing-page' );
	}

	public function get_attributes() {
		$attributes = new FieldsBuilder(
			'attributes',
			array(
				'button_label' => __( 'Add Attributes', SITECORE_PLUGIN_DOMAIN ),
			)
		);
		$attributes
			->addTab( __( 'Attributes', SITECORE_PLUGIN_DOMAIN ) )
			->addSelect( 'alignment' )
			->addChoices( ym_core_get_alignment(), array(
				'default_value' => '',
			) )
			->addText( 'classes' )
			->addRepeater( 'data' )
			->addText( 'key' )
			->addText( 'value' )
			->endRepeater();

		return $attributes;
	}

	public function get_posts( $pt, $label = '' ) {
		if ( is_array( $pt ) ) {
			$post_types = $pt;
			$slug       = implode( '-', $pt );
			$label      = '' !== $label ? $label : __( 'Posts', SITECORE_PLUGIN_DOMAIN );
		} else {
			$post_type  = get_post_type_object( $pt );
			$post_types = array( $pt );
			$label      = $post_type->label;
			$slug       = $post_type->name;
		}

		$posts = new FieldsBuilder( $slug );
		$posts
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addRelationship(
				$slug,
				array(
					'label'     => $label,
					'post_type' => $post_types,
				)
			);

		return $posts;
	}

	public function get_background() {
		$background = new FieldsBuilder(
			'background',
			array(
				'button_label' => __( 'Add Background', SITECORE_PLUGIN_DOMAIN ),
			)
		);
		$background
			->addTab( __( 'Background', SITECORE_PLUGIN_DOMAIN ) )
			->addSelect( 'bg_type', array(
				'label' => __( 'Background Type', SITECORE_PLUGIN_DOMAIN ),
			) )
			->addChoices( ym_core_get_bg_types() )
			->addImage( 'image' )
			->conditional( 'bg_type', '==', 'image' )
			->addSelect( 'theme_color' )
			->addChoices( ym_core_get_theme_colors() )
			->conditional( 'bg_type', '==', 'theme_color' )
			->addColorPicker( 'bg_color', array(
				'allow_null' => 1,
			) )
			->conditional( 'bg_type', '==', 'bg_color' )
		;

		return $background;
	}

	public function get_content() {
		$content = new FieldsBuilder(
			'content',
			array(
				'button_label' => __( 'Add Content', SITECORE_PLUGIN_DOMAIN ),
			)
		);
		$content
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addFlexibleContent( 'columns', array( 'min' => 1, 'max' => 6 ) )
			// Title
			->addLayout( 'title_content' )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addText( 'title' )
			->addText( 'subtitle' )
			->addFields( $this->get_attributes() )
			// Content
			->addLayout( 'content' )
//			->addRepeater( 'columns', array( 'min' => 1, 'max' => 6 ) )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addWysiwyg( 'content' )
			->addFields( $this->get_attributes() )
			// Image
			->addLayout( 'image_content' )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addImage( 'image' )
			->addFields( $this->get_attributes() )
			// Video
			->addLayout( 'video_content' )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addOembed( 'video' )
			->addFields( $this->get_attributes() )
			// Gallery
			->addLayout( 'gallery_content' )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addGallery( 'gallery' )
			->addSelect( 'orderby', array(
				'default_value' => 'ID',
			) )
			->addChoices( ym_core_get_gallery_orderby() )
			->addSelect( 'order', array(
				'default_value' => 'ID',
			) )
			->addChoices( ym_core_get_gallery_order() )
			->addNumber( 'columns', array(
				'min'           => 1,
				'max'           => 6,
				'default_value' => 4,
			) )
			->addSelect( 'size', array(
				'default_value' => 'thumbnail',
			) )
			->addChoices( ym_core_get_image_sizes() )
			->addFields( $this->get_attributes() )
			// File
			->addLayout( 'file_content' )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addFile( 'file' )
			->addFields( $this->get_attributes() )//			->endRepeater()
		;

		return $content;
	}

	public function get_slider_settings() {
		$content = new FieldsBuilder(
			'slider_settings',
			array(
				'button_label' => __( 'Add Setting', SITECORE_PLUGIN_DOMAIN ),
			)
		);
		$content
			->addTab( __( 'Settings', SITECORE_PLUGIN_DOMAIN ) )
			->addTrueFalse( 'accessibility', array(
				'default_value' => true
			) )
			->setInstructions( __( 'Enables tabbing and arrow key navigation', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'adaptiveHeight', array(
				'default_value' => false
			) )
			->setInstructions( __( 'Enables adaptive height for single slide horizontal carousels.', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'autoplay', array(
				'default_value' => false
			) )
			->setInstructions( __( 'Enables Autoplay', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'autoplaySpeed', array(
				'default_value' => 3000
			) )
			->setInstructions( __( 'Autoplay Speed in milliseconds
', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'arrows', array(
				'default_value' => true
			) )
			->setInstructions( __( 'Prev/Next Arrows', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'asNavFor' )
			->setInstructions( __( 'Set the slider to be the navigation of other slider (Class or ID Name)', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'appendArrows' )
			->setInstructions( __( 'Change where the navigation arrows are attached (Selector, htmlString, Array, Element, jQuery object)', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'appendDots' )
			->setInstructions( __( 'Change where the navigation dots are attached (Selector, htmlString, Array, Element, jQuery object)', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'prevArrow' )
			->setInstructions( __( 'Allows you to select a node or customize the HTML for the "Previous" arrow.', SITECORE_PLUGIN_DOMAIN ) )
			->addText( 'nextArrow' )
			->setInstructions( __( 'Allows you to select a node or customize the HTML for the "Next" arrow.', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'centerMode', array(
				'default_value' => false
			) )
			->setInstructions( __( 'Enables centered view with partial prev/next slides. Use with odd numbered slidesToShow counts.', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'centerPadding', array(
				'default_value' => '50px'
			) )
			->setInstructions( __( 'Side padding when in center mode (px or %)', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'cssEase', array(
				'default_value' => 'ease'
			) )
			->setInstructions( __( 'CSS3 Animation Easing', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'customPaging' )
			->setInstructions( __( 'Custom paging templates..', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'dots' )
			->setInstructions( __( 'Show dot indicators', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'dotsClass', array(
				'default_value' => 'slick-dots'
			) )
			->setInstructions( __( 'Class for slide indicator dots container', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'fade', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Enable fade', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'focusOnSelect', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Enable focus on selected element (click)', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'easing', array(
				'default_value' => 'linear',
			) )
			->setInstructions( __( 'Add easing for jQuery animate. Use with easing libraries or default easing methods', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'edgeFriction', array(
				'default_value' => '0.15',
			) )
			->setInstructions( __( 'Resistance when swiping edges of non-infinite carousels', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'infinite', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Infinite loop sliding', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'initialSlide' )
			->setInstructions( __( 'Slide to start on', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'lazyLoad', array(
				'default_value' => 'ondemand',
			) )
			->setInstructions( __( 'Set lazy loading technique. Accepts \'ondemand\' or \'progressive\'', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'mobileFirst', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Responsive settings use mobile first calculation', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'pauseOnFocus', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Pause Autoplay On Focus', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'pauseOnHover', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Pause Autoplay On Hover', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'pauseOnDotsHover', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Pause Autoplay when a dot is hovered', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'respondTo', array(
				'default_value' => 'window',
			) )
			->setInstructions( __( 'Width that responsive object responds to. Can be \'window\', \'slider\' or \'min\' (the smaller of the two)', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'responsive' )
			->setInstructions( __( 'Object containing breakpoints and settings objects (see demo). Enables settings sets at given screen width. Set settings to "unslick" instead of an object to disable slick at a given breakpoint.', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'rows', array(
				'default_value' => 1,
			) )
			->setInstructions( __( 'Setting this to more than 1 initializes grid mode. Use slidesPerRow to set how many slides should be in each row.', SITECORE_PLUGIN_DOMAIN ) )

			->addText( 'slide' )
			->setInstructions( __( 'Element query to use as slide', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'slidesPerRow', array(
				'default_value' => 1,
			) )
			->setInstructions( __( 'With grid mode intialized via the rows option, this sets how many slides are in each grid row. dver', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'slidesToShow', array(
				'default_value' => 1,
			) )
			->setInstructions( __( '# of slides to show', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'slidesToScroll', array(
				'default_value' => 1,
			) )
			->setInstructions( __( '# of slides to scroll', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'speed', array(
				'default_value' => 300,
			) )
			->setInstructions( __( 'Slide/Fade animation speed (in ms)', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'swipe', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Enable swiping', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'swipeToSlide', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Allow users to drag or swipe directly to a slide irrespective of slidesToScroll', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'touchMove', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Enable slide motion with touch', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'touchThreshold', array(
				'default_value' => 5,
			) )
			->setInstructions( __( 'To advance slides, the user must swipe a length of (1/touchThreshold) * the width of the slider', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'useCSS', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Enable/Disable CSS Transitions', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'useTransform', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Enable/Disable CSS Transforms', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'variableWidth', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Variable width slides', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'vertical', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Vertical slide mode', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'verticalSwiping', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Vertical swipe mode', SITECORE_PLUGIN_DOMAIN ) )

			->addTrueFalse( 'rtl', array(
				'default_value' => false,
			) )
			->setInstructions( __( 'Change the slider\'s direction to become right-to-left', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'waitForAnimate', array(
				'default_value' => true,
			) )
			->setInstructions( __( 'Ignores requests to advance the slide while animating', SITECORE_PLUGIN_DOMAIN ) )

			->addNumber( 'zIndex', array(
				'default_value' => 1000,
			) )
			->setInstructions( __( 'Set the zIndex values for slides, useful for IE9 and lower', SITECORE_PLUGIN_DOMAIN ) )



//			->addTrueFalse( 'mobileFirst' )
//			->setInstructions( __( '', SITECORE_PLUGIN_DOMAIN ) )


		;

		return $content;
	}

	public function get_slides() {
		$content = new FieldsBuilder(
			'slides',
			array(
				'button_label' => __( 'Add Slide', SITECORE_PLUGIN_DOMAIN ),
			)
		);
		$content
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addFlexibleContent( 'slides', array( 'min' => 2, 'max' => 10 ) )
			// Images
			->addLayout( 'image_content' )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addImage( 'image' )
			->addSelect( 'size', array(
				'default_value' => 'thumbnail',
			) )
			->addChoices( ym_core_get_image_sizes() )
			->addFields( $this->get_attributes() )

//				// Video
//				->addLayout( 'video_content' )
//				->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
//
			->addOembed( 'video' )
//				->addFields( $this->get_attributes() )

			// Content
			->addLayout( 'custom_content' )
			->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
			->addWysiwyg( 'content' )
			->addFields( $this->get_attributes() )

//				// Columned Content
//				->addLayout( 'columned_content' )
//				->addTab( __( 'Content', SITECORE_PLUGIN_DOMAIN ) )
//				->addFields( $this->get_content() )
//				->addFields( $this->get_attributes() )
		;
		return $content;
	}

	public function get_cta() {
		$args = array(
			'wrapper' => ym_core_get_wrapper( 25 ),
		);

		$cta = new FieldsBuilder( 'cta' );
		$cta
			->addFlexibleContent( 'type', array(
				'label'        => __( 'Call to Action', SITECORE_PLUGIN_DOMAIN ),
				'button_label' => __( 'Add CTA', SITECORE_PLUGIN_DOMAIN ),
			) )
			->addLayout( 'external' )
			->addText( 'text', $args )
			->addSelect( 'size', $args )
			->addChoices( ym_core_get_sizes() )
			->addSelect( 'color', $args )
			->addChoices( ym_core_get_theme_colors() )
			->addUrl( 'link', $args )
			->addLayout( 'internal' )
			->addText( 'text', $args )
			->addSelect( 'size', $args )
			->addChoices( ym_core_get_sizes() )
			->addSelect( 'color', $args )
			->addChoices( ym_core_get_theme_colors() )
			->addPageLink( 'link', array_merge( $args, array(
				'post_type' => array(
					'page',
					'post',
					'product',
				),
			) ) )
			->addLayout( 'text' )
			->addText( 'text', array(
				'wrapper' => array(
					'width' => 50,
					'class' => '',
					'id'    => '',
				),
			) )
			->addUrl( 'link', array(
				'wrapper' => array(
					'width' => 50,
					'class' => '',
					'id'    => '',
				),
			) );

		return $cta;
	}

	/**
	 * Add 'ym-blocks' shortcode
	 *
	 */
	public function add_shortcodes() {
		add_shortcode( 'ym-blocks', array( $this, 'shortcode' ) );
	}

	/**
	 * Build the shortcode, call templates
	 */
	public function shortcode() {
		ob_start();

		do_action( 'ym_before_blocks' );
		ym_template( 'contentblocks' );
		do_action( 'ym_after_blocks' );

		return ob_get_clean();
	}

	public function add_to_content( $content ) {
		if ( in_the_loop() ) {
			// Only edit the_content() if blocks have been added to this $post
			if ( have_rows( 'blocks' ) ) {
				$content_before = ( ! empty( $content ) ) ? apply_filters( 'ym_content_before', '' ) : '';
				$content_after  = ( ! empty( $content ) ) ? apply_filters( 'ym_content_after', '' ) : '';

				$content = $content_before . $content . $content_after . '[ym-blocks]';

				return $content;
			} else {
				// If no blocks are present, return the content unmolested
				return $content;
			}
		}
	}

}
