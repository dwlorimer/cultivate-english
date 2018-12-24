<?php
/**
 * All functions for LearnPress Assignment templates.
 *
 * @author  ThimPress
 * @package LearnPress/Assignments/Functions
 * @version 3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'learn_press_content_item_assignment_duration' ) ) {
	/**
	 * Assignment duration.
	 */
	function learn_press_content_item_assignment_duration() {
		$course          = LP_Global::course();
		$user            = learn_press_get_current_user();
		$assignments     = LP_Global::get_custom_posts( 'assignment' );
		$assignment      = current( $assignments );
		$assignment_data = $user->get_item_data( $assignment->get_id(), $course->get_id() );
		$status          = $user->get_item_status( $assignment->get_id(), $course->get_id() );
		$duration        = learn_press_assignment_get_time_remaining( $assignment_data );
		$duration_time = get_post_meta( $assignment->get_id(), '_lp_duration', true );

		if ( in_array( $status, array( 'started', 'doing', 'completed' ) ) ) {
			learn_press_assignment_get_template( 'content-assignment/duration.php', array( 'duration' => $duration, 'duration_time' => $duration_time ) );
		}
	}
}

if ( ! function_exists( 'learn_press_content_item_assignment_title' ) ) {
	/**
	 * Assignment title.
	 */
	function learn_press_content_item_assignment_title() {
		learn_press_assignment_get_template( 'content-assignment/title.php' );
	}
}

if ( ! function_exists( 'learn_press_content_item_assignment_intro' ) ) {
	/**
	 * Assignment introduction.
	 */
	function learn_press_content_item_assignment_intro() {

		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		$status      = $user->get_item_status( $assignment->get_id(), $course->get_id() );

		if ( ! $status || $status == 'viewed' ) {
			learn_press_assignment_get_template( 'content-assignment/intro.php' );
		}
	}
}

if ( ! function_exists( 'learn_press_content_item_assignment_buttons' ) ) {
	/**
	 * Assignment buttons.
	 */
	function learn_press_content_item_assignment_buttons() {
		learn_press_assignment_get_template( 'content-assignment/buttons.php' );
	}
}

if ( ! function_exists( 'learn_press_content_item_assignment_content' ) ) {
	/**
	 * Assignment content.
	 */
	function learn_press_content_item_assignment_content() {
		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		if ( $user->has_course_status( $course->get_id(), array( 'finished' ) ) || $user->has_item_status( array(
				'started',
				'doing',
				'completed',
				'evaluated'
			), $assignment->get_id(), $course->get_id() )
		) {
			learn_press_assignment_get_template( 'content-assignment/content.php' );
		}
	}
}

if ( ! function_exists( 'learn_press_content_item_assignment_attachment' ) ) {
	/**
	 * Assignment attachment.
	 */
	function learn_press_content_item_assignment_attachment() {
		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		if ( $user->has_course_status( $course->get_id(), array( 'finished' ) ) || $user->has_item_status( array(
				'started',
				'doing',
				'completed',
				'evaluated'
			), $assignment->get_id(), $course->get_id() )
		) {
			learn_press_assignment_get_template( 'content-assignment/attachment.php' );
		}

	}
}

if ( ! function_exists( 'learn_press_assignment_start_button' ) ) {
	/**
	 * Start button.
	 */
	function learn_press_assignment_start_button() {
		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		if ( $user->has_course_status( $course->get_id(), array( 'finished' ) ) || $user->has_item_status( array(
				'started',
				'doing',
				'completed',
				'evaluated'
			), $assignment->get_id(), $course->get_id() )
		) {
			return;
		}
		learn_press_assignment_get_template( 'content-assignment/buttons/start.php' );
	}
}


if ( ! function_exists( 'learn_press_assignment_nav_buttons' ) ) {
	/**
	 * Nav button.
	 */
	function learn_press_assignment_nav_buttons() {
		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		if ( ! $user->has_item_status( array(
			'started',
			'doing'
		), $assignment->get_id(), $course->get_id() ) ) {
			return;
		}

		learn_press_assignment_get_template( 'content-assignment/buttons/controls.php' );
	}
}


if ( ! function_exists( 'learn_press_assignment_after_sent' ) ) {
	/**
	 * Sent button.
	 */
	function learn_press_assignment_after_sent() {
		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		if ( ! $user->has_item_status( array(
			'completed'
		), $assignment->get_id(), $course->get_id() ) ) {
			return;
		}

		learn_press_assignment_get_template( 'content-assignment/buttons/sent.php' );
	}
}

if ( ! function_exists( 'learn_press_assignment_result' ) ) {
	/**
	 * Result button.
	 */
	function learn_press_assignment_result() {
		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		if ( ! $user->has_item_status( array(
			'evaluated'
		), $assignment->get_id(), $course->get_id() ) ) {
			return;
		}

		learn_press_assignment_get_template( 'content-assignment/buttons/result.php' );
	}
}

if ( ! function_exists( 'learn_press_assignment_retake' ) ) {
	/**
	 * Retake button.
	 */
	function learn_press_assignment_retake() {
		$course       = LP_Global::course();
		$user         = LP_Global::user();
		$assignments  = LP_Global::get_custom_posts( 'assignment' );
		$assignment   = current( $assignments );
		$user_item_id = learn_press_get_user_item_id( $user->get_id(), $assignment->get_id(), $course->get_id() );
		$retake_count = $assignment->get_data( 'retake_count' );
		$redo_time    = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_retaken', true );
		$redo_time    = ( $redo_time ) ? $redo_time : 0;
		if ( ! $user->has_item_status( array(
				'completed',
				'evaluated'
			), $assignment->get_id(), $course->get_id() ) || $retake_count <= $redo_time ) {
			return;
		}

		learn_press_assignment_get_template( 'content-assignment/buttons/retake.php' );
	}
}