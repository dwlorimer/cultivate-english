<?php

/*
Widget Name: Thim Testimonials
Description: Add testimonials.
Author: ThimPress
Author URI: https://thimpress.com
*/

class Thim_Testimonials_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'thim-testimonials',
			esc_attr__( 'Thim: Testimonials', 'education-pack' ),
			array(
				'description'   => esc_attr__( 'Add testimonials', 'education-pack' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(
				'thim-testimonials' => array(
					'type'      => 'repeater',
					'label'     => esc_attr__( 'Testimonials', 'education-pack' ),
					'item_name' => esc_attr__( 'Item', 'education-pack' ),
					'fields'    => array(
						'image'       => array(
							'type'        => 'media',
							'label'       => esc_attr__( 'Image', 'education-pack' ),
							'description' => esc_attr__( 'Select image from media library.', 'education-pack' )
						),
						'name'        => array(
							"type"    => "text",
							"label"   => esc_attr__( "Name", "education-pack" ),
							"default" => "",
						),
						'jobs'        => array(
							"type"    => "text",
							"label"   => esc_attr__( "Jobs", "education-pack" ),
							"default" => "",
						),
						'description' => array(
							"type"    => "textarea",
							"label"   => esc_attr__( "Description", "education-pack" ),
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
					'thim-testimonials',
					THIM_EP_URL . 'widgets/testimonials/js/testimonials.js',
					array( 'jquery' ),
					'',
					true
				),

			)
		);
	}
}

siteorigin_widget_register( "thim-testimonials", __FILE__, "Thim_Testimonials_Widget" );