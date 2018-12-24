<?php
/*
* Plugin Name: Cultivate Custom Learnpress Journal Add-On
* Description: Adds Journal and Teacher functions to Learnpress.  Requires Learnpress Assignments Add-On.
* Version: 0.1
* Author: David Lorimer
* Author URI: 
*/

// Example 2 : WP Shortcode to display text on page or post.
function cultivate_journal_shortcode(){


?>

<?php
/**
 * Template for displaying assignments tab in user profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/assignments/profile/tabs/assignments.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Assignments/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$profile = LP_Profile::instance();
$user    = $profile->get_user();

$filter_status = LP_Request::get_string( 'filter-status' );
$curd          = new LP_Assignment_CURD();
$query         = $curd->profile_query_assignments( $profile->get_user_data( 'id' ), array( 'status' => $filter_status ) );
?>

<div class="learn-press-subtab-content">
    <h3 class="profile-heading"><?php _e( 'My Assignments', 'learnpress-assignments' ); ?></h3>

	<?php if ( $filters = $curd->get_assignments_filters( $profile, $filter_status ) ) { ?>
        <ul class="lp-sub-menu">
			<li class="all"><a href="?filter-status=all">All</a></li>
			<li class="completed"><a href="?filter-status=completed">Submitted</a></li>
			<li class="evaluated"><a href="?filter-status=evaluated">Evaluated</a></li>
			<li class="passed"><a href="?filter-status=passed">Passed</a></li>
			<li class="failed"><a href="?filter-status=failed">Failed</a></li>
			<?php /* foreach ( $filters as $class => $link ) { ?>
                <li class="<?php echo $class; ?>"><?php echo $link; ?></li>
			<?php } */ ?>
        </ul>
	<?php } ?>

	<?php if ( $query['items'] ) { ?>
        <table class="lp-list-table profile-list-assignments profile-list-table">
            <thead>
            <tr>
                <th class="column-course"><?php _e( 'Course', 'learnpress-assignments' ); ?></th>
                <th class="column-assignment"><?php _e( 'Assignment', 'learnpress-assignments' ); ?></th>
                <th class="column-padding-grade"><?php _e( 'Passing Grade', 'learnpress-assignments' ); ?></th>
                <th class="column-status"><?php _e( 'Status', 'learnpress-assignments' ); ?></th>
                <th class="column-mark"><?php _e( 'Mark', 'learnpress-assignments' ); ?></th>
                <th class="column-time-interval"><?php _e( 'Interval', 'learnpress-assignments' ); ?></th>
                <th class="column-actions"><?php _e( 'Actions', 'learnpress-assignments' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $query['items'] as $user_assignment ) { ?>
				<?php
				/**
				 * @var $user_assignment LP_User_Item_Assignment
				 */
				$assignment = learn_press_get_assignment( $user_assignment->get_id() );
				$courses    = learn_press_get_item_courses( array( $user_assignment->get_id() ) );

				$course       = learn_press_get_course( $courses[0]->ID );
				$course_data  = $user->get_course_data( $course->get_id() );
				$user_item_id = $course_data->get_item( $assignment->get_id() )->get_user_item_id();
				$mark         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true );
				$evaluated    = $user->has_item_status( array( 'evaluated' ), $assignment->get_id(), $course->get_id() ); ?>
                <tr>
                    <td class="column-course">
	                    <?php if ( $courses ) { ?>
                            <a href="<?php echo $course->get_permalink() ?>">
			                    <?php echo $course->get_title( 'display' ); ?>
                            </a>
	                    <?php } ?>
                    </td>
                    <td class="column-assignment">
						<?php if ( $courses ) { ?>
                            <a href="<?php echo $course->get_item_link( $user_assignment->get_id() ) ?>">
								<?php echo $assignment->get_title( 'display' ); ?>
                            </a>
						<?php } ?>
                    </td>
                    <td class="column-padding-grade">
						<?php echo $assignment->get_data( 'passing_grade' ); ?>
                    </td>
                    <td class="column-status">
						<?php echo $evaluated ? __( 'Evaluated', 'learnpress-assignments' ) : __( 'Not evaluate', 'learnpress-assignments' ); ?>
                    </td>
                    <td class="column-mark">
						<?php
						if ( $evaluated ) {
							echo $mark . '/' . $assignment->get_data( 'mark' );

							if ( ! $evaluated ) {
								$status = __( 'completed', 'learnpress-assignments' );
							} else {
								$status = $mark >= $assignment->get_data( 'passing_grade' ) ? __( 'passed', 'learnpress-assignments' ) : __( 'failed', 'learnpress-assignments' );
							} ?>
                            <span class="lp-label label-<?php echo esc_attr( $status ); ?>"><?php esc_html_e( $status ); ?></span>
						<?php } else {
							echo '-';
						} ?>
                    </td>
                    <td class="column-time-interval">
						<?php echo( $user_assignment->get_time_interval( 'display' ) ); ?>
                    </td>
                    <td class="column-actions">
                        <a href="<?php echo $assignment->get_permalink(); ?>"
                           class="view"><?php _e( 'View', 'learnpress-assignments' ); ?> </a>
                    </td>
                </tr>
				<?php continue; ?>
                <tr>
                    <td colspan="4"></td>
                </tr>
			<?php } ?>
            </tbody>
            <tfoot>
            <tr class="list-table-nav">
                <td colspan="2" class="nav-text">
					<?php echo $query->get_offset_text(); ?>
                </td>
                <td colspan="4" class="nav-pages">
					<?php $query->get_nav_numbers( true ); ?>
                </td>
            </tr>
            </tfoot>
        </table>

	<?php } else { ?>
		<?php learn_press_display_message( __( 'No assignments!', 'learnpress-assignments' ) ); ?>
	<?php } ?>
