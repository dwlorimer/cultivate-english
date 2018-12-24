<?php
/**
 * Template for displaying title of assignment.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/assignments/single-assignment/title.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Assignments/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit(); ?>

<?php
$assignments        = LP_Global::get_custom_posts( 'assignment' );
$current_assignment = current( $assignments );
?>

<h2><?php echo esc_html( $current_assignment->get_title() ); ?></h2>