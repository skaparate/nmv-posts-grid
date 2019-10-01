<?php
/**
 * Class used to debug.
 *
 * @package nicomv/postsgrid/utils
 */

namespace Nicomv\PostsGrid\Utils;

/**
 * Utility class to log messages for debugging.
 */
class Logger {
	/**
	 * Logs the message.
	 *
	 * @param String $msg The message.
	 */
	public static function log( $msg ) {
		if ( WP_DEBUG ) {
			error_log( $msg );
		}
	}
}
