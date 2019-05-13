<?php

namespace Nicomv\PostsGrid\Utils;

/** 
 * Handles Ajax requests.
 * 
 * @since      0.0.1
 * @package 	 Nicomv\PostsGrid
 * @subpackage Nicomv\PostsGrid\Utils
 * @author     skaparate <info@nicomv.com>
 */

class AjaxHandler
{
    public function __construct()
    {
    }
  
    public function queryPost()
    {
        check_ajax_referer(NMV_POSTSGRID_NONCE, 'security');
        if (!isset($_GET['articleno']) || !is_numeric($_GET['articleno'])) {
            Logger::log("Ther articleno is not present or not a number");
            echo 'bad_request';
            die();
        }
        $post_id = intval($_GET['articleno']);
        $query = get_post($post_id);
        Logger::log("Loading article $post_id");
        Logger::log("Querying: $query");
        if (!$query) {
            Logger::log("Empty query, returning...");
            echo "empty";
        } else {
            Logger::log("Loading gallery template");
            $this->buildGalleryHtml($query->post_title, $query->post_content);
        }
        die();
    }
  
    private function buildGalleryHtml($title, $images)
    {
        include NMV_POSTSGRID . 'templates/gallery-template.php';
    }
}