</div>












<?php
$profile = LP_Profile::instance();
$user    = $profile->get_user();

$filter_status = LP_Request::get_string( 'filter-status' );
$curd          = new LP_Assignment_CURD();
$query         = $curd->profile_query_assignments( $profile->get_user_data( 'id' ), array( 'status' => $filter_status ) );
//$query         = $curd->profile_query_assignments( $profile->get_user_data( 'id' ), array( 'status' => 'completed' ) );
?>





<div class="learn-press-subtab-content">
    <h3 class="profile-heading"><?php _e( 'Journal', 'learnpress-assignments' ); ?></h3>


	<?php if ( $query['items'] ) { ?>
		<!--<div>
		                    <a href="<?php echo $course->get_item_link( $user_assignment->get_id() ) ?>">
								<?php echo $assignment->get_title( 'display' ); ?>
                            </a>
							
							<?php // print_R($assignment); 
							//print_R($course);
							
							
							?>
		</div>-->
	
	
	
	
        <table class="lp-list-table profile-list-assignments profile-list-table">
            <tbody>
			<?php foreach ( $query['items'] as $user_assignment ) { ?>
				<?php
				/**
				 * @var $user_assignment LP_User_Item_Assignment
				 */
				 
				 //print_R($user_assignment);
				 //print_R($user_assignment);
				 
				 
				 
				$assignment = learn_press_get_assignment( $user_assignment->get_id() );
				$assignment_id = $user_assignment->get_id();
				$courses    = learn_press_get_item_courses( array( $user_assignment->get_id() ) );
				//$section 	= learn_press_get_section ('1');
				//$section = learn_press_get_section_items( "1" );
				

				//print_R($section);
				
				global $wpdb;
				$section_id = $wpdb->get_row("SELECT section_id FROM {$wpdb->prefix}learnpress_section_items WHERE item_id = $assignment_id");
				$section_id = $section_id->section_id;
				//print_R($section_id);
				
				$section_name = $wpdb->get_row("SELECT section_name FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $section_id");
				$section_name = $section_name->section_name;
				//print_R( $section_name);
				
				$assignment_link = $course->get_item_link( $user_assignment->get_id() );
				
			
				
				//print_R($assignment);
				//print_R($courses);

				$course       = learn_press_get_course( $courses[0]->ID );
				$course_data  = $user->get_course_data( $course->get_id() );
				$user_item_id = $course_data->get_item( $assignment->get_id() )->get_user_item_id();
				
				//$user_item = new LP_User_Item($user_assignment);
				$end_date 	  = $user_assignment->get_end_time( 'display' );
				//print_R($end_time);
				
				//print_R($course_data);
				
				//print_R($user_item_id);
				//print_R($assignment->get_id());
				//print_R($assignment->get_section() );
				
				//print_R( $section->get_id());
				
				$student_response         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_answer_note', true );
				$mark         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true );
				$evaluated    = $user->has_item_status( array( 'evaluated' ), $assignment->get_id(), $course->get_id() );
				$coach_mark		= learn_press_get_user_item_meta( $user_item_id, '_cpc_lp_assignment_coach_mark', true );
				$champion_mark	= learn_press_get_user_item_meta( $user_item_id, '_cpc_lp_assignment_champion_mark', true );	

				$uploaded_files = learn_press_assignment_get_uploaded_files( $user_item_id );				
				
				//print_R($student_response);
				?>
				<tr><td>
				<div class="journal_block_listings">
					<div class="journal_block_left" style="width: 78%; float: left;">
						<div class="journal_listing_title">
							<?php echo $section_name . ": "; ?> <a href="<?php echo $assignment_link; ?>"><?php echo $assignment->get_title( 'display' ); ?></a>
						</div>
						<div class="journal_listing_copy">
							<?php echo "<blockquote>" . $student_response ."</blockquote>"; ?>
						</div>
					</div>
					<div class="journal_block_right" style="width: 20%; float: right; font-size: 0.8em; font-weight: bold; text-align: right; line-height: 1.3em;">
					<div class="course-title"><?php echo $course->get_title( 'display' ); ?></div>
						<?php 
						if ($end_date) {
							echo $end_date . "<br/>";
						}
						if ($coach_mark) {
							echo "Coach Rating: $coach_mark <br/>";
						}
						if ($champion_mark) {
							echo "Champion Rating: $champion_mark <br/>";
						}
						?>	

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
				</div>
				</tr></td>
				
			<?php } ?>
            </tbody>
            <tfoot>
            <tr class="list-table-nav">
                <td colspan="2" class="nav-text">
					<?php echo $query->get_offset_text(); ?>
                </td>
                <td colspan="4" class="nav-pages">
					<?php $query->get_nav_numbers( true ); ?>
                </td>
            </tr>
            </tfoot>
        </table>

	<?php } else { ?>
		<?php learn_press_display_message( __( 'No assignments!', 'learnpress-assignments' ) ); ?>
	<?php } ?>
