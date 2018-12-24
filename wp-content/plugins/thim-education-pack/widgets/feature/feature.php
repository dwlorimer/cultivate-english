<?php
/*
Widget Name: Thim List Link
Description: Add list link.
Author: ThimPress
Author URI: https://thimpress.com
*/

 class Thim_Feature_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'thim-feature',
			esc_html__( 'Thim: Feature', 'education-pack' ),
			array(
				'description' => esc_html__( 'Add Feature html', 'education-pack' ),
				'help'        => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(
				'title'   => array(
					"type"    => "text",
					"label"   => esc_html__( "Title", "education-pack" ),
					"default" => "Title",
				),
				'content' => array(
					"type"  => "textarea",
					"label" => esc_html__( "Content", "education-pack" ),
					'allow_html_formatting' => true
				),
			)
		);
	}
}
siteorigin_widget_register( "thim-feature", __FILE__, "Thim_Feature_Widget" );