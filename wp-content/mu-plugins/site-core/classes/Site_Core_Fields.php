<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

class Site_Core_Fields extends WPS_Singleton {

	public $builder;

	protected function __construct() {
		$this->builder = new FieldsBuilder( 'site_fields' );

		if ( did_action( 'init' ) || doing_action( 'init' ) ) {
			$this->create();
		} else {
			add_action( 'init', array( $this, 'create' ) );
		}
	}

	public function create() {
		$this->builder
			->addFields( $this->get_video() )
			->addFields( $this->get_background() )
			->setLocation( 'post_type', '==', 'page' )
			->or( 'post_type', '==', 'post' );

		if ( did_action( 'acf/init' ) || doing_action( 'acf/init' ) ) {
			$this->init_fields();
		} else {
			add_action( 'acf/init', array( $this, 'init_fields' ) );
		}
	}

	public function init_fields() {
		wps_write_log( $this->builder->build() );
		acf_add_local_field_group( $this->builder->build() );
	}

	public function get_background() {
		$background = new FieldsBuilder( 'background' );
		$background
			->addColorPicker( 'bg_color', array(
				'allow_null' => 1,
			) )
			->setInstructions( __( 'Optionally, set the background of the card.', SITECORE_PLUGIN_DOMAIN ) );

		return $background;
	}

	public function get_video() {
		$content = new FieldsBuilder( 'content' );
		$content
			->addWysiwyg( 'content' )
			->addOembed( 'video_oembed' );

		return $content;
	}

}
