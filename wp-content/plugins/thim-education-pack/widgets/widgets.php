<?php

if ( ! class_exists( 'Thim_Widget_Manager' ) ) {

	class Thim_Widget_Manager {
		/**
		 * Initialize plugin
		 */
		function __construct() {
			add_filter( 'siteorigin_panels_widget_dialog_tabs', array( $this, 'widget_group' ), 19 );
			add_filter( 'siteorigin_panels_row_style_fields', array( $this, 'row_style_fields' ) );
			add_filter( 'siteorigin_panels_row_style_attributes', array( $this, 'row_style_attributes' ), 10, 2 );
			add_action( 'after_setup_theme', array( $this, 'load_widgets' ), 30 );
		}


		public static function load_widgets() {
			if ( class_exists( 'SiteOrigin_Widget' ) ) {
				include_once( THIM_EP_PATH . 'widgets/slider/slider.php' );
				include_once( THIM_EP_PATH . 'widgets/list-link/list-link.php' );
				include_once( THIM_EP_PATH . 'widgets/list-post/list-post.php' );
				include_once( THIM_EP_PATH . 'widgets/icon-box/icon-box.php' );
				include_once( THIM_EP_PATH . 'widgets/single-image/single-image.php' );
				include_once( THIM_EP_PATH . 'widgets/feature/feature.php' );
				include_once( THIM_EP_PATH . 'widgets/testimonials/testimonials.php' );
				include_once( THIM_EP_PATH . 'widgets/brands/brands.php' );
				include_once( THIM_EP_PATH . 'widgets/google-map/google-map.php' );
			}

			include_once( THIM_EP_PATH . 'widgets/recent-posts/recent-posts.php' );
			include_once( THIM_EP_PATH . 'widgets/featured-posts/featured-posts.php' );
			include_once( THIM_EP_PATH . 'widgets/social-link/social-link.php' );
			include_once( THIM_EP_PATH . 'widgets/gallery/gallery.php' );
			include_once( THIM_EP_PATH . 'widgets/heading/heading.php' );

		}


		//pannel Widget Group
		public static function widget_group( $tabs ) {
			$tabs[] = array(
				'title'  => esc_html__( 'Education Pack Widgets', 'education-pack' ),
				'filter' => array(
					'groups' => array( 'thim_widget_group' )
				)
			);

			return $tabs;
		}


		public static function row_style_fields( $fields ) {
			$fields['parallax'] = array(
				'name'        => esc_html__( 'Parallax', 'education-pack' ),
				'type'        => 'checkbox',
				'group'       => 'design',
				'description' => esc_html__( 'If enabled, the background image will have a parallax effect.', 'education-pack' ),
				'priority'    => 8,
			);

			return $fields;
		}


		public static function row_style_attributes( $attributes, $args ) {
			if ( ! empty( $args['parallax'] ) ) {
				array_push( $attributes['class'], 'article__parallax' );
			}

			if ( ! empty( $args['row_stretch'] ) && $args['row_stretch'] == 'full-stretched' ) {
				array_push( $attributes['class'], 'thim-fix-stretched' );
			}

			return $attributes;
		}
	}

	new Thim_Widget_Manager();
}