</div>




















<?php



}
add_shortcode('cultivate-journal', 'cultivate_journal_shortcode');




add_shortcode('cultivate-mentee-list', 'cultivate_mentee_list_shortcode');
function cultivate_mentee_list_shortcode(){ 
	$user_id = get_current_user_id();
	
	//print_R($user_id);
	
	if (!$user_id) {
		echo "You do not have permission to view this page";
		exit;
	}
	
	
	//
	// process form submission
	//
	

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$xpage          = LP_Request::get( 'page' );
			$xassignment_id = LP_Request::get_int( 'assignment_id' );
			//$user_id       = LP_Request::get_int( 'user_id' );
			$xuser_item_id = LP_Request::get( 'user_item_id' );
			$xmentor = LP_Request::get( 'mentor' );
			$xmentorcomment = LP_Request::get( '_lp_evaluate_assignment_mentor_comment' );
			$xmentormark = LP_Request::get( '_lp_evaluate_assignment_mentor_mark' );
			
			$xcoach_mark	= learn_press_get_user_item_meta( $xuser_item_id, '_cpc_lp_assignment_coach_mark', true );
			$xchampion_mark	= learn_press_get_user_item_meta( $xuser_item_id, '_cpc_lp_assignment_champion_mark', true );
			$xassignment_mark	= learn_press_get_user_item_meta( $xuser_item_id, '_lp_assignment_mark', true );
			
			/*				
			_cpc_lp_assignment_coach_mark
			_cpc_lp_assignment_champion_mark
			_cpc_lp_assignment_coach_note
			_cpc_lp_assignment_champion_note
			*/

			if($xmentor and $xmentor != 'unknown') {

				global $wpdb;
				$wpdb->insert("{$wpdb->prefix}learnpress_user_itemmeta", array(
				   "learnpress_user_item_id" => $xuser_item_id,
				   "meta_key" => "_cpc_lp_assignment_{$xmentor}_note",
				   "meta_value" => $xmentorcomment,
				));
				$wpdb->insert("{$wpdb->prefix}learnpress_user_itemmeta", array(
				   "learnpress_user_item_id" => $xuser_item_id,
				   "meta_key" => "_cpc_lp_assignment_{$xmentor}_mark",
				   "meta_value" => $xmentormark,
				));

			}
			
			
			//create "teacher" evaluation for the sake of Learnpress understanding what we're doing.
			
			//mark as evaluated.  We're marking this as soon as one person, coach or champion, has evaluated them.  We don't use it on our end, but Learnpress needs it to mark assignment complete
			$user_curd = new LP_User_CURD();
			$user_curd->update_user_item_status($xuser_item_id, "evaluated");
			
			//if one person had already graded, merge grades to create average grade
			//if not, just set this mark
			$xmerge_mark = $xmentormark;
			//_lp_assignment_mark
			if ($xcoach_mark) {
				$xmerge_mark = ($xcoach_mark + $xmentor_mark)/2;
			} elseif ($xchampion_mark) {
				$xmerge_mark = ($xchampion_mark + $xmentor_mark)/2;
			}
			
			if ($xassignment_mark) {
				learn_press_update_user_item_meta( $xuser_item_id, '_lp_assignment_mark', $xmerge_mark );
			} else {
				$wpdb->insert("{$wpdb->prefix}learnpress_user_itemmeta", array(
				   "learnpress_user_item_id" => $xuser_item_id,
				   "meta_key" => "_lp_assignment_mark",
				   "meta_value" => $xmerge_mark,
				));				
			}
			
			
			
			

			
/*
//update grade with either passed or failed
			//$user_item_id           = learn_press_get_user_item_id( $user_id, $item_id, $course_id );
			$result['user_mark']    = ( learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true ) ) ? learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true ) : 0;
			$result['retake_count'] = ( learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_retaken', true ) ) ? learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_retaken', true ) : 0;
			$percent                = $result['mark'] ? ( $result['user_mark'] / $result['mark'] ) * 100 : 0;
			$passing_condition      = ( $assignment->get_data( 'passing_grade' ) / $result['mark'] ) * 100;
			$result['result']       = $percent;
			$result['grade']        = $status === 'evaluated' ? ( $percent >= $passing_condition ? 'passed' : 'failed' ) : '';
			if ( false === learn_press_get_user_item_meta( $user_item_id, 'grade', true ) ) {
				learn_press_update_user_item_meta( $user_item_id, 'grade', $result['grade'] );
			}			
*/
		$xassignment             = new LP_Assignment( $xassignment_id );
		//$user                   = learn_press_get_user( $user_id );
		//$status                 = $user->get_item_status( $item_id, $course_id );
		$status                 = "evaluated";
		
		$result                 = array(
			'mark'         => $xassignment->get_mark(),
			'user_mark'    => $xmerge_mark,
			'status'       => 'evaluated',
			//'grade'        => '',
			'result'       => 0,
			'retake_count' => 0
		);
		
		//$user_item_id           = learn_press_get_user_item_id( $user_id, $item_id, $course_id );
		//$result['user_mark']    = ( learn_press_get_user_item_meta( $xuser_item_id, '_lp_assignment_mark', true ) ) ? learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true ) : 0;
		//$result['retake_count'] = ( learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_retaken', true ) ) ? learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_retaken', true ) : 0;
		$percent                = $result['mark'] ? ( $result['user_mark'] / $result['mark'] ) * 100 : 0;
		$passing_condition      = ( $xassignment->get_data( 'passing_grade' ) / $result['mark'] ) * 100;
		$result['result']       = $percent;
		$result['grade']        = $status === 'evaluated' ? ( $percent >= $passing_condition ? 'passed' : 'failed' ) : '';
		//if ( false === learn_press_get_user_item_meta( $user_item_id, 'grade', true ) ) {
			learn_press_update_user_item_meta( $xuser_item_id, 'grade', $result['grade'] );
		//}





		
	} //end post submission
	//end post submission, should be safe to display records.
	
	
	
	
	
	
	
	//TODO - remove this
	//$user_id = 5;
	
	global $wpdb;	
	$mentees = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}usermeta WHERE (meta_key = 'has_coach' and meta_value =$user_id) OR (meta_key = 'has_champion' and meta_value =$user_id)" ); 
	
	if (!$mentees) {
		echo "You currently have no students that you are coach or champion for.";
	}
	
	//print_R($mentees);
	
	echo '<div class="cpc-student-list"><table>';
	
	foreach ($mentees as $mentee) {
		//print_R($mentee);
		$menteedata = get_userdata( $mentee->user_id );
		//print_R($cpcstudentdata);
		if ($mentee->meta_key == 'has_coach') {
			$relationship = 'coach';
		} elseif ($mentee->meta_key == 'has_champion') {
			$relationship = 'champion';
		} else {
			$relationship = 'unknown';
		}
		
		$user = learn_press_get_user( $mentee->user_id );		
		
		echo '<tr><td style="background-color: #3498db; color: white;">';

		echo "<h4 class='cpc-mentee-name'>You are " . $menteedata->display_name . "'s " . $relationship ."</h4>"; 
		echo " - <a href='#'>View Submissions</a>";
		//learn_press_get_template( 'progress.php' );
		echo '</td></tr>';
		

		
		
		
		echo '<tr><td>';

			
			//$profile = LP_Profile::instance();
			//$user    = $profile->get_user();	

		
			$curd          = new LP_Assignment_CURD();

			$query         = $curd->profile_query_assignments( $mentee->user_id, array( 'status' => 'completed' ) );
			$querye         = $curd->profile_query_assignments( $mentee->user_id, array( 'status' => 'evaluated' ) );
			//$query			= (object)array_merge((array)$query, (array)$querye);
			
		
			if ( $query['items'] ) {
				foreach ( $query['items'] as $user_assignment ) { 

					//print_R($user_assignment);
					
					$assignment_id = $user_assignment->get_id();
					//print_R($assignment_id);
					$assignment = learn_press_get_assignment( $user_assignment->get_id() );
					$courses    = learn_press_get_item_courses( array( $user_assignment->get_id() ) );
					
					$passing_grade = $assignment->get_data( 'passing_grade' );
					$max_mark = $assignment->get_data( 'mark' );
				
					$course       = learn_press_get_course( $courses[0]->ID );
					$course_id	  = $course->get_id();
					$course_data  = $user->get_course_data( $course->get_id() );
					$user_item_id = $course_data->get_item( $assignment->get_id() )->get_user_item_id();
					$mark         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true );
					$evaluated    = $user->has_item_status( array( 'evaluated' ), $assignment->get_id(), $course->get_id() );
					
					$mentor_mark	= learn_press_get_user_item_meta( $user_item_id, "_cpc_lp_assignment_{$relationship}_mark", true );
					$mentor_note	= learn_press_get_user_item_meta( $user_item_id, "_cpc_lp_assignment_{$relationship}_note", true );
					
					//if this mentor has already graded this assignment, do not show in this list
					if ($mentor_mark || $mentor_note) {
						//skip this record. do nothing and carry on.
					} else {
						echo '<tr><td>';	
						include( 'progress.php' );					


						global $wpdb;
						$section_id = $wpdb->get_row("SELECT section_id FROM {$wpdb->prefix}learnpress_section_items WHERE item_id = $assignment_id");
						$section_id = $section_id->section_id;
						//print_R($section_id);
						
						$section_name = $wpdb->get_row("SELECT section_name FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $section_id");
						$section_name = $section_name->section_name;
						//print_R( $section_name);
						
						$assignment_link = $course->get_item_link( $user_assignment->get_id() );
						
						

						?>
						
								<a href="<?php// echo $course->get_item_link( $user_assignment->get_id() ) ?>">
									<?php // echo $assignment->get_title( 'display' ); ?>
								</a>	
								
						<?php

						
						$student_response         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_answer_note', true );
						
						//print_R($student_response);
						
						
						//echo apply_filters('the_content', get_post_field('post_content', $user_assignment->get_id()));
						
						
						//echo '</tr></td>';
						//echo '<tr><td>';
							$user_id = $mentee->user_id;
							include('evaluate_view.php');
						echo '</tr></td>';
					}
				}
			}
		
	
			//repeat for other array of assignments
			if ( $querye['items'] ) {
				foreach ( $querye['items'] as $user_assignment ) { 

					//print_R($user_assignment);
					
					$assignment_id = $user_assignment->get_id();
					//print_R($assignment_id);
					$assignment = learn_press_get_assignment( $user_assignment->get_id() );
					$courses    = learn_press_get_item_courses( array( $user_assignment->get_id() ) );
					
					$passing_grade = $assignment->get_data( 'passing_grade' );
					$max_mark = $assignment->get_data( 'mark' );
				
					$course       = learn_press_get_course( $courses[0]->ID );
					$course_id	  = $course->get_id();
					$course_data  = $user->get_course_data( $course->get_id() );
					$user_item_id = $course_data->get_item( $assignment->get_id() )->get_user_item_id();
					$mark         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true );
					$evaluated    = $user->has_item_status( array( 'evaluated' ), $assignment->get_id(), $course->get_id() );
					
					$mentor_mark	= learn_press_get_user_item_meta( $user_item_id, "_cpc_lp_assignment_{$relationship}_mark", true );
					$mentor_note	= learn_press_get_user_item_meta( $user_item_id, "_cpc_lp_assignment_{$relationship}_note", true );
					
					//if this mentor has already graded this assignment, do not show in this list
					if ($mentor_mark || $mentor_note) {
						//skip this record. do nothing and carry on.
					} else {
						echo '<tr><td>';						


						global $wpdb;
						$section_id = $wpdb->get_row("SELECT section_id FROM {$wpdb->prefix}learnpress_section_items WHERE item_id = $assignment_id");
						$section_id = $section_id->section_id;
						//print_R($section_id);
						
						$section_name = $wpdb->get_row("SELECT section_name FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $section_id");
						$section_name = $section_name->section_name;
						//print_R( $section_name);
						
						$assignment_link = $course->get_item_link( $user_assignment->get_id() );
						
						

						?>
						
								<a href="<?php// echo $course->get_item_link( $user_assignment->get_id() ) ?>">
									<?php // echo $assignment->get_title( 'display' ); ?>
								</a>	
								
						<?php

						
						$student_response         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_answer_note', true );
						
						//print_R($student_response);
						
						
						//echo apply_filters('the_content', get_post_field('post_content', $user_assignment->get_id()));
						
						
						//echo '</tr></td>';
						//echo '<tr><td>';
							$user_id = $mentee->user_id;
							include('evaluate_view.php');
						echo '</tr></td>';
					}
				}
			}			
							 
	
		
		
		
		
		
		
		
		
		
		echo '</td></tr>';
	}
	
	echo '</table></div>';

 
	
}

