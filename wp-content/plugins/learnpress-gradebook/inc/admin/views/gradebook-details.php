<?php
/**
 * Admin View: Gradebook details page.
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php $items = $datas[ $this->course->get_id() ]['items']; ?>

<table class="form-table">
    <thead>
    <tr>
        <td><?php _e( 'Title', 'learnpress-gradebook' ); ?></td>
        <td><?php _e( 'Type', 'learnpress-gradebook' ); ?></td>
        <td><?php _e( 'Passing Grade', 'learnpress-gradebook' ); ?></td>
        <td><?php _e( 'Results', 'learnpress-gradebook' ); ?></td>
        <td><?php _e( 'Status', 'learnpress-gradebook' ); ?></td>
    </tr>
    <thead>
    <tbody>
	<?php if ( ! empty( $items ) ) { ?>
		<?php foreach ( $items as $data_item ) { ?>
            <tr>
                <td><?php esc_html_e( $data_item->post_title ); ?></td>
                <td>
					<?php if ( strpos( $data_item->post_type, 'lp_' ) === 0 ) {
						_e( substr( $data_item->post_type, 3 ), 'learnpress-gradebook' );
					} ?>
                </td>
                <td>
					<?php
					if ( $data_item->post_type == 'lp_quiz' ) {
						$quiz = learn_press_get_quiz( $data_item->ID );
						printf( __( "%s%%", 'learnpress-gradebook' ), $quiz->get_data( 'passing_grade' ) );
					} else if ( $data_item->post_type == 'lp_lesson' ) {
						echo '-';
					} else {
						do_action( 'learn-press/gradebook/details-view/passing-grade', $data_item );
					} ?>
                </td>
                <td>
					<?php
					if ( $data_item->post_type == 'lp_quiz' ) {
						if ( $data_item->status == 'completed' ) {
							$user_item = learn_press_get_user( $data_item->user_id );
							$quiz_res  = $user_item->get_quiz_results( $data_item->item_id, $data_item->ref_id, false ); ?>
                            <span class="quiz-result">
                            <?php echo __( $quiz_res['grade'], 'learnpress-gradebook' ); ?></span> -
                            <span class="quiz-result-details"><?php printf( __( "%s/%s questions (%s%%)", 'learnpress-gradebook' ), $quiz_res['question_correct'], $quiz_res['mark'], ( $quiz_res['question_correct'] / $quiz_res['question_count'] ) * 100 ); ?></span>
						<?php } else {
							echo '-';
						}
					} else if ( $data_item->post_type == 'lp_lesson' ) {
						if ( $data_item->status == 'completed' ) {
							echo __( 'completed', 'learnpress-gradebook' );
						} else {
							echo '-';
						}
					} else {
						do_action( 'learn-press/gradebook/details-view/result', $data_item );
					} ?>
                </td>
                <td><?php _e( $data_item->status, 'learnpress-gradebook' ); ?></td>
            </tr>
		<?php } ?>
	<?php } ?>
    </tbody>
</table>