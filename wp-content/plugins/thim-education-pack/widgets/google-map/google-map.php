<?php

/*
Widget Name: Thim Google Map
Description: Add google map.
Author: ThimPress
Author URI: https://thimpress.com
*/

class Thim_Google_Map_Widget extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'google-map',
			esc_attr__( 'Thim: Google Maps', 'education-pack' ),
			array(
				'description'   => esc_attr__( 'A Google Maps widget.', 'education-pack' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' )
			),
			array(),
			array(
				'title'      => array(
					'type'  => 'text',
					'label' => esc_attr__( 'Title', 'education-pack' ),
				),
				'display_by' => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Get Map By', 'education-pack' ),
					'options'       => array(
						'address'  => esc_html__( 'Address', 'education-pack' ),
						'location' => esc_html__( 'Coordinates', 'education-pack' ),
					),
					'default'       => 'address',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'display_by' )
					),
				),
				'location'   => array(
					'type'          => 'section',
					'label'         => esc_html__( 'Coordinates', 'education-pack' ),
					'hide'          => true,
					"class"         => "clear-both",
					'state_handler' => array(
						'display_by[address]'  => array( 'hide' ),
						'display_by[location]' => array( 'show' ),
					),
					'fields'        => array(
						'lat' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Lat', 'education-pack' ),
							'default' => '41.868626',
						),
						'lng' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Lng', 'education-pack' ),
							'default' => '-74.104301',
						),
					),
				),

				'map_center' => array(
					'type'          => 'textarea',
					'rows'          => 2,
					'label'         => esc_attr__( 'Map center', 'education-pack' ),
					'description'   => esc_attr__( 'The name of a place, town, city, or even a country. Can be an exact address too.', 'education-pack' ),
					'state_handler' => array(
						'display_by[address]'  => array( 'show' ),
						'display_by[location]' => array( 'hide' ),
					),
				),

				'settings' => array(
					'type'        => 'section',
					'label'       => esc_attr__( 'Settings', 'education-pack' ),
					'hide'        => false,
					'description' => esc_attr__( 'Set map display options.', 'education-pack' ),
					'fields'      => array(
						'height'      => array(
							'type'    => 'text',
							'default' => 480,
							'label'   => esc_attr__( 'Height', 'education-pack' )
						),
						'zoom'        => array(
							'type'        => 'slider',
							'label'       => esc_attr__( 'Zoom level', 'education-pack' ),
							'description' => esc_attr__( 'A value from 0 (the world) to 21 (street level).', 'education-pack' ),
							'min'         => 0,
							'max'         => 21,
							'default'     => 12,
							'integer'     => true,

						),
						'scroll_zoom' => array(
							'type'        => 'checkbox',
							'default'     => true,
							'state_name'  => 'interactive',
							'label'       => esc_attr__( 'Scroll to zoom', 'education-pack' ),
							'description' => esc_attr__( 'Allow scrolling over the map to zoom in or out.', 'education-pack' )
						),
						'draggable'   => array(
							'type'        => 'checkbox',
							'default'     => true,
							'state_name'  => 'interactive',
							'label'       => esc_attr__( 'Draggable', 'education-pack' ),
							'description' => esc_attr__( 'Allow dragging the map to move it around.', 'education-pack' )
						)
					)
				),
				'markers'  => array(
					'type'        => 'section',
					'label'       => esc_attr__( 'Markers', 'education-pack' ),
					'hide'        => true,
					'description' => esc_attr__( 'Use markers to identify points of interest on the map.', 'education-pack' ),
					'fields'      => array(
						'marker_at_center' => array(
							'type'    => 'checkbox',
							'default' => true,
							'label'   => esc_attr__( 'Show marker at map center', 'education-pack' )
						),
						'marker_icon'      => array(
							'type'        => 'media',
							'default'     => '',
							'label'       => esc_attr__( 'Marker Icon', 'education-pack' ),
							'description' => esc_attr__( 'Replaces the default map marker with your own image.', 'education-pack' )
						),
						'marker_positions' => array(
							'type'      => 'repeater',
							'label'     => esc_attr__( 'Marker positions', 'education-pack' ),
							'item_name' => esc_attr__( 'Marker', 'education-pack' ),
							'fields'    => array(
								'place' => array(
									'type'  => 'textarea',
									'rows'  => 2,
									'label' => esc_attr__( 'Place', 'education-pack' )
								)
							)
						)
					)
				),
				'map_api'  => array(
					'type'        => 'text',
					'label'       => esc_attr__( 'Google map API', 'education-pack' ),
					'description' => esc_attr__( 'Enter API google map of you or create new API: https://developers.google.com/maps/documentation/javascript/get-api-key', 'education-pack' ),
					'default'     => 'AIzaSyARtFR6zbpjGbGNqOSu-MknQYETXvS2cBU',
				),
			)
		);
	}

	function initialize() {
		$this->register_frontend_scripts(
			array(
				array(
					'thim-google-map',
					THIM_EP_URL . 'widgets/google-map/js/js-google-map.js',
					array( 'jquery' ),
					'',
					true
				),

			)
		);
	}


	function get_template_variables( $instance, $args ) {
		$settings = $instance['settings'];
		$markers  = $instance['markers'];
		$mrkr_src = wp_get_attachment_image_src( $instance['markers']['marker_icon'] );
		{
			return array(
				'map_id'   => md5( $instance['map_center'] ),
				'height'   => $settings['height'],
				'map_data' => array(
					'display_by'       => ( isset( $instance['display_by'] ) && $instance['display_by'] != 'address' ) ? $instance['display_by'] : 'address',
					'lat'              => isset( $instance['location']['lat'] ) ? $instance['location']['lat'] : 41.956750,
					'lng'              => isset( $instance['location']['lng'] ) ? $instance['location']['lng'] : - 74.545448,
					'address'          => $instance['map_center'],
					'zoom'             => $settings['zoom'],
					'scroll-zoom'      => $settings['scroll_zoom'],
					'draggable'        => $settings['draggable'],
					'marker-icon'      => ! empty( $mrkr_src ) ? $mrkr_src[0] : '',
					//	'markers-draggable' => isset( $markers['markers_draggable'] ) ? $markers['markers_draggable'] : '',
					'marker-at-center' => $markers['marker_at_center'],
					'marker-positions' => isset( $markers['marker_positions'] ) ? json_encode( $markers['marker_positions'] ) : '',
					'google-map-api'   => isset( $instance['map_api'] ) ? $instance['map_api'] : 'AIzaSyARtFR6zbpjGbGNqOSu-MknQYETXvS2cBU',
				)
			);
		}
	}
}

siteorigin_widget_register( "google-map", __FILE__, "Thim_Google_Map_Widget" );