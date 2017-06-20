<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

class Site_Core_ACF extends WPS_Singleton {

	public function __construct() {
		add_action( 'acf/init', array( $this, 'options_page' ) );
	}

	public function options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {

			acf_add_options_page( array(
				'page_title' => __( 'Site Settings', SITECORE_PLUGIN_DOMAIN ),
				'menu_title' => __( 'Site Settings', SITECORE_PLUGIN_DOMAIN ),
				'menu_slug'  => 'ym-settings',
				'capability' => 'edit_posts',
				'redirect'   => false
			) );

		}

		$company = new FieldsBuilder( 'company', array(
			'title' => __( 'Company Settings', SITECORE_PLUGIN_DOMAIN ),
		) );
		$company
			->addText( 'phone', array(
				'required'      => 1,
				'placeholder'   => '1-800-827-0046',
				'default_value' => '1-800-827-0046',
			) )
			->addEmail( 'primary', array(
				'required'      => 1,
				'placeholder'   => 'connectwithus@yourmembership.com',
				'default_value' => 'connectwithus@yourmembership.com',
			) )
			->addImage( 'logo', array(
				'default_value' => 89,
			) )
			->setLocation( 'options_page', '==', 'ym-settings' );


		$social = new FieldsBuilder( 'social', array(
			'title' => __( 'Social Settings', SITECORE_PLUGIN_DOMAIN ),
		) );

		$accounts = array();

		$social
			->addRepeater(
				'social_accounts',
				array(
					'layout'       => 'table',
					'button_label' => __( 'Add Account', SITECORE_PLUGIN_DOMAIN ),
				)
			)
				->addText( 'account_name' )
				->addUrl( 'account_url' )
				->addText( 'account_icon' )
				->addColorPicker( 'account_color' )
			->endRepeater()
			->addMessage(
				__( 'Instructions', SITECORE_PLUGIN_DOMAIN ),
				__( 'See <a href="http://designpieces.com/2012/12/social-media-colours-hex-and-rgb/">This reference</a> for official social media account colors.', SITECORE_PLUGIN_DOMAIN )
			)
			->setLocation( 'options_page', '==', 'ym-settings' );


		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group( $company->build() );
			acf_add_local_field_group( $social->build() );
		}
	}

}
