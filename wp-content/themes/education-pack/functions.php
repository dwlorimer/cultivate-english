<?php
/**
 * Theme functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 */

define( 'EDUCATION_PACK_DIR', trailingslashit( get_template_directory() ) );
define( 'EDUCATION_PACK_URL', trailingslashit( get_template_directory_uri() ) );
/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! function_exists( 'education_pack_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function education_pack_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on this theme, use a find and replace
		 * to change 'education-pack' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'education-pack', EDUCATION_PACK_DIR . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Add support Woocommerce
		add_theme_support( 'woocommerce' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'education-pack' ),
		) );

		if ( get_theme_mod( 'copyright_menu', true ) ) {
			register_nav_menus( array(
				'copyright_menu' => esc_html__( 'Copyright Menu', 'education-pack' ),
			) );
		}

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'audio',
			'quote',
			'link',
			'gallery',
			'chat',
		) );

		add_theme_support( 'custom-background' );

		add_editor_style();

		add_theme_support( 'so-builder' );

		add_theme_support( 'thim-core' );

		add_theme_support( 'thim-core-lite' );

		add_theme_support( 'education-pack-demo-data' );

	}
endif;
add_action( 'after_setup_theme', 'education_pack_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function education_pack_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'education_pack_content_width', 640 );
}

