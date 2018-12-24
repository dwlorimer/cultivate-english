<?php
/**
 * Question fill in blank question class.
 *
 * @author   ThimPress
 * @package  LearnPress/Fill-In-Blank/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Question_Fill_In_Blank' ) ) {

	/**
	 * Class LP_Question_Fill_In_Blank
	 */
	class LP_Question_Fill_In_Blank extends LP_Question {

		/**
		 * @var string
		 */
		protected $_question_type = 'fill_in_blank';

		/**
		 * @var string
		 */
		static $_shortcode_pattern = '!\[fib(.*)fill=["|\'](.*)["|\']!iSU';

		/**
		 * Do not support answer options
		 *
		 * @var bool
		 */
		protected $_answer_options = false;

		/**
		 * @var array
		 */
		protected $_answer = false;

		/**
		 * @var bool
		 */
		public $_blanks = array();

		/**
		 * LP_Question_Fill_In_Blank constructor.
		 *
		 * @param null $the_question
		 * @param null $args
		 *
		 * @throws Exception
		 */
		public function __construct( $the_question = null, $args = null ) {
			parent::__construct( $the_question, $args );
			
			# make sure that shortcode is not exists before add it
			if( !shortcode_exists( 'fib' ) ){
                add_shortcode( 'fib', array( $this, 'add_shortcode' ) );
            }
			if ( $answers = $this->get_answers() ) {
				foreach ( $answers as $answer ) {
					if ( $blanks = $answer->get_meta( '_blanks' ) ) {
						$this->_blanks = array();
						foreach ( $blanks as $blank ) {
							$this->_blanks[ $blank['id'] ] = $blank;
						}
					}
					break;
				}
			}
		}

		/**
		 * Get passage.
		 *
		 * @param bool $checked
		 *
		 * @return mixed|null|string|string[]
		 */
		public function get_passage( $checked = false ) {
			$passage = $this->_passage;
			if ( $checked ) {
				$pattern = $this->get_wp_shortcode_regex();
				$passage = preg_replace_callback( $pattern, array( $this, '_replace_callback' ), $passage );
			}
			global $wp_filter;

			if ( ! empty( $wp_filter['wpautop'] ) ) {
				$wpautop = $wp_filter['wpautop'];
				unset( $wp_filter['wpautop'] );
			}
			$passage = do_shortcode( $passage );

			if ( isset( $wpautop ) ) {
				$wp_filter['wpautop'] = $wpautop;
			}

			return preg_replace( '!^<p>|<\/p>$!', '', $passage );
		}

		/**
		 * Replace callback.
		 *
		 * @param $a
		 *
		 * @return string
		 */
		public function _replace_callback( $a ) {
			$user_fill = '';

			$attr = shortcode_parse_atts( $a[3] );

			if ( ! empty( $this->user_answered ) && array_key_exists( 'fill', $attr ) ) {
				settype( $this->user_answered, 'array' );
				$input_name = $this->get_input_name( $attr['fill'] );
				if ( ! empty( $this->user_answered[ $input_name ] ) ) {
					settype( $this->user_answered[ $input_name ], 'array' );
					$user_fill = array_shift( $this->user_answered[ $input_name ] );
				}
			}

			$atts = shortcode_parse_atts( $a[3] );

			return "[fib " . $a[3] . ' correct_fill="' . $atts['fill'] . '" user_fill="' . $user_fill . '"]';
		}

		/**
		 * Get Fill in blank default answers.
		 *
		 * @return array|bool|string
		 */
		public function get_default_answers() {
			$default = array(
				array(
					'is_true' => 'yes',
					'value'   => '',
					'text'    => ''
				)
			);

			return $default;
		}

		/**
		 * Prints the question in frontend user.
		 *
		 * @param bool $args
		 */
		public function render( $args = false ) {
			learn_press_fib_get_template( 'answer.php', array( 'question' => $this ) );
		}

		protected function get_blank_data() {

		}

		/**
		 * Add fill in blank question shortcode.
		 *
		 * @param null $atts
		 *
		 * @return string
		 */
		public function add_shortcode( $atts = null ) {

			$answered = $this->get_answered();

			if ( false === ( $checked = $this->_get_checked( $answered ) ) ) {
				$checked = $this->check( $answered );
			}

			$atts = shortcode_atts(
				array(
					'fill'      => '',
					'uid'       => '',
					'id'        => '',
					'user_fill' => '',
					'correct'   => ''
				), $atts
			);

			$uid = $atts['id'];
			if ( ! empty( $answered[ $uid ] ) ) {
				$atts['user_fill'] = $answered[ $uid ];
				if ( ! empty( $checked['blanks'][ $uid ] ) ) {
					$atts['correct'] = $checked['blanks'][ $uid ];
				} else {

				}
			}


			ob_start();

			global $wp_filter;

			if ( ! empty( $wp_filter['wpautop'] ) ) {
				$wpautop = $wp_filter['wpautop'];
				unset( $wp_filter['wpautop'] );
			}

			learn_press_fib_get_template(
				'blank.php',
				array(
					'question' => $this,
					'answer'   => $this->_answer,
					'blank'    => array_merge( $this->_blanks[ $atts['id'] ], $atts )
				)
			);

			if ( isset( $wpautop ) ) {
				$wp_filter['wpautop'] = $wpautop;
			}

			return ob_get_clean();

			# What's this? why not remove it?
/*
			$input_name   = $this->get_input_name( $atts['fill'] );
			$correct_fill = $this->_format_text( $atts['correct_fill'] );
			$user_fill    = $this->_format_text( $atts['user_fill'] );
			$qid          = $this->_format_text( $atts['qid'] );
			if ( empty( $atts['range'] ) ) {
				$correct = strcasecmp( $user_fill, $correct_fill ) == 0;
			} else {
				$range = explode( ',', $atts['fill'] );
				$range = array_map( 'floatval', $range );
				for ( $i = 0; $i < 2; $i ++ ) {
					if ( ! array_key_exists( $i, $range ) ) {
						$range[ $i ] = '';
					}
				}
				$user_fill_x = floatval( $user_fill );
				$correct     = $user_fill_x >= $range[0] && $user_fill_x <= $range[1];
			}
			$question_id = $qid ? $qid : $this->id;

			return sprintf(
				'<input type="text" name="%s" data-fill="%s" value="%s" %s class="answer-options %s" />%s',
				'learn-press-question-' . $question_id . '',
				esc_attr( $input_name ),
				$correct_fill ? $correct_fill : '',
				$correct_fill ? ' disabled="disabled"' : '',
				$correct ? 'blank-fill-correct' : '',
				$correct_fill ? sprintf( '<span class="check-label %s">%s</span>', $correct ? 'correct' : 'wrong', $user_fill ) : ''
			);
*/
		}

		/**
		 * Get input name.
		 *
		 * @param $fill
		 *
		 * @return string
		 */
		public function get_input_name( $fill ) {
			return '_' . md5( wp_create_nonce( $fill ) );
		}

		/**
		 * Set text format.
		 *
		 * @param $text
		 *
		 * @return string
		 */
		private function _format_text( $text ) {
			return trim( preg_replace( '!\s+!', ' ', $text ) );
		}

		/**
		 * Check user answer.
		 *
		 * @param null $user_answer
		 *
		 * @return mixed
		 */
		public function check( $user_answer = null ) {

			if ( $return = $this->_get_checked( $user_answer ) ) {
				return $return;
			}

			$return = parent::check();
			if ( $this->_blanks && ( $answered = $user_answer ) ) {
				$return['blanks'] = array();
				$point_per_blank  = $this->get_mark() / sizeof( $this->_blanks );
				$correct          = false;
				foreach ( $this->_blanks as $blank ) {
					$uid       = $blank['id'];
					$user_fill = ! empty( $answered[ $uid ] ) ? trim( $answered[ $uid ] ) : false;
					$fill      = trim( $blank['fill'] );

					$comparison    = ! empty( $blank['comparison'] ) ? $blank['comparison'] : false;
					$match_case    = ! empty( $blank['match_case'] ) ? ! ! $blank['match_case'] : false;
					$blank_correct = false;
					switch ( $comparison ) {
						case 'range':
							if ( is_numeric( $user_fill ) ) {
								$fill      = explode( ',', $fill );
								$words     = array_map( 'trim', $fill );
								$words     = array_map( 'floatval', $fill );
								$user_fill = floatval( $user_fill );

								if ( sizeof( $words ) == 2 ) {
									$blank_correct = $words[0] <= $user_fill && $user_fill <= $words[1];
								}
							}
							break;
						case 'any':
							$fill  = explode( ',', $fill );
							$words = array_map( 'trim', $fill );

							if ( ! $match_case ) {
								$words     = array_map( 'strtolower', $words );
								$user_fill = strtolower( $user_fill );
							}

							$blank_correct = in_array( $user_fill, $words );
							break;
						default:
							if ( $match_case ) {
								$blank_correct = strcmp( $user_fill, $blank['fill'] ) == 0;
							} else {
								$blank_correct = strcasecmp( $user_fill, $blank['fill'] ) == 0;
							}
					}

					$return['blanks'][ $uid ] = $blank_correct;
					if ( $blank_correct ) {
						$return['mark'] += $point_per_blank;
						$correct        = true;

					} else {
					    $correct = false;
					    break;
					}
				}

				$return['correct'] = $correct;
				$answered_value = array_values( $answered );
				if( empty( array_filter( $answered_value ) ) ) {
				    $return['answered'] = false;
				}
			}

//			$pattern = $this->get_wp_shortcode_regex();
//
//			if ( preg_match_all( $pattern, $passage, $matches ) ) {
//				settype( $user_answer, 'array' );
//				$input_pos = array();
//				$correct   = true;
//
//				foreach ( $matches[0] as $k => $v ) {
//					$atts = shortcode_parse_atts( $matches[3][ $k ] );
//
//					$input_name = $this->get_input_name( $atts['fill'] );
//
//					$user_fill = '';
//					if ( ! empty( $user_answer[ $input_name ] ) ) {
//						$pos = ! empty( $input_pos[ $input_name ] ) ? $input_pos[ $input_name ] : 1;
//						if ( ! empty( $user_answer[ $input_name ][ $pos - 1 ] ) ) {
//							$user_fill = $user_answer[ $input_name ][ $pos - 1 ];
//						}
//						$input_pos[ $input_name ] = $pos + 1;
//					}
//					$user_fill    = $this->_format_text( $user_fill );
//					$correct_fill = $this->_format_text( $atts['fill'] );
//
//					if ( array_key_exists( 'range', $atts ) && $atts['range'] ) {
//						$range = explode( ',', $atts['fill'] );
//						$range = array_map( 'floatval', $range );
//						for ( $i = 0; $i < 2; $i ++ ) {
//							if ( ! array_key_exists( $i, $range ) ) {
//								$range[ $i ] = '';
//							}
//						}
//						$user_fill = floatval( $user_fill );
//						$correct   = $user_fill >= $range[0] && $user_fill <= $range[1];
//						if ( ! $correct ) {
//							// No need to check anymore because we calculate the mark if all blanks are true
//							$correct = false;
//							break;
//						}
//					} else {
//						if ( strcasecmp( $user_fill, $correct_fill ) != 0 ) {
//							$correct = false;
//							// No need to check anymore because we calculate the mark if all blanks are true
//							break;
//						}
//					}
//				}
//
//				if ( $correct ) {
//					$return = array(
//						'correct' => true,
//						'mark'    => floatval( $this->get_mark() )
//					);
//				}
//			}

			$this->_set_checked( $return, $user_answer );

			return $return;
		}

		/**
		 * Get Wordpress shortcode regex.
		 *
		 * @return string
		 */
		public function get_wp_shortcode_regex() {
			return '/' . get_shortcode_regex( array( 'fib' ) ) . '/';
		}
	}
}