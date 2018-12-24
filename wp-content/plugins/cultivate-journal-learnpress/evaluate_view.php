<?php
/**
 * Front End Admin View: Assignment evaluate page for Coach and Champion.
 * this file is a modified copy of \plugins\learnpress-assignments\inc\admin\views\evaluate.php
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>



<?php
//$assignment_id = LP_Request::get_int( 'assignment_id' );
//$user_id       = LP_Request::get_int( 'user_id' );

/*
if ( ! learn_press_assignment_verify_url( $assignment_id ) ) { ?>
    <div id="error-page">
        <p><?php _e( 'Sorry, you are not allowed to access this page.', 'learnpress-assignments' ); ?></p>
    </div>
	<?php
	return;
}
*/

if ( ! $user_id ) {
	_e( 'Invalid student', 'learnpress-assignments' );

	return;
}

if ( ! $assignment = learn_press_get_assignment( $assignment_id ) ) {
	_e( 'Invalid course', 'learnpress-assignments' );

	return;
} ?>

<?php
$user      = learn_press_get_user( $user_id );
$course    = learn_press_get_item_courses( $assignment_id );
$lp_course = learn_press_get_course( $course[0]->ID );

$course_data = $user->get_course_data( $lp_course->get_id() );

$evaluated = $user->has_item_status( array( 'evaluated' ), $assignment_id, $lp_course->get_id() );

$user_item_id = $course_data->get_item( $assignment_id )->get_user_item_id();

$last_answer    = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_answer_note', true );
$uploaded_files = learn_press_assignment_get_uploaded_files( $user_item_id );
?>











<div class="wrap" id="learn-press-evaluate">
    <!-- <h3><?php esc_html_e( 'Evaluate Form', 'learnpress-assignments' ); ?></h3> -->
    <!-- <a href="<?php echo esc_attr( learn_press_assignment_students_url( $assignment_id ) ) ?>"><?php _e( 'Back to list students', 'learnpress-assignments' ); ?></a> -->

    <div id="poststuff" class="<?php echo $evaluated ? esc_attr( 'assignment-evaluated' ) : ''; ?>">

			
            <div id="post-body" class="metabox-holder columns-2">

                <div id="postbox-container-2" class="postbox-container">
                    <div class="inside">


                        <h4 class="section-title">
							<?php echo $section_name; ?>
                        </h4>

                        <h4 class="assignment-title">
                            <a href="<?php echo $assignment_link; ?>"
                               target="_blank"><?php echo $assignment->get_title(); ?></a>
                        </h4>
						<?php if ( $intro = get_post_meta( $assignment_id, '_lp_introduction', true ) ) { ?>
                            <div class="assignment-content"><?php echo $intro; ?></div>
						<?php } ?>
						
                        <div class="rwmb-field rwmb-heading-wrapper assignment-heading">
                            <h4><?php _e( 'Assignment', 'learnpress-assignments' ); ?></h4>
							<?php echo apply_filters('the_content', get_post_field('post_content', $user_assignment->get_id())); ?>
                        </div>						

                        <div class="rwmb-field rwmb-heading-wrapper submission-heading">
                            <h4><?php _e( 'Submission', 'learnpress-assignments' ); ?></h4>
                            <!-- <p class="description"><?php _e( 'Include student assignment answer and attach files.', 'learnpress-assignments' ); ?></p> -->
                        </div>
                        <div class="rwmb-field answer-content">
                            <div class="rwmb-label">
                                <label for="user-answer"><?php _e( 'Answer', 'learnpress-assignments' ); ?></label>
                            </div>
                            <div class="rwmb-input">
								<?php echo "<blockquote>" .$last_answer. "</blockquote>"; ?>
                            </div>
                        </div>
                        <div class="rwmb-field answer-uploads">
                            <div class="rwmb-label">
                                <label for="user-uploads"><?php _e( 'Attach File', 'learnpress-assignments' ); ?></label>
                            </div>
                            <div class="rwmb-input">
								<?php if ( $uploaded_files ) { ?>
                                    <ul class="assignment-files assignment-uploaded list-group list-group-flush">
										<?php foreach ( $uploaded_files as $file ) { ?>
                                            <li class="list-group-item"><a
                                                        href="<?php echo get_site_url() . $file['url']; ?>"
                                                        target="_blank"><?php echo $file['filename']; ?></a></li>
										<?php } ?>
                                    </ul>
								<?php } else { ?>
                                    <i><?php _e( 'There is no assignments attach file(s).', 'learnpress-assignments' ); ?></i>
								<?php } ?>
                            </div>
                        </div>

                        <div class="rwmb-field rwmb-heading-wrapper">
                            <h4><?php _e( 'Evaluation', 'learnpress-assignments' ); ?></h4>
                            <p class="description"><?php _e( 'Your evaluation about student submission.', 'learnpress-assignments' ); ?><br/>
							Max grade is <?php echo $assignment->get_data( 'mark' ); ?></p>
                        </div>
						
						<div>
						<form method="post">
									<input type="hidden" name="user_item_id" value="<?php echo esc_attr( $user_item_id ); ?>">
									<input type="hidden" name="page" value="assignment-evaluate">
									<input type="hidden" name="assignment_id" value="<?php echo esc_attr( $assignment_id ); ?>">
									<input type="hidden" name="mentor" value="<?php echo esc_attr( $relationship ); ?>">
							
									<?php	
									
									$prefix = '_lp_evaluate_assignment_';

									$mark   = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true );
									$note   = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_instructor_note', true );
									$upload = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_evaluate_upload', true );			

									$settings = 
										array(
											array(
												'title'            => __( 'Mark', 'learnpress-assignments' ),
												'id'               => $prefix . 'mentor_mark',
												//'std'              => $mark ? $mark : 0,
												'std'              => 0,
												'type'             => 'number',
												'desc'             => __( 'Mark for user answer.', 'learnpress-assignments' ),
												'min'              => 0,
												'max'              => $assignment ? $assignment->get_data( 'mark' ) : '',
												'assignment-field' => 'yes',
												//'disabled'         => $this->evaluated ? true : false
											),
											array(
												'title'            => __( 'Your Comment', 'learnpress-assignments' ),
												'id'               => $prefix . 'mentor_comment',
												'std'              => $note ? $note : '',
												'type'             => 'textarea',
												'placeholder'      => __( 'Note here...', 'learnpress-assignments' ),
												'desc'             => __( '', 'learnpress-assignments' ),
												'assignment-field' => 'yes',
												//'disabled'         => $this->evaluated ? true : false
											),
										);	
									
									
									LP_Meta_Box_Helper::render_fields( $settings );	
									?>
									
						<input type="submit" value="Submit">			

						</form>
						</div>						

						<?php
						
						
