<?php
/**
 * I18n class file.
 *
 * @package nicomv/postsgrid/i18n
 */

namespace Nicomv\PostsGrid\I18n;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.0.1
 * @package    Nicomv\PostsGrid\
 * @subpackage Nicomv\PostsGrid\I18n
 * @author     skaparate <info@nicomv.com>
 */
class I18n {
	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_text_domain() {
		load_plugin_textdomain(
			'nmv-postsgrid',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
