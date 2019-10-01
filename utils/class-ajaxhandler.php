<?php
/**
 * Ajax request handler.
 *
 * @package nicomv/postsgrid/utils
 */

namespace Nicomv\PostsGrid\Utils;

/**
 * Handles Ajax requests.
 *
 * @since      0.0.1
 * @package      Nicomv\PostsGrid
 * @subpackage Nicomv\PostsGrid\Utils
 * @author     skaparate <info@nicomv.com>
 */
class AjaxHandler {

	/**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Query the post.
	 */
	public function query_post() {
		check_ajax_referer( NMV_POSTSGRID_NONCE, 'security' );
		if ( ! isset( $_GET['articleno'] ) || ! is_numeric( $_GET['articleno'] ) ) {
			Logger::log( 'Ther articleno is not present or not a number' );
			echo 'bad_request';
			die();
		}
		$post_id = intval( $_GET['articleno'] );
		$query = get_post( $post_id );
		Logger::log( "Loading article $post_id" );
		Logger::log( "Querying: $query" );
		if ( ! $query ) {
			Logger::log( 'Empty query, returning...' );
			echo 'empty';
		} else {
			Logger::log( 'Loading gallery template' );
			$this->build_gallery_html( $query->post_title, $query->post_content );
		}
		die();
	}

	/**
	 * Builds the gallery html content.
	 *
	 * @param String $title The gallery title.
	 * @param Array  $images The images to be shown.
	 */
	private function build_gallery_html( $title, $images ) {
		include NMV_POSTSGRID . 'templates/gallery-template.php';
	}
}
