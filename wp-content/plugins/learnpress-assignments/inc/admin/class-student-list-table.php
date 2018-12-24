<?php
/**
 * Class LP_Student_Assignment_List_Table.
 *
 * @author  ThimPress
 * @package LearnPress/Assignments/Classes
 * @version 3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// WP_List_Table is not loaded automatically so we need to load it in our application
if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . '/wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'LP_Student_Assignment_List_Table' ) ) {
	/**
	 * Class LP_Student_Assignment_List_Table
	 */
	class LP_Student_Assignment_List_Table extends WP_List_Table {

		/**
		 * @var LP_Assignment
		 */
		protected $assignment = null;

		/**
		 * @var int
		 */
		public $per_page = 10;

		/**
		 * LP_Student_Assignment_List_Table constructor.
		 *
		 * @param $assignment_id
		 */
		public function __construct( $assignment_id ) {
			parent::__construct();

			$this->assignment = learn_press_get_assignment( $assignment_id );
			$this->per_page   = $this->get_items_per_page( 'users_per_page', $this->per_page );
			$this->prepare_items();
		}

		/**
		 * @return array
		 */
		public function get_columns() {

			$url = learn_press_assignment_students_url( $this->assignment->get_id() );

			return array(
				'id'         => __( 'ID', 'learnpress-assignments' ),
				'name'       => __( 'Name', 'learnpress-assignments' ),
				'email'      => __( 'Email', 'learnpress-assignments' ),
				'status'     => wp_kses( sprintf( __( '<a href="%s">Status</a>', 'learnpress-assignments' ), esc_url( $url ) ), array( 'a' => array( 'href' => array() ) ) ),
				'instructor' => wp_kses( sprintf( __( '<a href="%s">Instructor</a>', 'learnpress-assignments' ), esc_url( $url ) ), array( 'a' => array( 'href' => array() ) ) ),
				'mark'       => __( 'Mark', 'learnpress-assignments' ),
				'result'     => wp_kses( sprintf( __( '<a href="%s">Result</a>', 'learnpress-assignments' ), esc_url( $url ) ), array( 'a' => array( 'href' => array() ) ) ),
				'actions'    => __( 'Actions', 'learnpress-assignments' ),
			);
		}

		/**
		 * @param object $item
		 */
		public function column_cb( $item ) {
			echo '<input type="checkbox" name="items[]" value="' . $item . '">';
		}

		/**
		 * Prepare items.
		 */
		public function prepare_items() {
			$columns               = $this->get_columns();
			$hidden                = array();
			$sortable              = array();
			$this->_column_headers = array( $columns, $hidden, $sortable );
			$this->items           = $this->get_items();
		}

		/**
		 * Get items.
		 *
		 * @return array
		 */
		private function get_items() {
			$items    = array();
			$students = $this->get_students_list();
			$total    = count( $students );

			$this->set_pagination_args(
				array(
					'total_items' => $total,
					'per_page'    => $this->per_page
				)
			);

			for ( $i = 0; $i < $total; $i ++ ) {
				$user = learn_press_get_user( $students[ $i ]['ID'] );

				$items[] = array(
					'user'       => $user,
					'assignment' => $this->assignment
				);
			}

			return $items;
		}

		/**
		 * @return array|null|object
		 */
		private function get_students_list() {
			global $wpdb;


			$paged             = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;
			$limit_start       = ( $paged - 1 ) * $this->per_page;
			$assignment_id     = $this->assignment->get_id();
			$search            = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : '';
			$filter_status     = ( isset( $_REQUEST['filter_status'] ) ) ? $_REQUEST['filter_status'] : '';
			$filter_instructor = ( isset( $_REQUEST['filter_instructor'] ) ) ? $_REQUEST['filter_instructor'] : null;
			$filter_result     = ( isset( $_REQUEST['filter_result'] ) ) ? $_REQUEST['filter_result'] : null;

			$sql = "SELECT DISTINCT student.* FROM {$wpdb->users} AS student
				INNER JOIN {$wpdb->prefix}learnpress_user_items AS user_item  ON user_item.user_id = student.ID ";

			if ( ! is_null( $filter_instructor ) || ! is_null( $filter_result ) ) {
				$sql .= "LEFT JOIN {$wpdb->prefix}learnpress_user_itemmeta AS user_itemmeta  ON user_item.user_item_id = user_itemmeta.learnpress_user_item_id ";
			}

			$sql .= "WHERE user_item.item_id = $assignment_id AND user_item.item_type = 'lp_assignment'";

			if ( $search ) {
				$sql .= " AND (student.user_login LIKE '%%{$search}%%' OR student.user_email LIKE '%%{$search}%%')";
			}

			if ( ! $filter_status ) {
				$sql .= "AND user_item.status IN ('completed', 'evaluated')";
			} else {
				$sql .= "AND user_item.status = '$filter_status'";
			}

			if ( ! is_null( $filter_instructor ) ) {
				$sql .= " AND user_itemmeta.meta_key = '_lp_assignment_evaluate_author' AND user_itemmeta.meta_value = $filter_instructor";
			}

			if ( ! is_null( $filter_result ) ) {
				if ( $filter_result ) {
					$sql .= " AND user_itemmeta.meta_key = 'grade' AND user_itemmeta.meta_value = '$filter_result'";
				}
			}

			$sql .= " LIMIT %d, %d";

			$query    = $wpdb->prepare( $sql, $limit_start, $this->per_page );
			$students = $wpdb->get_results( $query, ARRAY_A );

			return $students;
		}

		/**
		 * @param string $which
		 */
		protected function extra_tablenav( $which ) {
			if ( $which != 'top' ) {
				return;
			} ?>
            <div class="alignleft actions">
                <input type="search" id="post-search-input" placeholder="" name="s" value="<?php _admin_search_query(); ?>">
                <input type="submit" id="search-submit" class="button"
                       value="<?php esc_attr_e( 'Search student', 'learnpress-assignments' ); ?>">
            </div>
			<?php
		}

		/**
		 * @param object $item
		 * @param string $column_name
		 */
		public function column_default( $item, $column_name ) {
			/**
			 * @var $user LP_User
			 */
			$user = $item['user'];

			/**
			 * @var $lp_assignment LP_Assignment
			 */
			$lp_assignment = $item['assignment'];
			$assignment_id = $lp_assignment->get_id();

			$course    = learn_press_get_item_courses( $assignment_id );
			$lp_course = learn_press_get_course( $course[0]->ID );

			$course_data = $user->get_course_data( $lp_course->get_id() );

			$user_item_id = $course_data->get_item( $assignment_id )->get_user_item_id();
			$mark         = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_mark', true );
			$instructor   = learn_press_get_user_item_meta( $user_item_id, '_lp_assignment_evaluate_author', true );
			$evaluated    = $user->has_item_status( array( 'evaluated' ), $assignment_id, $lp_course->get_id() );

			switch ( $column_name ) {
				case 'id':
					echo $user->get_id();
					break;
				case 'name':
					echo $user->get_data( 'user_login' );
					break;
				case 'email':
					echo $user->get_data( 'email' );
					break;
				case 'status': ?>
					<?php $status = $evaluated ? __( 'Evaluated', 'learnpress-assignments' ) : __( 'Not evaluate', 'learnpress-assignments' ); ?>
                    <a href="<?php echo esc_url( add_query_arg( array( 'filter_status' => $evaluated ? 'evaluated' : 'completed' ) ) ); ?>"><?php echo $status; ?></a>
					<?php break;
				case 'instructor':
					$user = get_user_by( 'id', $instructor );
					$name = $user ? $user->user_login : '-'; ?>
                    <a href="<?php echo esc_url( add_query_arg( array( 'filter_instructor' => $user ? $instructor : 0 ) ) ); ?>"><?php echo $name; ?></a>
					<?php
					break;
				case 'mark':
					echo $mark ? $mark : '-';
					break;
				case 'result':
					if ( ! $evaluated ) {
						echo '-';
					} else {
						$pass   = $mark >= $lp_assignment->get_data( 'passing_grade' );
						$result = $pass ? __( 'Passed', 'learnpress-assignments' ) : __( 'Failed', 'learnpress-assignments' ); ?>
                        <a href="<?php echo esc_url( add_query_arg( array( 'filter_result' => $pass ? 'passed' : 'failed' ) ) ); ?>"><?php echo $result; ?></a>
						<?php
					}
					break;
				case 'actions': ?>
                    <div class="assignment-students-actions" data-user_id="<?php echo esc_attr( $user->get_id() ); ?>"
                         data-assignment_id="<?php echo esc_attr( $lp_assignment->get_id() ); ?>"
                         data-user-item-id="<?php echo esc_attr( $user_item_id ); ?>">
						<?php
						printf( '<a href="%s" class="view" title="%s"><i class="dashicons dashicons-welcome-write-blog"></i></a>', learn_press_assignment_evaluate_url( array( 'user_id' => $user->get_id() ) ), esc_attr__( 'Evaluate', 'learnpress-assignments' ) );
						printf( '<a href="%s" class="delete" title="%s"><i class="dashicons dashicons-trash"></i></a>', '#', esc_attr__( 'Delete submission', 'learnpress-assignments' ) );
						if ( $evaluated ) {
							printf( '<a href="%s" class="reset" title="%s"><i class="dashicons dashicons-update"></i></a>', '#', esc_attr__( 'Reset result', 'learnpress-assignments' ) );
							printf( '<a href="%s" class="send-mail" title="%s"><i class="dashicons dashicons-email-alt"></i></a>', '#', esc_attr__( 'Send evaluated mail', 'learnpress-assignments' ) );
						}
						?>
                    </div>
					<?php break;
				default:
					break;
			}
		}
	}
}