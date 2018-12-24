<?php


/**
 * Theme Feature: Open Graph meta tag
 *
 * @param string
 */
if ( ! function_exists( 'education_pack_add_opengraph' ) ) {
	function education_pack_add_opengraph() {
		global $post;

		if ( get_theme_mod( 'feature_open_graph_meta', true ) ) {
			if ( is_single() ) {
				if ( has_post_thumbnail( $post->ID ) ) {
					$img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
					$img_src = esc_attr( $img_src[0] );
				} else {
					$img_src = EDUCATION_PACK_URL . 'assets/images/opengraph.png';
				}
				if ( $excerpt = $post->post_excerpt ) {
					$excerpt = strip_tags( $post->post_excerpt );
					$excerpt = str_replace( "", "'", $excerpt );
				} else {
					$excerpt = get_bloginfo( 'description' );
				}
				?>

				<meta property="og:title" content="<?php echo the_title(); ?>" />
				<meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>" />
				<meta property="og:type" content="article" />
				<meta property="og:url" content="<?php echo the_permalink(); ?>" />
				<meta property="og:site_name" content="<?php echo get_bloginfo(); ?>" />
				<meta property="og:image" content="<?php echo esc_attr( $img_src ); ?>" />

				<?php
			} else {
				return;
			}
		}
	}

	add_action( 'wp_head', 'education_pack_add_opengraph', 10 );
}


/**
 * Theme Feature: Google theme color
 */
if ( ! function_exists( 'education_pack_google_theme_color' ) ) {
	function education_pack_google_theme_color() {
		if ( get_theme_mod( 'feature_google_theme', false ) ) { ?>
			<meta name="theme-color" content="<?php echo esc_attr( get_theme_mod( 'feature_google_theme_color', '#333333' ) ) ?>">
			<?php
		}
	}

	add_action( 'wp_head', 'education_pack_google_theme_color', 10 );
}


/**
 * Theme Feature: Open Graph insert doctype
 *
 * @param $output
 */
if ( ! function_exists( 'education_pack_doctype_opengraph' ) ) {
	function education_pack_doctype_opengraph( $output ) {
		if ( get_theme_mod( 'feature_open_graph_meta', true ) ) {
			return $output . ' prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"';
		}
	}

	add_filter( 'language_attributes', 'education_pack_doctype_opengraph' );
}