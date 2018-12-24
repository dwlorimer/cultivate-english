<?php
/**
 * Plugin Name: Thim Education Pack
 * Plugin URI: http://thimpress.com
 * Description: Features & Widgets for "Education Pack" theme by ThimPress
 * Author: ThimPress
 * Author URI: http://thimpress.com
 * Version: 1.0.0
 * Text Domain: thim-education-pack
 */

/**
 * Created by PhpStorm.
 * User: khoapq
 * Date: 7/3/2017
 * Time: 2:20 PM
 */

/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! class_exists( 'Thim_Education_Pack' ) ) {
	class Thim_Education_Pack {

		function __construct() {

			if ( ! defined( 'THIM_EP_PATH' ) ) {
				define( 'THIM_EP_PATH', plugin_dir_path( __FILE__ ) );
			}
			if ( ! is_plugin_active( 'thim-education-pack/thim-education-pack.php' ) ) {
				define( 'THIM_EP_URL', trailingslashit( get_template_directory_uri() . '/thim-education-pack/' ) );
			} else {
				define( 'THIM_EP_URL', plugin_dir_url( __FILE__ ) );
			}

			add_filter( 'user_contactmethods', array( $this, 'modify_contact_methods' ) );

			$this->widgets();
			$this->includes();
		}


		/**
		 * Register shortcodes.
		 *
		 * @since 1.0.0
		 */
		public function widgets() {
			if ( is_plugin_active( 'thim-core/thim-core.php' ) ) {
				require_once( THIM_EP_PATH . 'widgets/widgets-attributes.php' );
				require_once( THIM_EP_PATH . 'widgets/widgets.php' );
			}
		}


		/**
		 * Includes
		 */
		public function includes() {
			if ( is_plugin_active( 'thim-core/thim-core.php' ) ) {
				require_once( THIM_EP_PATH . 'inc/admin/customizer-sections/general-features.php' );
				require_once( THIM_EP_PATH . 'inc/theme-features.php' );
			}
		}

		/**
		 * Add field to user profile
		 *
		 * @param $user_contact_method
		 *
		 * @return mixed
		 */
		public function modify_contact_methods( $user_contact_method ) {

			//Add Facebook
			$user_contact_method['facebook'] = 'Facebook';
			// Add Twitter
			$user_contact_method['twitter'] = 'Twitter';
			// Add Twitter
			$user_contact_method['skype'] = 'Skype';
			//Add Facebook
			$user_contact_method['pinterest'] = 'Pinterest';

			return $user_contact_method;
		}
	}

	$Thim_Education_Pack = new Thim_Education_Pack();
}