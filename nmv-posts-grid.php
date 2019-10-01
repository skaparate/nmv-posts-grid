<?php
/**
 * Plugin Name:       Posts Grid
 * Author:            Nicolas Mancilla <info@nicomv.com>
 * Author URL:        https://nicomv.com
 * Description:       Displays a set of posts from a specific category as a grid.
 * Version:           0.0.3
 * Author:            nicomv.com
 * Author URI:        http://nicomv.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nmv-postsgrid
 * Domain Path:       /languages
 *
 * @package nicomv/postsgrid
 */

namespace Nicomv\PostsGrid;

 if ( ! defined( 'ABSPATH' ) ) {
   exit;
 }

define( 'NMV_POSTSGRID', plugin_dir_path( __FILE__ ) );
define( 'NMV_POSTSGRID_URL', plugins_url( '/', __FILE__ ) );
// TODO: fix the way in which the nonce is generated for ajax requests.
define( 'NMV_POSTSGRID_NONCE', '89pcvxlg21' );
define( 'NMV_POSTSGRID_VERSION', '0.0.4' );

/**
 * Executes the plugin.
 */
function nmv_postsgrid_run() {
  require_once NMV_POSTSGRID . 'includes/class-core.php';
  $core = \Nicomv\PostsGrid\Includes\Core::get_instance();
  $core->run();
}

nmv_postsgrid_run();
