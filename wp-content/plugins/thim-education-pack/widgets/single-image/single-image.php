<?php
/*
Widget Name: Thim List Link
Description: Add list link.
Author: ThimPress
Author URI: https://thimpress.com
*/

 class Thim_Single_Images_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'single-images',
			esc_attr__( 'Thim: Single Images', 'education-pack' ),
			array(
				'description' => esc_attr__( 'Add heading text', 'education-pack' ),
				'help'        => '',
				'panels_groups' => array('thim_widget_group')
			),
			array(),
			array(
				'image' => array(
					'type'  => 'media',
					'label' => esc_attr__( 'Image', 'education-pack' ),
					'description'  => esc_attr__( 'Select image from media library.', 'education-pack' )
				),

				'image_size'         => array(
					'type'    => 'text',
					'label'   => esc_attr__( 'Image size', 'education-pack' ),
					'description'    => esc_attr__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'education-pack' )
				),
				'image_link'         => array(
					'type'    => 'text',
					'label'   => esc_attr__( 'Image Link', 'education-pack' ),
					'description'    => esc_attr__( 'Enter URL if you want this image to have a link.', 'education-pack' )
				),
				'link_target'       => array(
					'type'    	=> 'select',
					'label'   	=> esc_attr__( "Link Target", 'education-pack' ),
					'options' 	=> array(
						'_self'              => esc_attr__( 'Same window', 'education-pack' ),
						'_blank' => esc_attr__( 'New window', 'education-pack' ),
					),
				),
				'image_alignment'       => array(
					'type'    		=> 'select',
					'label'   		=> esc_attr__( 'Image alignment', 'education-pack' ),
					'description'	=>	'Select image alignment.',
					'options' 		=> array(
						'left'      => esc_attr__( 'Align Left', 'education-pack' ),
						'right' 	=> esc_attr__( 'Align Right', 'education-pack' ),
						'center' 	=> esc_attr__( 'Align Center', 'education-pack' )
					),
				),
			)
		);
	}
}
siteorigin_widget_register( "single-images", __FILE__, "Thim_Single_Images_Widget" );