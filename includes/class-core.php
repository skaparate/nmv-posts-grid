<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.1
 * @package      Nicomv\PostsGrid
 * @subpackage   Nicomv\PostsGrid\Includes
 */

namespace Nicomv\PostsGrid\Includes;

use Nicomv\PostsGrid\Utils\Logger;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.1
 * @package      Nicomv\PostsGrid
 * @subpackage   Nicomv\PostsGrid\Includes
 */
class Core {


	/**
	 * Singleton instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Nicomv\PostsGrid\Core
	 */
	private static $instance = null;

	/**
	 * Class used to register and auto load other classes.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Nicomv\PostsGrid\Includes\Auto_Loader
	 */
	private $auto_loader;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nicomv\PostsGrid\Includes\Action_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Creates an instance of this class.
	 */
	private function __construct() {
	}

	/**
	 * Retrieves the singleton instance of this class.
	 *
	 * @return Nicomv\PostsGrid\Core.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Setup the auto load classes.
	 */
	private function auto_load() {
		require_once NMV_POSTSGRID . 'includes/class-auto-loader.php';

		$loader = new Auto_Loader( '\Nicomv\PostsGrid', NMV_POSTSGRID );
		$loader->register();
		$loader->add_namespace( '', '' );
		$loader->add_namespace( '\Includes', 'includes' );
		$loader->add_namespace( '\I18n', 'i18n' );
		$loader->add_namespace( '\Shortcodes', 'shortcodes' );
		$loader->add_namespace( '\Utils', 'utils' );
		$this->auto_loader = $loader;
		$this->loader = new ActionLoader();
	}

	/**
	 * Runs the plugin.
	 */
	public function run() {
		$this->auto_load();
		$this->load_text_domain();
		add_action( 'init', array( $this, 'register_styles' ) );
		add_action( 'init', array( $this, 'register_scripts' ) );
		$this->register_shortcodes();
		$this->loader->run();
	}

	/**
	 * Defines administration actions for ajax requests.
	 */
	private function define_admin_actions() {
		$ajax_handler = new \Nicomv\PostsGrid\Utils\AjaxHandler();
		$this->loader->add_action( 'wp_ajax_query_post_content', $ajax_handler, 'query_post' );
		$this->loader->add_action( 'wp_ajax_nopriv_query_post_content', $ajax_handler, 'query_post' );
	}

	/**
	 * Register plugin styles.
	 */
	public function register_styles() {
		wp_register_style( 'nmv-pg-slick', NMV_POSTSGRID_URL . 'assets/js/slick/slick.css', null, '1.8.0' );
		wp_register_style( 'nmv-pg-slick-theme', NMV_POSTSGRID_URL . 'assets/js/slick/slick-theme.css', null, '1.8.0' );
	}

	/**
	 * Register plugin scripts.
	 */
	public function register_scripts() {
		$js_url = NMV_POSTSGRID_URL . 'assets/js';
		Logger::log( 'Registering scripts in: ' . $js_url );
		$r = wp_register_script( 'slick', "$js_url/slick/slick.min.js", array( 'jquery' ), '1.8.0', true );
		if ( false === $r ) {
			Logger::log( '[nmv-postsgrid] Failed to register slick script' );
		}

		$r = wp_register_script( 'nmv-pg-gallery', "$js_url/grid-gallery.js", array( 'jquery' ), '1.0', true );

		if ( false === $r ) {
			Logger::log( '[nmv-postsgrid] Failed to register gallery script' );
		}

		$r = wp_register_script( 'images-loaded', "$js_url/images-loaded.min.js", array(), '4.1.4', true );

		if ( false === $r ) {
			Logger::log( '[nmv-postsgrid] Failed to register images-loaded' );
		}

		wp_deregister_script( 'masonry' );
		$r = wp_register_script( 'masonry', "$js_url/masonry.min.js", array( 'images-loaded' ), '4.2.2', true );

		if ( false === $r ) {
			Logger::log( '[nmv-postsgrid] Failed to register masonry script' );
		}

		$r = wp_register_script( 'nmv-pg-masonry-setup', "$js_url/masonry-setup.js", array( 'jquery', 'masonry' ), '1.0.0', true );

		if ( false === $r ) {
			Logger::log( '[nmv-postsgrid] Failed to register masonry-setup script' );
		}
	}

	/**
	 * Loads the plugin text doamin.
	 */
	private function load_text_domain() {
		$i18n = new \Nicomv\PostsGrid\I18n\I18n();
		$this->loader->add_action( 'plugins_loaded', $i18n, 'load_text_domain' );
	}

	/**
	 * Register short codes.
	 */
	private function register_shortcodes() {
		$posts_grid = new \Nicomv\PostsGrid\Shortcodes\PostsGridShortcode();
		$this->loader->add_shortcode( 'nmv_posts_grid', $posts_grid, 'do_shortcode' );
	}
}