?>




<?php 
/// functions



/**
 * Add more 2 user roles teacher and student
 *
 */
 
 /*
   function add_roles_on_plugin_activation() {
       add_role( 'custom_role', 'Custom Subscriber', array( 'read' => true, 'edit_posts' => true ) );
   }
   register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );
   */
   

 
function cpc_learn_press_add_user_roles() {

	//$settings = LP()->settings;

	/* translators: user role */
	_x( 'Mentor', 'User role' );

	add_role(
		'CPC_MENTOR_ROLE',
		'Mentor',
		array()
	);
	
	
	if ( $cpcpmentor = get_role( 'CPC_MENTOR_ROLE' ) ) {
		$cpcpmentor->add_cap( 'read' );
	}

	/*
	$course_cap = LP_COURSE_CPT . 's';
	$lesson_cap = LP_LESSON_CPT . 's';
	$order_cap  = LP_ORDER_CPT . 's';

	// teacher
	if ( $teacher = get_role( LP_TEACHER_ROLE ) ) {
		$teacher->add_cap( 'delete_published_' . $course_cap );
		$teacher->add_cap( 'edit_published_' . $course_cap );
		$teacher->add_cap( 'edit_' . $course_cap );
		$teacher->add_cap( 'delete_' . $course_cap );
		$teacher->add_cap( 'unfiltered_html' );

		$settings->get( 'required_review' );

		if ( $settings->get( 'required_review' ) == 'yes' ) {
			$teacher->remove_cap( 'publish_' . $course_cap );
		} else {
			$teacher->add_cap( 'publish_' . $course_cap );
		}

		$teacher->add_cap( 'delete_published_' . $lesson_cap );
		$teacher->add_cap( 'edit_published_' . $lesson_cap );
		$teacher->add_cap( 'edit_' . $lesson_cap );
		$teacher->add_cap( 'delete_' . $lesson_cap );
		$teacher->add_cap( 'publish_' . $lesson_cap );
		$teacher->add_cap( 'upload_files' );
		$teacher->add_cap( 'read' );
		$teacher->add_cap( 'edit_posts' );
	}

	// administrator
	if ( $admin = get_role( 'administrator' ) ) {
		$admin->add_cap( 'delete_' . $course_cap );
		$admin->add_cap( 'delete_published_' . $course_cap );
		$admin->add_cap( 'edit_' . $course_cap );
		$admin->add_cap( 'edit_published_' . $course_cap );
		$admin->add_cap( 'publish_' . $course_cap );
		$admin->add_cap( 'delete_private_' . $course_cap );
		$admin->add_cap( 'edit_private_' . $course_cap );
		$admin->add_cap( 'delete_others_' . $course_cap );
		$admin->add_cap( 'edit_others_' . $course_cap );

		$admin->add_cap( 'delete_' . $lesson_cap );
		$admin->add_cap( 'delete_published_' . $lesson_cap );
		$admin->add_cap( 'edit_' . $lesson_cap );
		$admin->add_cap( 'edit_published_' . $lesson_cap );
		$admin->add_cap( 'publish_' . $lesson_cap );
		$admin->add_cap( 'delete_private_' . $lesson_cap );
		$admin->add_cap( 'edit_private_' . $lesson_cap );
		$admin->add_cap( 'delete_others_' . $lesson_cap );
		$admin->add_cap( 'edit_others_' . $lesson_cap );

		$admin->add_cap( 'delete_' . $order_cap );
		$admin->add_cap( 'delete_published_' . $order_cap );
		$admin->add_cap( 'edit_' . $order_cap );
		$admin->add_cap( 'edit_published_' . $order_cap );
		$admin->add_cap( 'publish_' . $order_cap );
		$admin->add_cap( 'delete_private_' . $order_cap );
		$admin->add_cap( 'edit_private_' . $order_cap );
		$admin->add_cap( 'delete_others_' . $order_cap );
		$admin->add_cap( 'edit_others_' . $order_cap );
	}
	*/
}

