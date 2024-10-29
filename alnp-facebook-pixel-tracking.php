<?php
/*
 * Plugin Name: Auto Load Next Post: Pixel Tracking
 * Plugin URI: https://wordpress.org/plugins/alnp-facebook-pixel-tracking/
 * Description: Track your page views using Facebook Pixel with Auto Load Next Post.
 * Author: Auto Load Next Post
 * Author URI: https://autoloadnextpost.com
 * Version: 2.0.1
 * Developer: Sébastien Dumont
 * Developer URI: https://sebastiendumont.com
 * Text Domain: alnp-facebook-pixel-tracking
 * Domain Path: /languages/
 *
 * Auto Load Next Post: Pixel Tracking is free software: you 
 * can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software 
 * Foundation, either version 2 of the License, or any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with Auto Load Next Post: Pixel Tracking.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Auto Load Next Post: Pixel Tracking
 * @author  Auto Load Next Post
 * @link    https://autoloadnextpost.com
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! class_exists( 'ALNP_FB_Pixel_Tracking' ) ) {
	class ALNP_FB_Pixel_Tracking {

		/**
		 * @var ALNP_FB_Pixel_Tracking - The single instance of the class.
		 *
		 * @access protected
		 * @static
		 * @since  1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Plugin Version
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 */
		public static $version = '2.0.1';

		/**
		 * Required Auto Load Next Post Version
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 */
		public static $required_alnp = '1.4.10';

		/**
		 * Main ALNP_FB_Pixel_Tracking Instance.
		 *
		 * Ensures only one instance of ALNP_FB_Pixel_Tracking is loaded or can be loaded.
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 * @see    ALNP_FB_Pixel_Tracking()
		 * @return ALNP_FB_Pixel_Tracking - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, __( 'Cloning this object is forbidden.', 'alnp-facebook-pixel-tracking' ), self::$version );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'alnp-facebook-pixel-tracking' ), self::$version );
		}

		/**
		 * The Constructor.
		 * 
		 * @access  public
		 * @since   1.0.0
		 * @version 2.0.0
		 */
		public function __construct() {
			$this->constants();
			$this->includes();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'alnp_enqueue_scripts' ) );
		}

		/**
		 * Setup plugin constants.
		 *
		 * @access private
		 * @since  2.0.0
		 * @return void
		 */
		private function constants() {
			$this->define( 'ALNP_FB_PIXEL_VERSION', self::$version );
			$this->define( 'ALNP_FB_PIXEL_ALNP_REQUIRED', self::$required_alnp );
			$this->define( 'ALNP_FB_PIXEL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'ALNP_FB_PIXEL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'ALNP_FB_PIXEL_PLUGIN_FILE', __FILE__ );
			$this->define( 'ALNP_FB_PIXEL_PLUGIN_BASE', plugin_basename( __FILE__ ) );
			$this->define( 'ALNP_FB_PIXEL_SUPPORT_URL', 'https://wordpress.org/support/plugin/alnp-facebook-pixel-tracking' );
			$this->define( 'ALNP_FB_PIXEL_REVIEW_URL', 'https://wordpress.org/support/plugin/alnp-facebook-pixel-tracking/reviews/' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @access private
		 * @since  2.0.0
		 * @param  string|string $name Name of the definition.
		 * @param  string|bool   $value Default value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @since  2.0.0
		 * @return void
		 */
		private function includes() {
			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
				require_once( ALNP_FB_PIXEL_PLUGIN_DIR . 'includes/admin/alnp-facebook-pixel-tracking-admin.php' );
			}
		}

		/**
		 * Load the plugin text domain once the plugin has initialized.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 2.0.0 
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'alnp-facebook-pixel-tracking', false, dirname( plugin_basename( ALNP_FB_PIXEL_PLUGIN_DIR ) ) . '/languages/' );
		}

		/**
		 * Load JS only on the front end for a single post.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function alnp_enqueue_scripts() {
			if ( is_singular() && in_array( get_post_type(), $this->allowed_post_types() ) ) {
				wp_register_script( 'alnp-facebook-pixel-tracking', ALNP_FB_PIXEL_PLUGIN_URL . '/assets/js/alnp-facebook-pixel-tracking.js', array( 'jquery' ), self::$version );
				wp_enqueue_script( 'alnp-facebook-pixel-tracking' );

				wp_localize_script( 'alnp-facebook-pixel-tracking', 'alnp_fb_pixel', array(
					'alnpVersion'   => AUTO_LOAD_NEXT_POST_VERSION,
					'pluginVersion' => self::$version,
				));
			}
		}

		/**
		 * Returns allowed post types to track page views.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return array
		 */
		public function allowed_post_types() {
			return array( 'post' );
		}

	} // END class

} // END if class exists

return ALNP_FB_Pixel_Tracking::instance();
