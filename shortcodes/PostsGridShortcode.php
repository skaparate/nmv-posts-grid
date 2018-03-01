<?php

namespace Nicomv\Posts\Grid\Shortcodes;

/**
 * Displays a set of posts from a specific category and layout.
 *
 * @author: skaparate <info@nicomv.com>
 * @version: 0.0.2
 */
class PostsGridShortcode
{
    /**
     * Default shortcode arguments.
     * @var array
     */
    private $defaultArgs;
    
    /**
     * Order applied to the posts meta.
     * @var array
     */
    private $orderByMeta;

    public function __construct()
    {
        $this->defaultArgs = array(
            'category'	         => 'Eventos',
            'quantity'	         => 30,
            'container_class'	   => '',
            'display_order'      => 'ASC',
            'order_by'           => '',
            'is_gallery'         => 'false'
        );
        $this->orderByMeta = array(
            'nmv_pg_date', 'nmv_pg_index'
        );
    }

    public function doShortcode($attrs)
    {
        ob_start();
        $data = shortcode_atts($this->defaultArgs, $attrs);
        $this->prepareScripts($data);
        $result = $this->buildInitialTag($data);
        $posts = get_posts($this->buildQuery($data));
        foreach ($posts as $post) {
            $this->includeTemplate($post, $data);
        }
        wp_reset_postdata();
        $result .= ob_get_clean();
        $result .= '</div>';
        return $result;
    }
    
    private function includeTemplate($post, $data)
    {
        $custom_fields = get_post_custom($post->ID);
        $item_id = $post->ID;
        $item_link = $this->buildLink($post, $custom_fields);
        $item_date = $this->getItemDate($custom_fields);
        $item_title = $post->post_title;
        $item_img_url = wp_get_attachment_url(get_post_thumbnail_id($item_id));
        $item_subtitle = $this->getSubtitle($custom_fields);
        $has_gallery = $this->hasGallery($custom_fields);
        $is_gallery = $data['is_gallery'];
        include NMV_POSTSGRID . 'templates/post-grid.php';
    }

    private function orderBy($sc_data)
    {
        $result = array( 'order_by' => '', 'meta_key' => '' );
        if (array_search($sc_data['order_by'], $this->orderByMeta) === false) {
            return $result;
        }
        $val = $sc_data['order_by'];
        if ($val !== 'nmv_pg_index' && $val !== 'nmv_post_date') {
            $result['order_by'] = $val;
        } else {
            if ($val === 'nmv_pg_index') {
                $result['order_by'] = 'meta_value_num';
                $result['meta_key'] = $val;
            } elseif ($val === 'nmv_post_date') {
                $result['order_by'] = 'meta_value_date';
                $result['meta_key'] = $val;
            }
        }
        return $result;
    }

    private function isComingSoon($custom_fields)
    {
        if (! isset($custom_fields['coming_soon'])) {
            return false;
        }
        $cs = trim($custom_fields['coming_soon'][0]);
        return $cs === 'true' || $cs === '1';
    }

    private function buildQuery($sc_data)
    {
        $order_by = $this->orderBy($sc_data);
        //print_r( $order_by );
        $cat = get_cat_ID($sc_data['category']);
        $query = array(
            'posts_per_page'    => $sc_data['quantity'],
            'category'          => $cat,
            'orderby'           => $order_by['order_by'],
            'meta_key'          => $order_by['meta_key'],
            'order'             => $sc_data['display_order']
        );
        return $query;
    }

    private function getItemDate($custom_fields)
    {
        if (! isset($custom_fields['nmv_pg_date'])) {
            return '';
        }
        return $custom_fields['nmv_pg_date'][0];
    }
    
    private function getCustomUrl($custom_fields)
    {
        if (! isset($custom_fields['nmv_pg_url'])) {
            return '';
        }
        return trim($custom_fields['nmv_pg_url'][0]);
    }

    private function buildLink($post, $custom_fields)
    {
        $custom_url = $this->getCustomUrl($custom_fields);
        if ($custom_url !== '') {
            return $custom_url;
        }
        if ($this->isComingSoon($custom_fields)) {
            return '#item-' . $post->ID;
        }
        return get_the_permalink($post);
    }

    private function buildInitialTag($sc_data)
    {
        $r = '<div class="nmv-posts-grid';

        if (isset($sc_data['container_class']) && $sc_data['container_class'] !== '') {
            $r .= ' ' . trim($sc_data['container_class']);
        }

        $r .= '">';
        return $r;
    }
    
    private function hasGallery($custom_fields)
    {
        if (! isset($custom_fields['nmv_pg_nogallery'])) {
            return true;
        }
        return $custom_fields['nmv_pg_nogallery'][0] === 'false' ||
      $custom_fields['nmv_pg_nogallery'][0] === '0';
    }

    private function getSubtitle($custom_fields)
    {
        if (! isset($custom_fields['nmv_pg_caption'])) {
            return '';
        }
        return $custom_fields['nmv_pg_caption'][0];
    }
    
    private function prepareScripts($sc_data)
    {
        if ($sc_data['is_gallery'] === 'true') {
            add_action('wp_enqueue_scripts', array( $this, 'loadGalleryScripts' ));
        }
    }
    
    public function loadGalleryScripts()
    {
        wp_enqueue_script('nmv-pg-slick');
        wp_enqueue_style('nmv-pg-slick');
        wp_enqueue_style('nmv-pg-slick-theme');
        wp_enqueue_script('nmv-pg-gallery');
        wp_localize_script(
          'nmv-pg-gallery',
          'gridGalleryConfig',
        array(
          'ajaxurl' => admin_url('admin-ajax.php'),
          'nonce'   => wp_create_nonce(NMV_POSTSGRID_NONCE)
        )
      );
    }
}