add_action( 'init', 'cpc_learn_press_add_user_roles' );



/**
* Add a custom link to the end of a specific menu that uses the wp_nav_menu() function
*/
add_filter('wp_nav_menu_items', 'cpc_add_admin_link', 10, 2);
function cpc_add_admin_link($items, $args){
	
	//if person has mentor role, show my students
	$cpc_check_user = wp_get_current_user();
	
	
    if( $args->theme_location == 'primary' ){
        //$items .= '<li><a title="Admin" href="'. esc_url( admin_url() ) .'">' . __( 'Admin' ) . '</a></li>';
		$items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-275 tc-menu-item tc-menu-depth-0 tc-menu-align-left tc-menu-layout-default"><a title="Journal" href="/journal">' . __( 'JOURNAL' ) . '</a></li>';
		
		if ( in_array( 'CPC_MENTOR_ROLE', (array) $cpc_check_user->roles ) ) {
			$items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-275 tc-menu-item tc-menu-depth-0 tc-menu-align-left tc-menu-layout-default"><a title="My Students" href="/my-students">' . __( 'MY STUDENTS' ) . '</a></li>';
		}		
    }
    return $items;
}



/* Not sure we are going to use this page.  Add coach and mentor to user profile area instead.  */
/** Step 2 (from text above). */
//add_action( 'admin_menu', 'cpc_mentors_menu' );

