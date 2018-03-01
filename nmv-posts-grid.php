<?php
namespace Nicomv\Posts\Grid;

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
 */

 if ( ! defined( 'ABSPATH') ) {
   exit;
 }

define( 'NMV_POSTSGRID', plugin_dir_path( __FILE__ ) );
define( 'NMV_POSTSGRID_URL', plugins_url( './', __FILE__ ) );
define( 'NMV_POSTSGRID_NONCE', '89pcvxlg21' );
define( 'NMV_POSTSGRID_VERSION', '0.0.3' );

function run() {
  require_once NMV_POSTSGRID . 'includes/Core.php';
  $core = new \Nicomv\Posts\Grid\Includes\Core;
  $core->run();
}
run();