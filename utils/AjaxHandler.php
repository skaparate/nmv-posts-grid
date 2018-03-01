<?php

namespace Nicomv\Posts\Grid\Utils;

/** 
 * Handles Ajax requests.
 * 
 * @since      0.0.1
 * @package 	 Nicomv\Posts\Grid
 * @subpackage Nicomv\Posts\Grid\Utils
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
            echo 'bad_request';
            die();
        }
        $post_id = intval($_GET['articleno']);
        $query = get_post($post_id);
        if (!$query) {
            echo "empty";
        } else {
            $this->buildGalleryHtml($query->post_title, $query->post_content);
        }
        die();
    }
  
    private function buildGalleryHtml($title, $images)
    {
        include NMV_POSTSGRID . 'templates/gallery-template.php';
    }
}
