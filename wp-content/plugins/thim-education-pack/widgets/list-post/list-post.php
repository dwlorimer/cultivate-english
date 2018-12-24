<?php

class Thim_List_Post_Widget extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'list-post',
			esc_attr__( 'Thim: Display Posts', 'education-pack' ),
			array(
				'description'   => esc_attr__( 'Show Post', 'education-pack' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),

			),
			array(),
			array(
				'cat_id' => array(
					'type' 		=> 'select',
					'label'		=> esc_attr__('Select Categories', 'education-pack'),
					'default'	=> 'none',
					'options'	=> $this->get_categories()
				),
				'image_size'         => array(
					'type'    => 'text',
					'label'   => esc_attr__( 'Image size', 'education-pack' ),
					'description'    => esc_attr__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'education-pack' )
				),
				'show_meta' =>array(
					'type'		=> 'radio',
					'label'		=> esc_attr__('Show Meta', 'education-pack'),
					'default'	=> 'yes',
					'options'	=> array(
						'no' => esc_attr__('No', 'education-pack'),
						'yes' => esc_attr__('Yes', 'education-pack'),
					)
				),
				'number_posts' => array(
					'type'    => 'number',
					'label'   => esc_attr__( 'Number Post', 'education-pack' ),
					'default' => '3'
				),
				'columns'      => array(
					'type'    => 'select',
					'label'   => esc_attr__( 'Columns', 'education-pack' ),
					'default' => '3',
					'options' => array(
						'1' 	=> esc_attr__( '1', 'education-pack' ),
						'2'  	=> esc_attr__( '2', 'education-pack' ),
						'3'  	=> esc_attr__( '3', 'education-pack' ),
						'4'  	=> esc_attr__( '4', 'education-pack' ),
						'6'  	=> esc_attr__( '6', 'education-pack' ),
					),
				),
				'orderby'      => array(
					'type'		=> 'select',
					'label'		=> 	esc_html__( 'Order by', 'education-pack' ),
					'options' => array(
						'popular' => esc_attr__( 'Popular', 'education-pack' ),
						'recent'  => esc_attr__( 'Recent', 'education-pack' ),
						'title'   => esc_attr__( 'Title', 'education-pack' ),
						'random'  => esc_attr__( 'Random', 'education-pack' ),
					),
				),
				'order'        => array(
					'type'    => 'select',
					'label'   => esc_attr__( 'Order', 'education-pack' ),
					'options' => array(
						'asc'  => esc_attr__( 'ASC', 'education-pack' ),
						'desc' => esc_attr__( 'DESC', 'education-pack' )
					),
				),
				'link'        => array(
					'type'    => 'text',
					'label'   => esc_attr__( 'Link All Post', 'education-pack' ),
 				),
				'text_link'        => array(
					'type'    => 'text',
					'label'   => esc_attr__( 'Text Link', 'education-pack' ),
 				),
 				'style'      => array(
					'type'    => 'select',
					'label'   => esc_attr__( 'Style', 'education-pack' ),
					'options' => array(
						'style-1' 	=> esc_attr__( 'Style 1', 'education-pack' ),
						'style-2'  	=> esc_attr__( 'Style 2', 'education-pack' ),
						'style-3'  	=> esc_attr__( 'Style 3', 'education-pack' ),
					),
				),
			)
		);
	}

	// Get list category
    function get_categories(){
    	$args = array(
		  'orderby' 	=> 'id',
		  'parent' 		=> 0
		 );
		$items = array();
		$items['all'] = 'All';
		$categories = get_categories( $args );
		if (isset($categories)) {
			foreach ($categories as $key => $cat) {
				$items[$cat -> cat_ID] = $cat -> cat_name;
				$childrens = get_term_children($cat->term_id, $cat->taxonomy);
				if ($childrens){
					foreach ($childrens as $key => $children) {
						$child = get_term_by( 'id', $children, $cat->taxonomy);
						$items[$child->term_id] = '--'.$child->name;

					}
				}
			}
		}
		return $items;
    }
}
siteorigin_widget_register( "list-post", __FILE__, "Thim_List_Post_Widget" );