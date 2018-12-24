<?php
/*
Widget Name: Thim List Link
Description: Add list link.
Author: ThimPress
Author URI: https://thimpress.com
*/

 class List_Link_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'list-link',
			esc_attr__( 'Thim: List Link', 'education-pack' ),
			array(
				'description' => esc_attr__( 'Add link', 'education-pack' ),
				'help'        => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(
				'list-link' => array(
					'type'      => 'repeater',
					'label'     => esc_attr__( 'Text', 'education-pack' ),
					'item_name' => esc_attr__( 'Text', 'education-pack' ),
					'fields'    => array(
						'title'   => array(
							"type"    => "text",
							"label"   => esc_attr__( "Title", "education-pack" ),
							"default" => "",
						),
						'link' => array(
							"type"  => "text",
							"label" => esc_attr__( "Link", "education-pack" ),
							"default" => "",
						),
					),
				),
				'style'      => array(
					'type'    => 'select',
					'label'   => esc_attr__( 'Style', 'education-pack' ),
					"default" => "style-1",
					'options' => array(
						'style-1' 	=> esc_attr__( 'Style 1', 'education-pack' ),
						'style-2'  	=> esc_attr__( 'Style 2', 'education-pack' ),
					),
				),
			)
		);
	}
}
siteorigin_widget_register( "list-link", __FILE__, "List_Link_Widget" );