<?php
$number_posts = 3;
if ( $instance['number_posts'] <> '' ) {
	$number_posts = $instance['number_posts'];
}
$columns = 3;
if ( $instance['columns'] <> '' ) {
	$columns = $instance['columns'];
}
$col       = 4;
$class_col = 'col-md-4 col-sm-4';
$col       = 12 / $columns;
$class_col = 'col-md-' . $col . ' col-sm-' . $col . '';

$style = '';
if ( $instance['style'] <> '' ) {
	$style = $instance['style'];
}
// Get image size
$image_size = 'thumbnail';
if ( $instance['image_size'] ) {
	if ( in_array( $instance['image_size'], array( 'medium', 'large', 'full' ) ) ) {
		$image_size = $instance['image_size'];
	}
	preg_match_all( '/\d+/', $instance['image_size'], $size_matches );
	if ( $size_matches[0] ) {
		if ( isset ( $size_matches[0][0] ) && isset ( $size_matches[0][1] ) ) {
			$image_size = array( $size_matches[0][0], $size_matches[0][1] );
		} else {
			$image_size = array( 370, 370 );
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

$order      = isset( $instance['order'] ) ? $instance['order'] : 'desc';
$query_args = array(
	'posts_per_page' => $number_posts,
	'order'          => $order,
);

if ( $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	$query_args['cat'] = $instance['cat_id'];
}
switch ( $instance['orderby'] ) {
	case 'recent' :
		$query_args['orderby'] = 'post_date';
		break;
	case 'title' :
		$query_args['orderby'] = 'post_title';
		break;
	case 'popular' :
		$query_args['orderby'] = 'comment_count';
		break;
	default : //random
		$query_args['orderby'] = 'rand';
}

$posts_display = new WP_Query( $query_args );
if ( $posts_display->have_posts() ) {
	echo '<div class="thim-list-posts ' . $style . '">';
	if ( $instance['link'] <> '' ) {
		echo '<div class="link_read_more"><a href="' . esc_url( $instance['link'] ) . '">' . esc_attr( $instance['text_link'] ) . '</a></div>';
	}
	echo '<div class="row">';
	while ( $posts_display->have_posts() ) {
		$posts_display->the_post();
		global $post;
		$images_mobile = get_the_post_thumbnail_url( $post->ID, 'large' );
		if ( $resize ) {
			$images = get_the_post_thumbnail_url();
		} else {
			$images = get_the_post_thumbnail_url( $post->ID, $image_size );
		}
		$image_crop = education_pack_aq_resize( $images, $resize[0], $resize[1], 1 );

		$class = 'item-post';
		?>
		<div class="<?php echo esc_attr( $class_col ); ?>">
			<div <?php post_class( $class ); ?>>
				<?php
				if ( $image_size && has_post_thumbnail() ) {
					echo '<div class="article-image">';
					echo '<a class="img-post-mobile" href="' . esc_url( get_permalink( get_the_ID() ) ) . '"><img src="' . esc_url( $images_mobile ) . '" alt= "' . esc_attr( get_the_title() ) . '" title = "' . esc_attr( get_the_title() ) . '" /></a>';
					if ( $resize ) {
						echo '<a class="img-post" href="' . esc_url( get_permalink( get_the_ID() ) ) . '"><img src="' . esc_url( $image_crop ) . '" alt= "' . esc_attr( get_the_title() ) . '" title = "' . esc_attr( get_the_title() ) . '" /></a>';
					} else {
						echo '<a class="img-post" href="' . esc_url( get_permalink( get_the_ID() ) ) . '"><img src="' . esc_url( $images ) . '" alt= "' . esc_attr( get_the_title() ) . '" title = "' . esc_attr( get_the_title() ) . '" /></a>';
					}
					echo '</div>';
				}
				echo '<div class="article-title-wrapper">';
				echo '<h5><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" class="article-title">' . esc_attr( get_the_title() ) . '</a></h5>';
				if ( $instance['show_meta'] && $instance['show_meta'] <> 'no' ) {
					echo '<div class="article-meta">';
					echo '<span class="entry-date">' . get_the_date() . '</span>';
					echo '<span class="author vcard">';
					echo esc_html__( 'by ', 'education-pack' ) . sprintf( '<a href="%1$s" rel="author">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) ) . '';
					echo '</span>';
					echo '</div>';
				}
				echo '</div>';
				?>
			</div>
		</div>
		<?php
	}
	echo '</div>';
	echo '</div>';
	wp_reset_postdata();
}
?>