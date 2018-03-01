<?php

namespace Nicomv\Posts\Grid\I18n;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.0.1
 * @package    Nicomv\Posts\Grid\
 * @subpackage Nicomv\Posts\Grid\I18n
 * @author     skaparate <info@nicomv.com>
 */
class I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.0.1
	 */
	public function loadTextDomain() {
		load_plugin_textdomain(
			'nmv-postsgrid',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
