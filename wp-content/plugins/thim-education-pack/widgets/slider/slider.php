<?php
/*
Widget Name: Thim Slider
Description: Add slider.
Author: ThimPress
Author URI: https://thimpress.com
*/

 class Thim_Slider_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'thim-slider',
			esc_attr__( 'Thim: Slider', 'education-pack' ),
			array(
				'description' => esc_attr__( 'Add slider', 'education-pack' ),
				'help'        => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(
				'thim-slider' => array(
					'type'      => 'repeater',
					'label'     => esc_attr__( 'Slider', 'education-pack' ),
					'item_name' => esc_attr__( 'Item', 'education-pack' ),
					'fields'    => array(
						'image' => array(
							'type'  => 'media',
							'label' => esc_attr__( 'Image', 'education-pack' ),
							'description'  => esc_attr__( 'Select image from media library.', 'education-pack' )
						),
						'title'   => array(
							"type"    => "text",
							"label"   => esc_attr__( "Title", "education-pack" ),
							"default" => "",
						),
						'description'   => array(
							"type"    => "text",
							"label"   => esc_attr__( "Description", "education-pack" ),
							"default" => "",
						),
						'button'   => array(
							"type"    => "text",
							"label"   => esc_attr__( "Button", "education-pack" ),
							"default" => "",
						),
						'link' => array(
							"type"  => "text",
							"label" => esc_attr__( "Link button", "education-pack" ),
							"default" => "",
						),
					),
				),
			)
		);
	}
}
siteorigin_widget_register( "thim-slider", __FILE__, "Thim_Slider_Widget" );