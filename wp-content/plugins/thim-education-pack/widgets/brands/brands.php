<?php
/*
Widget Name: Thim Brands
Description: Add brand item.
Author: ThimPress
Author URI: https://thimpress.com
*/

class Thim_Brands_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'thim-brands',
			esc_attr__( 'Thim: Brands', 'education-pack' ),
			array(
				'description' => esc_attr__( 'Add brand item', 'education-pack' ),
				'help'        => '',
				'panels_groups' => array('thim_widget_group')
			),
			array(),
			array(
				'thim-brands' => array(
					'type'      => 'repeater',
					'label'     => esc_attr__( 'Brands', 'education-pack' ),
					'item_name' => esc_attr__( 'Item', 'education-pack' ),
					'fields'    => array(
						'image' => array(
							'type'  => 'media',
							'label' => esc_attr__( 'Image', 'education-pack' ),
							'description'  => esc_attr__( 'Select image from media library.', 'education-pack' )
						),
						'title'   => array(
							"type"    => "text",
							"label"   => esc_attr__( "Title brand", "education-pack" ),
							"default" => "",
						),
						'link' => array(
							"type"  => "text",
							"label" => esc_attr__( "Link brand", "education-pack" ),
							"default" => "",
						),
					),
				),
			)
		);
	}

	function initialize() {
		$this->register_frontend_scripts(
			array(
				array(
					'thim-brands',
					THIM_EP_URL . 'widgets/brands/js/brands.js',
					array( 'jquery' ),
					'',
					true
				),

			)
		);
	}
}
siteorigin_widget_register( "thim-brands", __FILE__, "Thim_Brands_Widget" );