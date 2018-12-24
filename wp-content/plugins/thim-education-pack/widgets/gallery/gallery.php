<?php

/**
 * Created by PhpStorm.
 * User: Trung TV
 * Date: 20/2/2017
 */
class Thim_Gallery_Widget extends WP_Widget {

	// Constructor
	function __construct() {
		parent::__construct(
			'thim_gallery',
			esc_html__( 'Thim: Gallery', 'education-pack' ),
			array(
				'description'   => esc_html__( 'Display gallery', 'education-pack' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
			)
		);
	}

	public function form( $instance ) {

		//Check values
		if ( $instance && $instance['number_limit'] && $instance['number_limit'] != '' ) {
			$title_widget   = $instance['title_widget'];
			$number_columns = $instance['number_columns'];
			$number_limit   = $instance['number_limit'];
			$image_size     = $instance['image_size'];
			$display_layout = $instance['display_layout'];

			if ( $display_layout != '' ) {
				$checked_display_layout = 'checked="checked"';
			} else {
				$checked_display_layout = '';
			}
		} else {
			//Default value
			$title_widget           = 'Gallery';
			$number_columns         = 3;
			$number_limit           = 9;
			$image_size             = '';
			$checked_display_layout = '';
		}

		?>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title_widget' ) ); ?>"><?php esc_html_e( 'Title:', 'education-pack' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title_widget' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title_widget' ) ); ?>"
			       value="<?php echo esc_attr( $title_widget ); ?>">
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'number_columns' ) ); ?>"><?php esc_html_e( 'Columns:', 'education-pack' ); ?></label>
			<input class="widefat" type="number" id="<?php echo esc_attr( $this->get_field_id( 'number_columns' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'number_columns' ) ); ?>"
			       value="<?php echo esc_attr( $number_columns ); ?>">
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'number_limit' ) ); ?>"><?php esc_html_e( 'Number limit:', 'education-pack' ); ?></label>
			<input class="widefat" type="number" id="<?php echo esc_attr( $this->get_field_id( 'number_limit' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'number_limit' ) ); ?>"
			       value="<?php echo esc_attr( $number_limit ); ?>">
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>"><?php esc_html_e( 'Image Size (full, large, medium, thumbnail, 80x80 )', 'education-pack' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'image_size' ) ); ?>"
			       value="<?php echo esc_attr( $image_size ); ?>">
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'display_layout' ) ); ?>"><?php esc_html_e( 'Display Style 2:', 'education-pack' ); ?></label>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'display_layout' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'display_layout' ) ); ?>"
				<?php echo esc_attr( $checked_display_layout ); ?>>
		</p>
		<?php
	}

	// Update widget
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// Fields
		$instance['number_columns'] = strip_tags( $new_instance['number_columns'] );
		$instance['number_limit']   = strip_tags( $new_instance['number_limit'] );
		$instance['image_size']     = strip_tags( $new_instance['image_size'] );
		$instance['title_widget']   = strip_tags( $new_instance['title_widget'] );
		$instance['display_layout'] = strip_tags( $new_instance['display_layout'] );

		return $instance;
	}

	// Display widget
	public function widget( $args, $instance ) {
		echo ent2ncr( $args['before_widget'] );
		if ( ! empty ( $instance['title_widget'] ) ) {
			echo ent2ncr( $args['before_title'] . $instance['title_widget'] . $args['after_title'] );
		}
		if ( isset( $instance['display_layout'] ) && $instance['display_layout'] == 'on' ) {
			echo '<div class="style-2">';
		}
		//Query
		$args_query = array(
			'post_type' => 'post',
			'tax_query' => array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => array( 'post-format-gallery' ),
				),
			),
		);

		// Get grid
		$number_columns = isset( $instance['number_columns'] ) ? $instance['number_columns'] : 3;
		$cell           = floor( 12 / $number_columns );
		$grid_class     = 'col-sm-' . $cell . '';

		// Get image size
		$image_size = isset( $instance['image_size'] ) ? $instance['image_size'] : 'thumbnail';
		if ( $image_size ) {
			if ( in_array( $image_size, array( 'medium', 'large', 'full' ) ) ) {
				$image_size = $image_size;
			}
			preg_match_all( '/\d+/', $image_size, $size_matches );
			if ( $size_matches[0] ) {
				if ( isset ( $size_matches[0][0] ) && isset ( $size_matches[0][1] ) ) {
					$image_size = array( $size_matches[0][0], $size_matches[0][1] );
				} else {
					$image_size = array( 80, 80 );
				}
			}
		}
		if ( is_array( $image_size ) ) {
			$resize = $image_size;
			if ( $image_size[0] <= get_option( 'thumbnail_size_w' ) ) {
				$image_size = 'thumbnail';
			} elseif ( $image_size[0] <= get_option( 'medium_size_w' ) ) {
				$image_size = 'medium';
			} elseif ( $image_size[0] <= get_option( 'large_size_w' ) ) {
				$image_size = 'large';
			} else {
				$image_size = 'full';
			}
		} else {
			$resize = 0;
		}

		$query      = new WP_Query( $args_query );
		$image_html = '';
		$limit      = 1;

		$number_limit = isset( $instance['number_limit'] ) ? $instance['number_limit'] : 9;
		while ( $query->have_posts() ) {
			$query->the_post();
			$images           = thim_meta( 'thim_gallery', array(
				'type'   => 'image',
				'single' => 'false',
				'size'   => $image_size
			) );
			$full_size_images = thim_meta( 'thim_gallery', array(
				'type'   => 'image',
				'single' => 'false',
				'size'   => 'full'
			) );
			for ( $i = 0; $i < count( $images ); $i ++ ) {
				if ( $limit > $number_limit ) {
					break;
				}
				if ( ! empty ( $images[ $i ]['url'] ) ) {
					$image_html .= '<div class="' . $grid_class . ' item">';
					if ( $resize ) {
						$image_crop = education_pack_aq_resize( $full_size_images[ $i ]['url'], $resize[0], $resize[1], 1 );
						$image_html .= '<a class="fancybox" data-fancybox-group="gallery" data-filter="filter-gallery-' . get_the_ID() . '" href="' . esc_url( $full_size_images[ $i ]['url'] ) . '"><img src="' . esc_url( $image_crop ) . '" alt= "' . esc_attr( get_the_title() ) . '" title = "' . esc_attr( get_the_title() ) . '" /></a>';
					} else {
						$image_html .= '<a class="' . $grid_class . ' fancybox" data-fancybox-group="gallery" data-filter="filter-gallery-' . get_the_ID() . '" href="' . esc_url( $full_size_images[ $i ]['url'] ) . '"><img src="' . esc_url( $images[ $i ]['url'] ) . '" alt= "' . esc_attr( get_the_title() ) . '" title = "' . esc_attr( get_the_title() ) . '" /></a>';
					};
					$image_html .= '</div>';
					$limit ++;
				}
			}
		}
		echo '<div class="wrapper-gallery-filter row" itemscope itemtype = "http://schema.org/ItemList">' . $image_html . '</div>';

		wp_reset_postdata();

		if ( isset( $instance['display_layout'] ) && $instance['display_layout'] == 'on' ) {
			echo '</div>';
		}
		echo ent2ncr( $args['after_widget'] );
	}
}

// Register and load the widget
function thim_register_widget_gallery() {
	register_widget( 'Thim_Gallery_Widget' );
}

add_action( 'widgets_init', 'thim_register_widget_gallery' );