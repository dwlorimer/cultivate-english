<?php
/*
Widget Name: Thim List Link
Description: Add list link.
Author: ThimPress
Author URI: https://thimpress.com
*/

 class Thim_Icon_Box_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'thim-icon-box',
			esc_attr__( 'Thim: Icon Box', 'education-pack' ),
			array(
				'description' => esc_attr__( 'Add icon box', 'education-pack' ),
				'help'        => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(
				'icon'   => array(
					'type'    => 'icon',
					'label'   => esc_attr__( 'Icon', 'education-pack' ),
				),
				'title'   => array(
					'type'    => 'text',
					'label'   => esc_attr__( 'Title', 'education-pack' ),
					"default" => "",
				),

				'description'   => array(
					'type'    => 'textarea',
					'label'   => esc_attr__( 'Description', 'education-pack' ),
					"default" => "",
				),
			)
		);
	}
}
siteorigin_widget_register( "thim-icon-box", __FILE__, "Thim_Icon_Box_Widget" );