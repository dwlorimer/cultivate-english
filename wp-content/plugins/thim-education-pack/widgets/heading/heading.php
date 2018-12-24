<?php

/**
 * Created by PhpStorm.
 * User: Trung TV
 * Date: 21/2/2017
 */
class Thim_Heading_Widget extends WP_Widget {

	// Constructor
	function __construct() {

		parent::__construct(
			'thim_heading',
			esc_html__( 'Thim: Heading', 'education-pack' ),
			array(
				'description'   => esc_attr__( 'Display heading', 'education-pack' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
			)
		);
	}

	public function form( $instance ) {

		//Check values
		$instance = wp_parse_args( (array) $instance, array( 'heading_style' => 'left' ) );
		$title    = isset( $instance['title'] ) ? $instance['title'] : '';
		$subtitle = isset( $instance['subtitle'] ) ? $instance['subtitle'] : '';
		?>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'education-pack' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'SubTitle:', 'education-pack' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>"
			       value="<?php echo esc_attr( $subtitle ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'heading_style' ); ?>"><?php esc_html_e( 'Text Align:', 'education-pack' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'heading_style' ); ?>" name="<?php echo $this->get_field_name( 'heading_style' ); ?>">
				<option value="left"<?php selected( $instance['heading_style'], 'left' ); ?>><?php esc_html_e( 'Left', 'education-pack' ); ?></option>
				<option value="center"<?php selected( $instance['heading_style'], 'center' ); ?>><?php esc_html_e( 'Center', 'education-pack' ); ?></option>
				<option value="right"<?php selected( $instance['heading_style'], 'right' ); ?>><?php esc_html_e( 'Right', 'education-pack' ); ?></option>
			</select>
		</p>

		<?php
	}

	// Update widget
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// Fields
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		if ( in_array( $new_instance['heading_style'], array( 'left', 'center', 'right' ) ) ) {
			$instance['heading_style'] = $new_instance['heading_style'];
		} else {
			$instance['heading_style'] = 'left';
		}

		return $instance;
	}

	// Display widget
	public function widget( $args, $instance ) {
		?>
		<div class="thim-heading heading-<?php echo esc_attr( $instance['heading_style'] ); ?>">
			<h3 class="title"><?php echo esc_html( $instance['title'] ); ?></h3>
			<p class="subtitle"><?php echo esc_html( $instance['subtitle'] ); ?></p>
		</div>
		<?php
	}
}

// Register and load the widget
function thim_register_widget_heading() {
	register_widget( 'Thim_Heading_Widget' );
}

add_action( 'widgets_init', 'thim_register_widget_heading' );