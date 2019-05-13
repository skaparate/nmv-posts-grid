<?php

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
 * @package 	 Nicomv\PostsGrid
 * @subpackage Nicomv\PostsGrid\Includes
 * @author     skaparate <info@nicomv.com>
 */

class Core
{
  
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      ActionLoader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    
    public function __construct()
    {
        $this->autoLoad();
        $this->loadTextdomain();
        add_action( 'init', array( $this, 'registerStyles' ));
        add_action( 'init', array( $this, 'registerScripts' ));
        $this->registerShortcodes();
    }
    
    private function autoLoad()
    {
        require_once NMV_POSTSGRID . 'includes/AutoLoader.php';
        $loader = new \Nicomv\PostsGrid\Includes\AutoLoader;
        $loader->register();
        $loader->addNamespace('\Nicomv\PostsGrid', NMV_POSTSGRID);
        $loader->addNamespace('\Nicomv\PostsGrid\Includes', NMV_POSTSGRID . 'includes');
        $loader->addNamespace('\Nicomv\PostsGrid\I18n', NMV_POSTSGRID . 'i18n');
        $loader->addNamespace('\Nicomv\PostsGrid\Shortcodes', NMV_POSTSGRID . 'shortcodes');
        $loader->addNamespace('\Nicomv\PostsGrid\Utils', NMV_POSTSGRID . 'utils');
        $this->loader = new ActionLoader;
    }
  
    public function run()
    {
        $this->loader->run();
    }
    
    private function defineAdminActions()
    {
        $ajax_handler = new \Nicomv\PostsGrid\Utils\AjaxHandler;
        $this->loader->addAction('wp_ajax_query_post_content', $ajax_handler, 'queryPost');
        $this->loader->addAction('wp_ajax_nopriv_query_post_content', $ajax_handler, 'queryPost');
    }
  
    public function registerStyles()
    {
        wp_register_style('nmv-pg-slick', NMV_POSTSGRID_URL . 'assets/js/slick/slick.css');
        wp_register_style('nmv-pg-slick-theme', NMV_POSTSGRID_URL . 'assets/js/slick/slick-theme.css');
    }
  
    public function registerScripts()
    {
        $js_url = NMV_POSTSGRID_URL . 'assets/js';
        Logger::log('Registering scripts in: ' . $js_url);
        $r = wp_register_script('nmv-pg-slick', $js_url . '/slick/slick.min.js', array( 'jquery' ), '1.0', true);
        if ($r === false) {
            Logger::log('Failed to register nmv-pg-slick script');
        }

        $r = wp_register_script('nmv-pg-gallery', $js_url . '/grid-gallery.js', array( 'jquery' ), '1.0', true);

        if ($r === false) {
            Logger::log('Failed to register nmv-pg-gallery script');
        }

        $r = wp_register_script('nmv-pg-load-images', $js_url . '/load-images.min.js', array( 'jquery' ), '4.1.4', true);

        if ($r === false) {
            Logger::log('Failed to register nmv-pg-load-images');
        }

        $r = wp_register_script('nmv-pg-masonry', $js_url . '/masonry.min.js', array( 'jquery', 'nmv-pg-load-images' ), '4.2.2', true);

        if ($r === false) {
            Logger::log('Failed to register nmv-pg-masonry script');
        }
        
        $r = wp_register_script('nmv-pg-masonry-setup', $js_url . '/masonry-setup.js', array('nmv-pg-masonry'), '1.0.0', true);

        if ($r === false) {
            Logger::log('Failed to register nmv-pg-masonry-setup script');
        }
    }
  
    private function loadTextdomain()
    {
        $i18n = new \Nicomv\PostsGrid\I18n\I18n;
        $this->loader->addAction('plugins_loaded', $i18n, 'loadTextdomain');
    }
    
    private function registerShortcodes()
    {
        $posts_grid = new \Nicomv\PostsGrid\Shortcodes\PostsGridShortcode;
        $this->loader->addShortcode('nmv_posts_grid', $posts_grid, 'doShortcode');
    }
}