add_action( 'after_setup_theme', 'education_pack_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function education_pack_widgets_init() {
	$education_pack_options = get_theme_mods();

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'education-pack' ),
		'id'            => 'sidebar',
		'description'   => esc_html__( 'Appears in the Sidebar section of the site.', 'education-pack' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	if ( get_theme_mod( 'header_sidebar_right_display', true ) ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Header Right', 'education-pack' ),
			'id'            => 'header_right',
			'description'   => esc_html__( 'Display in header right.', 'education-pack' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	if ( isset( $education_pack_options['footer_columns'] ) ) {
		$footer_columns = (int) $education_pack_options['footer_columns'];
		for ( $i = 1; $i <= $footer_columns; $i ++ ) {
			register_sidebar( array(
				'name'          => sprintf( 'Footer Sidebar %s', $i ),
				'id'            => 'footer-sidebar-' . $i,
				'description'   => esc_html__( 'Sidebar display widgets.', 'education-pack' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
		}
	}

	/**
	 * Not remove
	 * Function create sidebar on wp-admin.
	 */
	$sidebars = apply_filters( 'thim_core_list_sidebar', array() );
	if ( count( $sidebars ) > 0 ) {
		foreach ( $sidebars as $sidebar ) {
			$new_sidebar = array(
				'name'          => $sidebar['name'],
				'id'            => $sidebar['id'],
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			);

			register_sidebar( $new_sidebar );
		}
	}

}

add_action( 'widgets_init', 'education_pack_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function education_pack_scripts() {
	global $current_blog, $wp_locale;

	//	Style
	if ( is_multisite() ) {
		if ( file_exists( EDUCATION_PACK_DIR . 'style-' . $current_blog->blog_id . '.css' ) ) {
			wp_enqueue_style( 'thim-style', get_template_directory_uri() . '/style-' . $current_blog->blog_id . '.css', array() );
		} else {
			wp_enqueue_style( 'thim-style', get_stylesheet_uri(), 100 );
		}
	} else {
		wp_enqueue_style( 'thim-style', get_stylesheet_uri() );
	}

	// Style default
	if ( ! education_pack_plugin_active( 'thim-core' ) ) {
		wp_enqueue_style( 'thim-default', EDUCATION_PACK_URL . 'inc/data/default.css', array() );
	}

	//	RTL
	if ( get_theme_mod( 'feature_rtl_support', false ) ) {
		wp_enqueue_style( 'thim-style-rtl', EDUCATION_PACK_URL . 'rtl.css', array() );
	}
	//	Scripts
	wp_enqueue_script( 'thim-main', EDUCATION_PACK_URL . 'assets/js/main.min.js', array(
		'jquery',
		'imagesloaded'
	), '', true );
	wp_enqueue_script( 'thim-cookie', EDUCATION_PACK_URL . 'assets/js/libs/jquery.cookie.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'bootstrap', EDUCATION_PACK_URL . 'assets/js/libs/bootstrap.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'dropkick', EDUCATION_PACK_URL . 'assets/js/libs/dropkick-2.1.8.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'fancybox', EDUCATION_PACK_URL . 'assets/js/libs/jquery.fancybox.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'flexslider', EDUCATION_PACK_URL . 'assets/js/libs/jquery.flexslider.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'owl-carousel', EDUCATION_PACK_URL . 'assets/js/libs/owl.carousel.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'theia-sticky-sidebar', EDUCATION_PACK_URL . 'assets/js/libs/theia-sticky-sidebar.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'stellar', EDUCATION_PACK_URL . 'assets/js/libs/stellar.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'magnific-popup', EDUCATION_PACK_URL . 'assets/js/libs/jquery.magnific-popup.min.js', array( 'jquery' ), '', true );

	if ( get_theme_mod( 'feature_smoothscroll', false ) ) {
		wp_enqueue_script( 'thim-smoothscroll', EDUCATION_PACK_URL . 'assets/js/libs/smoothscroll.js', array( 'jquery' ), '', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'education_pack_scripts', 1000 );

/**
 * Implement the theme wrapper.
 */
require EDUCATION_PACK_DIR . 'inc/libs/theme-wrapper.php';

/**
 * Implement the Custom Header feature.
 */
require EDUCATION_PACK_DIR . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require EDUCATION_PACK_DIR . 'inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require EDUCATION_PACK_DIR . 'inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require EDUCATION_PACK_DIR . 'inc/jetpack.php';

/**
 * Custom wrapper layout for theme
 */
require EDUCATION_PACK_DIR . 'inc/wrapper-layout.php';

/**
 * Load Plugin for Theme.
 */
if ( ! is_plugin_active( 'thim-education-pack/thim-education-pack.php' ) ) {
	if ( file_exists( EDUCATION_PACK_DIR . 'thim-education-pack/thim-education-pack.php' ) ) {
		require EDUCATION_PACK_DIR . 'thim-education-pack/thim-education-pack.php';
	}
}

/**
 * Custom functions
 */
require EDUCATION_PACK_DIR . 'inc/custom-functions.php';

/**
 * Custom functions
 */
require EDUCATION_PACK_DIR . 'inc/theme-info.php';

/**
 * Customizer additions.
 */
require EDUCATION_PACK_DIR . 'inc/customizer.php';

if ( is_admin() && current_user_can( 'manage_options' ) ) {
	require EDUCATION_PACK_DIR . 'inc/libs/installer.php';
	require EDUCATION_PACK_DIR . 'inc/admin/plugins-require.php';
}





function cpc_allow_view_for_coach($return, $item_id, $course_id, $user_id)
{
	/*
	print_R($return);
	print_R(' - ');
	print_R($item_id);
	print_R(' - ');
	print_R($course_id);
	print_R(' - ');
	print_R($user_id);
	die('to stop this');
	*/
	
	
	//if user_id is a mentor, let's let them view every course without question
	//in the future, this should probably restrict to the courses they have a mentee in
	
	$user = get_user_by( 'id', $user_id );
	if ($user) {
		if ( in_array( 'CPC_MENTOR_ROLE', (array) $user->roles ) ) {
			//The user has the "mentor" role
			//return true if user can view this content
			return true;
		} else {
			//just return whatever the value was before
			return $return;
		}	
	} else {
		return $return;
	}
    //$return = 'preview';
}
//add_filter('learn_press_user_can_view_item', 'allow_view_for_coach', 99);
add_filter('learn-press/can-view-item', 'cpc_allow_view_for_coach', 4, 99);




function cpc_allow_lesson_view_for_coach($return, $lesson_id, $user_id, $course_id)
{
	/*
	print_R($return);
	print_R(' - ');
	print_R($item_id);
	print_R(' - ');
	print_R($course_id);
	print_R(' - ');
	print_R($user_id);
	die('to stop this');
	*/
	
	
	//if user_id is a mentor, let's let them view every course without question
	//in the future, this should probably restrict to the courses they have a mentee in
	
	$user = get_user_by( 'id', $user_id );
	if ($user) {
		if ( in_array( 'CPC_MENTOR_ROLE', (array) $user->roles ) ) {
			//The user has the "mentor" role
			//return true if user can view this content
			return 'preview';
		} else {
			//just return whatever the value was before
			return $return;
		}	
	} else {
		return $return;
	}
    //$return = 'preview';
}
add_filter('learn-press/can-view-lesson', 'cpc_allow_lesson_view_for_coach', 4, 99);


function cpc_is_blocked_for_coach($return, $courseitem_id, $course_id, $user_id)
{	
	//if user_id is a mentor, let's let them view every course without question
	//in the future, this should probably restrict to the courses they have a mentee in
	
	$user = get_user_by( 'id', $user_id );
	if ($user) {
		if ( in_array( 'CPC_MENTOR_ROLE', (array) $user->roles ) ) {
			//The user has the "mentor" role
			//return false is user is not blocked from viewing this content
			return false;
		} else {
			//just return whatever the value was before
			return $return;
		}
	} else {
		return $return;
	}
}
add_filter('learn-press/course-item/is-blocked', 'cpc_is_blocked_for_coach', 4, 99);




function learn_press_assignment_mentor_comments () {

		$course      = LP_Global::course();
		$user        = LP_Global::user();
		$assignments = LP_Global::get_custom_posts( 'assignment' );
		$assignment  = current( $assignments );
		/*
		if ( ! $user->has_item_status( array(
			'evaluated'
		), $assignment->get_id(), $course->get_id() ) ) {
			return;
		}
		*/
				
		$course              = LP_Global::course();
		$assignments         = LP_Global::get_custom_posts( 'assignment' );
		$current_assignment  = current( $assignments );
		$user                = learn_press_get_current_user();
		$current_useritem_id = learn_press_get_user_item_id( $user->get_id(), $current_assignment->get_id(), $course->get_id() );

		$last_answer     = learn_press_get_user_item_meta( $current_useritem_id, '_lp_assignment_answer_note', true );
		$uploaded_files  = learn_press_assignment_get_uploaded_files( $current_useritem_id );
		$result_grade    = learn_press_assignment_get_result( $current_assignment->get_id(), $user->get_id(), $course->get_id() );
		$reference_files = learn_press_get_user_item_meta( $current_useritem_id, '_lp_assignment_evaluate_upload', true );
		$instructor_note = learn_press_get_user_item_meta( $current_useritem_id, '_lp_assignment_instructor_note', true );
		
		$coach_note		= learn_press_get_user_item_meta( $current_useritem_id, '_cpc_lp_assignment_coach_note', true );
		$coach_mark		= learn_press_get_user_item_meta( $current_useritem_id, '_cpc_lp_assignment_coach_mark', true );
		$champion_note	= learn_press_get_user_item_meta( $current_useritem_id, '_cpc_lp_assignment_champion_note', true );
		$champion_mark	= learn_press_get_user_item_meta( $current_useritem_id, '_cpc_lp_assignment_champion_mark', true );
		
			
		if ($coach_mark) {
		?>
		<hr>
		<div class="cpc-grade assignment-result <?php echo esc_attr( $result_grade['grade'] ); ?>">
			<div class="result-grade">
				<div>
				<h4>Coach Grade:</h4>
				</div>
				<div>
				<span class="result-achieved"><?php echo $coach_mark; ?></span>
				<span class="result-require"><?php echo $result_grade['mark']; ?></span>
				<!-- <p class="result-message"><?php echo sprintf( __( 'Your grade is <strong>%s</strong>', 'learnpress-assignments' ), $result_grade['grade'] == '' ? __( 'Ungraded', 'learnpress-assignments' ) : $result_grade['grade'] ); ?> </p> -->
				</div>
			</div>	
		</div>		
		<?php
		}
		if ($coach_note) {
		 ?>
			<div class="cpc-coach-comments cpc-mentor-comments">
				<h3>Coach Comments:</h3>
				<div>
					<blockquote><?php echo stripslashes($coach_note); ?></blockquote>
				</div>
			</div>		
		<?php			
		}		
		
		
		if ($champion_mark) {
		?>
		<hr>
		<div class="cpc-grade assignment-result <?php echo esc_attr( $result_grade['grade'] ); ?>">
			<div class="result-grade">
				<div>
				<h4>Champion Grade:</h4>
				</div>
				<div>
				<span class="result-achieved"><?php echo $champion_mark; ?></span>
				<span class="result-require"><?php echo $result_grade['mark']; ?></span>
				<!-- <p class="result-message"><?php echo sprintf( __( 'Your grade is <strong>%s</strong>', 'learnpress-assignments' ), $result_grade['grade'] == '' ? __( 'Ungraded', 'learnpress-assignments' ) : $result_grade['grade'] ); ?> </p> -->
				</div>
			</div>	
		</div>		
		<?php
		}
		if ($champion_note) {
		 ?>
			<div class="cpc-champion-comments cpc-mentor-comments">
				<h3>Champion Comments:</h3>
				<div>
					<blockquote><?php echo stripslashes($champion_note); ?></blockquote>
				</div>
			</div>		
		<?php				
		}
		?>
	<?php
}
add_action( 'learn-press/assignment-buttons', 'learn_press_assignment_mentor_comments', 17 );