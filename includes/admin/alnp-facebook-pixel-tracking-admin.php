<?php
/**
 * Auto Load Next Post: Pixel Tracking - Admin.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  Auto Load Next Post: Pixel Tracking/Admin
 * @license  GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ALNP_FB_Pixel_Tracking_Admin' ) ) {

	class ALNP_FB_Pixel_Tracking_Admin {

		/**
		 * Constructor
		 *
		 * @access public
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'includes' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta'), 10, 3 );
		}

		/**
		 * Include any classes we need within admin.
		 *
		 * @access public
		 */
		public function includes() {
			include( dirname( __FILE__ ) . '/class-alnp-facebook-pixel-tracking-check.php' );
			include( dirname( __FILE__ ) . '/class-alnp-facebook-pixel-tracking-feedback.php' );
		}

		/**
		 * Plugin row meta links
		 *
		 * @access public
		 * @param  array  $links Plugin Row Meta
		 * @param  string $file  Plugin Base file
		 * @param  array  $data  Plugin Information
		 * @return array  $links
		 */
		public function plugin_row_meta( $links, $file, $data ) {
			if ( $file == plugin_basename( ALNP_FB_PIXEL_PLUGIN_FILE ) ) {
				$links[ 1 ] = sprintf( __( 'Developed By %s', 'alnp-facebook-pixel-tracking' ), '<a href="' . $data[ 'AuthorURI' ] . '" aria-label="' . esc_attr__( 'View the Developers Site', 'alnp-facebook-pixel-tracking' ) . '">' . $data[ 'Author' ] . '</a>' );

				$row_meta = array(
					'community' => '<a href="' . esc_url( ALNP_FB_PIXEL_SUPPORT_URL ) . '" aria-label="' . esc_attr__( 'Get Support from the Community', 'alnp-facebook-pixel-tracking' ). '" target="_blank">' . esc_attr__( 'Community Support', 'alnp-facebook-pixel-tracking' ) . '</a>',
					'review' => '<a href="' . esc_url( ALNP_FB_PIXEL_REVIEW_URL ) . '" aria-label="' . esc_attr( __( 'Review Pixel Tracking for Auto Load Next Post on WordPress.org', 'alnp-facebook-pixel-tracking' ) ) . '" target="_blank">' . __( 'Leave a Review', 'alnp-facebook-pixel-tracking' ) . '</a>',
				);

				$links = array_merge( $links, $row_meta );
			}

			return $links;
		}

	}

}

return new ALNP_FB_Pixel_Tracking_Admin();
