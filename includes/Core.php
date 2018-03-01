<?php

namespace Nicomv\Posts\Grid\Includes;

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
 * @package 	 Nicomv\Posts\Grid
 * @subpackage Nicomv\Posts\Grid\Includes
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
        $this->registerStyles();
        $this->registerScripts();
        $this->registerShortcodes();
    }
    
    private function autoLoad()
    {
        require_once NMV_POSTSGRID . 'includes/AutoLoader.php';
        $loader = new \Nicomv\Posts\Grid\Includes\AutoLoader;
        $loader->register();
        $loader->addNamespace('\Nicomv\Posts\Grid', NMV_POSTSGRID);
        $loader->addNamespace('\Nicomv\Posts\Grid\Includes', NMV_POSTSGRID . 'includes');
        $loader->addNamespace('\Nicomv\Posts\Grid\I18n', NMV_POSTSGRID . 'i18n');
        $loader->addNamespace('\Nicomv\Posts\Grid\Shortcodes', NMV_POSTSGRID . 'shortcodes');
        $this->loader = new ActionLoader;
    }
  
    public function run()
    {
        $this->loader->run();
    }
    
    private function defineAdminActions()
    {
        $ajax_handler = new \Nicomv\Posts\Grid\Utils\AjaxHandler;
        $this->loader->addAction('wp_ajax_query_post_content', $ajax_handler, 'queryPost');
        $this->loader->addAction('wp_ajax_nopriv_query_post_content', $ajax_handler, 'queryPost');
    }
  
    private function registerStyles()
    {
        wp_register_style('nmv-pg-slick', NMV_POSTSGRID_URL . 'assets/js/slick/slick.css');
        wp_register_style('nmv-pg-slick-theme', NMV_POSTSGRID_URL . 'assets/js/slick/slick-theme.css');
    }
  
    private function registerScripts()
    {
        wp_register_script('nmv-pg-slick', NMV_POSTSGRID_URL . 'assets/js/slick/slick.min.js', array( 'jquery' ), '1.0', true);
        wp_register_script('nmv-pg-gallery', NMV_POSTSGRID_URL . 'assets/js/grid-gallery.js', array( 'jquery' ), '1.0', true);
    }
  
    private function loadTextdomain()
    {
        $i18n = new \Nicomv\Posts\Grid\I18n\I18n;
        $this->loader->addAction('plugins_loaded', $i18n, 'loadTextdomain');
    }
    
    private function registerShortcodes()
    {
        $posts_grid = new \Nicomv\Posts\Grid\Shortcodes\PostsGridShortcode;
        $this->loader->addShortcode('nmv_posts_grid', $posts_grid, 'doShortcode');
    }
}
