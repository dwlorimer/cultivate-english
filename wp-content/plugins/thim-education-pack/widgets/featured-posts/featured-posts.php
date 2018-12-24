<?php

/**
 * Created by PhpStorm.
 * User: TrungTV
 * Date: 20/2/2017
 */
class Thim_Featured_Posts_Widget extends WP_Widget {

	// Constructor
	function __construct() {
		parent::__construct(
			'thim_featured_posts',
			esc_html__( 'Thim: Featured posts', 'education-pack' ),
			array(
				'description'   => esc_html__( 'Display featured posts', 'education-pack' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
			)
		);
	}

	public function form( $instance ) {

		//Check values
		if ( $instance && $instance['number_posts'] && $instance['number_posts'] != '' ) {
			$number_posts = $instance['number_posts'];
			$title_widget = $instance['title_widget'];
			$display_time = $instance['display_time'];

			if ( $display_time != '' ) {
				$checked_display_time = 'checked="checked"';
			} else {
				$checked_display_time = '';
			}
		} else {
			//Default value
			$number_posts         = 3;
			$title_widget         = 'Featured posts';
			$checked_display_time = 'checked="checked"';
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
				for="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>"><?php esc_html_e( 'Number of recent posts to show:', 'education-pack' ); ?></label>
			<input class="widefat" type="number" id="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'number_posts' ) ); ?>"
			       value="<?php echo esc_attr( $number_posts ); ?>">
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'display_time' ) ); ?>"><?php esc_html_e( 'Display post date:', 'education-pack' ); ?></label>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'display_time' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'display_time' ) ); ?>"
				<?php echo esc_attr( $checked_display_time ); ?>>
		</p>
		<?php
	}

	// Update widget
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// Fields
		$instance['number_posts'] = strip_tags( $new_instance['number_posts'] );
		$instance['title_widget'] = strip_tags( $new_instance['title_widget'] );
		$instance['display_time'] = strip_tags( $new_instance['display_time'] );

		return $instance;
	}

	// Display widget
	public function widget( $args, $instance ) {
		echo ent2ncr( $args['before_widget'] );
		if ( ! empty ( $instance['title_widget'] ) ) {
			echo ent2ncr( $args['before_title'] . $instance['title_widget'] . $args['after_title'] );
		}
		if ( ! empty( $instance['number_posts'] ) ) {
			//Query
			$args_query = array(
				'post_type'      => 'post',
				'posts_per_page' => $instance['number_posts'],
				'order'          => 'DESC',
				'post__in'       => get_option( 'sticky_posts' ),
			);

			echo '<ul>';
			$query = new WP_Query( $args_query );
			while ( $query->have_posts() ) {
				$query->the_post(); ?>
				<?php
				$class_li = 'no-thumbnail';
				if ( has_post_thumbnail() ) {
					$class_li = 'has-thumbnail';
				}
				$images     = get_the_post_thumbnail_url();
				$image_crop = education_pack_aq_resize( $images, 70, 70, 1 );
				?>
				<li class="<?php echo esc_attr( $class_li ); ?>">
					<div class="post_thumbnail">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
							<img src="<?php echo esc_url( $image_crop ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
						</a>
					</div>
					<div class="post_info">
						<h3 class="title">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"
							   title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a>
						</h3>
						<?php if ( $instance['display_time'] && $instance['display_time'] == 'on' ) { ?>
							<div
								class="time"><?php printf( '%s', get_the_date() ); ?></div>
						<?php } ?>
					</div>
				</li>

			<?php }
			echo '</ul>';

			wp_reset_postdata();
		}
		echo ent2ncr( $args['after_widget'] );
	}
}

// Register and load the widget
function thim_register_widget_featured_posts() {
	register_widget( 'Thim_Featured_Posts_Widget' );
}

add_action( 'widgets_init', 'thim_register_widget_featured_posts' );