/** Step 1. */
function cpc_mentors_menu() {
	add_submenu_page( 'learnpress', 'CPC Mentors', 'CPC Mentors', 'manage_options', 'cpc_mentors', 'cpc_mentors_information' );
}

/** Step 3. */
function cpc_mentors_information() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}





add_action( 'show_user_profile', 'cpc_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'cpc_show_extra_profile_fields' );

function cpc_show_extra_profile_fields( $user ) { ?>

<?php
		if ( !current_user_can( 'edit_users' )) {
			add_filter( 'wp_dropdown_users', 'filter_wp_dropdown_users_to_disable' );
		}
?>

	<h2>Cultivate Information</h2>

	<table class="form-table">

		<tr>
			<th><label for="has_coach">Coach</label></th>

			<td>
				<!-- <input type="text" name="has_coach" id="has_coach" value="<?php echo esc_attr( get_the_author_meta( 'has_coach', $user->ID ) ); ?>" class="regular-text" /><br /> -->
				<?php wp_dropdown_users( array('selected' => esc_attr( get_the_author_meta( 'has_coach', $user->ID ) ), 'name' => "has_coach", 'id' => "has_coach", 'include_selected' => true, 'show_option_none' => " ", ) ); ?>
				<br /><span class="description">Assigned Coach</span>
			</td>
		</tr>
		<tr>
			<th><label for="has_coach">Champion</label></th>

			<td>
				<!-- <input type="text" name="has_champion" id="has_champion" value="<?php echo esc_attr( get_the_author_meta( 'has_champion', $user->ID ) ); ?>" class="regular-text" /><br /> -->
				<?php wp_dropdown_users( array('selected' => esc_attr( get_the_author_meta( 'has_champion', $user->ID ) ), 'name' => "has_champion", 'id' => "has_champion", 'include_selected' => true, 'show_option_none' => " ", ) ); ?>
				<br /><span class="description">Assigned Champion</span>
			</td>
		</tr>		

	</table>
<?php 
	remove_filter( 'wp_dropdown_users', 'filter_wp_dropdown_users_to_disable' );
}



//add_action( 'personal_options_update', 'cpc_save_extra_profile_fields' ); //leave this off -- we do not want users to be able to update their own coach/champion
add_action( 'edit_user_profile_update', 'cpc_save_extra_profile_fields' );

function cpc_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'has_coach', $_POST['has_coach'] );
	update_usermeta( $user_id, 'has_champion', $_POST['has_champion'] );
}


function filter_wp_dropdown_users_to_disable( $output ) {
	return str_replace( '<select ', '<select disabled="disabled" ', $output );
}




?>