//TODO -- show comments of whatever relationship this person is not
// -- show already submitted comments from this person; if none, show comment form.
// have fun.  I'm tired for today.  Bye.						
						


		$result_grade    = learn_press_assignment_get_result( $assignment_id, $user_id, $course_id );		
		
		$coach_note		= learn_press_get_user_item_meta( $user_item_id, '_cpc_lp_assignment_coach_note', true );
		$coach_mark		= learn_press_get_user_item_meta( $user_item_id, '_cpc_lp_assignment_coach_mark', true );
		$champion_note	= learn_press_get_user_item_meta( $user_item_id, '_cpc_lp_assignment_champion_note', true );
		$champion_mark	= learn_press_get_user_item_meta( $user_item_id, '_cpc_lp_assignment_champion_mark', true );
						
						
if ( ($relationship == 'coach' && !$coach_note && !$coach_mark) || ($relationship == 'champion' && !$champion_note && !$champion_mark) ) {
	//show grade form
	
	
}

		
			
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
					<blockquote><?php echo $coach_note; ?></blockquote>
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
					<blockquote><?php echo $champion_note; ?></blockquote>
				</div>
			</div>		
		<?php				
		}
		?>						
						
						
						
						
						
						
						
						
						
						
						
						
						
<?php 		
						
						
						
						
						//maybe split this out again later?
						//require "class-cpc-lp-assignment-evaluate.php";
						//CPC_LP_Assignment_Evaluate::instance( $assignment, $user_item_id, $evaluated )->display(); ?>


<!--						
						<div id="commentBoxtask0exp1je0">
						<hr>
						<h4>Post New Comment</h4>
						
                        <div class="rwmb-field answer-content">
                            <div class="rwmb-label">
                                <label for="mentor-comment"><?php _e( 'Comment', 'learnpress-assignments' ); ?></label>
                            </div>
                            <div class="rwmb-input">
								<?php wp_editor( $last_answer, 'assignment-editor-student-answer', array(
									'media_buttons' => false,
									'textarea_rows' => 10
								) ); ?>
                            </div>
                        </div>						

						</div>
-->
						
						<?php
						
						//all needs to be saved special
						
						?>


                    </div>
                </div>			
				
            </div>
    </div>
</div>


