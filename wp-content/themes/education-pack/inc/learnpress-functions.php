<?php
/**
 * Breadcrumb for LearnPress
 */
if ( ! function_exists( 'thim_learnpress_breadcrumb' ) ) {
	function thim_learnpress_breadcrumb() {

		// Do not display on the homepage
		if ( is_front_page() || is_404() ) {
			return;
		}

		// Get the query & post information
		global $post;
		$icon = '-';

		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';

		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr__( 'Home', 'education-pack' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'education-pack' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';

		if ( is_single() ) {

			$categories = get_the_terms( $post, 'course_category' );

			if ( get_post_type() == 'lp_course' ) {
				// All courses
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'education-pack' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'education-pack' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
			} else {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '" title="' . esc_attr( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '"><span itemprop="name">' . esc_html( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
			}

			// Single post (Only display the first category)
			if ( isset( $categories[0] ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $categories[0] ) ) . '" title="' . esc_attr( $categories[0]->name ) . '"><span itemprop="name">' . esc_html( $categories[0]->name ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';

		} else if ( is_tax( 'course_category' ) || is_tax( 'course_tag' ) ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'education-pack' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'education-pack' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';

			// Category page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( single_term_title( '', false ) ) . '">' . esc_html( single_term_title( '', false ) ) . '</span><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
		} else if ( ! empty( $_REQUEST['s'] ) && ! empty( $_REQUEST['ref'] ) && ( $_REQUEST['ref'] == 'course' ) ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'education-pack' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'education-pack' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';

			// Search result
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Search results for:', 'education-pack' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'education-pack' ) . ' ' . esc_html( get_search_query() ) . '</span><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
		} else if ( function_exists( 'learn_press_is_profile' ) && ( learn_press_is_profile() == true ) ) {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Profile', 'education-pack' ) . '">' . esc_html__( 'Profile', 'education-pack' ) . '</span><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
		} else {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'All courses', 'education-pack' ) . '">' . esc_html__( 'All courses', 'education-pack' ) . '</span><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
		}

		echo '</ul>';
	}
}


// remove default breadcrumb
remove_action( 'learn-press/before-main-content', 'learn_press_breadcrumb', 10 );
