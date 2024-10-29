<?php
/**
 * Admin View: Check if Auto Load Next Post is not installed notice.
 *
 * @since    2.0.0
 * @author   Auto Load Next Post
 * @category Admin
 * @package  Auto Load Next Post/Admin/Views
 * @license  GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="notice notice-error">
    <p><?php echo sprintf( __( '%1$s requires %2$sAuto Load Next Post%3$s. Install and activate Auto Load Next Post version %4$s or higher.', 'alnp-facebook-pixel-tracking' ), esc_html( 'Pixel Tracking for Auto Load Next Post', 'alnp-facebook-pixel-tracking' ), '<strong>', '</strong>', ALNP_FB_PIXEL_ALNP_REQUIRED ); ?></p>

    <p>
    <?php
    if ( ! is_plugin_active( 'auto-load-next-post/auto-load-next-post.php' ) && current_user_can( 'activate_plugin', 'auto-load-next-post/auto-load-next-post.php' ) ) :

        echo '<a href="' . esc_url( wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=auto-load-next-post/auto-load-next-post.php&plugin_status=active' ), 'activate-plugin_auto-load-next-post/auto-load-next-post.php' ) ) . '" class="button button-primary">' . esc_html__( 'Activate Auto Load Next Post', 'alnp-facebook-pixel-tracking' ) . '</a>';

    else:

        if ( current_user_can( 'install_plugins' ) ) {
            $url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=auto-load-next-post' ), 'install-plugin_auto-load-next-post' );
        } else {
            $url = 'https://wordpress.org/plugins/auto-load-next-post/';
        }

        echo '<a href="' . esc_url( $url ) . '" class="button button-primary">' . esc_html__( 'Install Auto Load Next Post', 'alnp-facebook-pixel-tracking' ) . '</a>';

    endif;
    ?>
    </p>

</div>