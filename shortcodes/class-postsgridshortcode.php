<?php
/**
 * Displays a set of posts from a specific category and layout.
 *
 * @package nicomv/postsgrid/shortcodes
 */

namespace Nicomv\PostsGrid\Shortcodes;

use Nicomv\PostsGrid\Utils\Logger;

/**
 * Displays a set of posts from a specific category and layout.
 *
 * @version: 0.0.2
 */
class PostsGridShortcode {

	/**
	 * Default shortcode arguments.
	 *
	 * @var array
	 */
	private $default_args;

	/**
	 * Order applied to the posts meta.
	 *
	 * @var array
	 */
	private $order_by_meta;

	/**
	 * Builds an instance of this class.
	 */
	public function __construct() {
		$this->default_args = array(
			/**
			 * Required: The category name (not the slug or ID)
			 * that's being shown.
			 */
			'category'           => '',
			/**
			 * Quantity of posts to display.
			 */
			'quantity'           => 30,
			/**
			 * The container class.
			 */
			'container_class'      => '',
			/**
			 * The order by which the posts are displayed:
			 * ASC = ascending, DESC, descending.
			 */
			'display_order'      => 'ASC',
			/**
			 * The field by which the posts are
			 * sorted by. See order_by_meta field.
			 */
			'order_by'           => '',
			/**
			 * If set to true, every post is expected to
			 * contain only images, which will be shown using
			 * slick.
			 */
			'is_gallery'         => 'false',
			/**
			 * Hides the show more link.
			 */
			'hide_show_more'     => 'true',
		);
		/**
		 * Supported meta fields by which the posts can be
		 * sorted.
		 */
		$this->order_by_meta = array(
			'nmv_pg_date',
			'nmv_pg_index',
			'title',
		);
	}

	/**
	 * Executes the shortcode.
	 *
	 * @param Array $attrs The attributes assigned by the user.
	 */
	public function do_shortcode( $attrs ) {
		$data = shortcode_atts( $this->default_args, $attrs );

		// If there is no category defined, return an empty div.
		if ( ! isset( $data['category'] ) || empty( $data['category'] ) ) {
			return '<div class="nmv-posts-grid"></div>';
		}
		$this->prepare_scripts( $data );
		ob_start();
		$result = $this->build_initial_tag( $data );

		/**
		 * Hook into this filter to prepend any extra HTML
		 * to the grid container (not each item).
		 *
		 * @since 0.0.5
		 * @param String $result The output html.
		 */
		$result = apply_filters( 'nicomv/postsgrid/prepend_content', $result );
		$posts = get_posts( $this->build_query( $data ) );
		foreach ( $posts as $post ) {
			$this->include_template( $post, $data );
		}
		wp_reset_postdata();
		$result .= ob_get_clean();

		/**
		 * Hook into this filter to append content to the grid container.
		 *
		 * @since 0.0.5
		 * @param String $result The ouput html.
		 */
		$result = apply_filters( 'nicomv/postsgrid/append_content', $result );
		$result .= '</div>';
		return $result;
	}

	/**
	 * Includes a template.
	 *
	 * @param Object $post A WP_Post instance object.
	 * @param Object $data The data received from the shortcode.
	 */
	private function include_template( $post, $data ) {
		$custom_fields = get_post_custom( $post->ID );
		$item_id = $post->ID;
		$item_link = $this->build_link( $post, $custom_fields );
		$item_date = $this->get_item_date( $custom_fields );
		$item_title = $post->post_title;
		$item_img_url = wp_get_attachment_url( get_post_thumbnail_id( $item_id ) );
		$item_subtitle = $this->get_subtitle( $custom_fields );
		$hide_show_more = 'true' === $data['hide_show_more'];
		$has_gallery = $this->has_gallery( $custom_fields );
		$is_gallery = $this->is_gallery( $data );
		$item_class = 'posts-grid-item grid-item' . $this->get_custom_masonry_item_class( $custom_fields );
		include NMV_POSTSGRID . 'templates/post-grid.php';
	}

	/**
	 * Sorts the query by the specified value.
	 *
	 * @param Array $sc_data The data used to sort the result.
	 */
	private function order_by( $sc_data ) {
		$result = array(
			'order_by' => '',
			'meta_key' => '',
		);
		if ( array_search( $sc_data['order_by'], $this->order_by_meta ) === false ) {
			return $result;
		}
		$val = $sc_data['order_by'];
		if ( 'title' === $val ) {
			return array( 'order_by' => $val );
		}
		if ( 'nmv_pg_index' !== $val && 'nmv_post_date' !== $val ) {
			$result['order_by'] = $val;
		} else {
			if ( 'nmv_pg_index' === $val ) {
				$result['order_by'] = 'meta_value_num';
				$result['meta_key'] = $val;
			} elseif ( 'nmv_post_date' === $val ) {
				$result['order_by'] = 'meta_value_date';
				$result['meta_key'] = $val;
			}
		}
		return $result;
	}

	/**
	 * Checks if the is coming soon setting is enabled.
	 *
	 * @param Array $custom_fields The fields set on the page.
	 */
	private function is_coming_soon( $custom_fields ) {
		if ( ! isset( $custom_fields['coming_soon'] ) ) {
			return false;
		}
		$cs = trim( $custom_fields['coming_soon'][0] );
		return 'true' === $cs || '1' === $cs;
	}

	/**
	 * Builds the query to retrieve the posts.
	 *
	 * @param Array $sc_data The shortcode data.
	 */
	private function build_query( $sc_data ) {
		$order_by = $this->order_by( $sc_data );
		$cat = get_cat_ID( $sc_data['category'] );
		$query = array(
			'posts_per_page'    => $sc_data['quantity'],
			'category'          => $cat,
			'orderby'           => $order_by['order_by'],
			'meta_key'          => $order_by['meta_key'],
			'order'             => $sc_data['display_order'],
		);
		return $query;
	}

	/**
	 * Retrieves the custom post date.
	 *
	 * @param Array $custom_fields The post custom attributes.
	 */
	private function get_item_date( $custom_fields ) {
		if ( ! isset( $custom_fields['nmv_pg_date'] ) ) {
			return '';
		}
		return $custom_fields['nmv_pg_date'][0];
	}

	/**
	 * Retrieves the custom post url.
	 *
	 * @param Array $custom_fields The post custom attributes.
	 */
	private function get_custom_url( $custom_fields ) {
		if ( ! isset( $custom_fields['nmv_pg_url'] ) ) {
			return '';
		}
		return trim( $custom_fields['nmv_pg_url'][0] );
	}

	/**
	 * Builds a link from a post and the post custom attributes.
	 *
	 * @param WP_Post $post The post.
	 * @param Array   $custom_fields The post custom attributes.
	 */
	private function build_link( $post, $custom_fields ) {
		$custom_url = $this->get_custom_url( $custom_fields );
		if ( '' !== $custom_url ) {
			return $custom_url;
		}
		if ( $this->is_coming_soon( $custom_fields ) ) {
			return '#item-' . $post->ID;
		}
		return get_the_permalink( $post );
	}

	/**
	 * Builds the initial container tag.
	 *
	 * @param Array $sc_data The data passed to the shortcode.
	 */
	private function build_initial_tag( $sc_data ) {
		$r = '<div class="nmv-posts-grid grid';

		if ( isset( $sc_data['container_class'] ) && '' !== $sc_data['container_class'] ) {
			$r .= ' ' . trim( $sc_data['container_class'] );
		}

		$r .= '">';
		return $r;
	}

	/**
	 * Checks if this post has a gallery. The difference with is_gallery, is that
	 * the post may set this attribute to skip displaying the gallery.
	 *
	 * @param Array $custom_fields The post custom attributes.
	 */
	private function has_gallery( $custom_fields ) {
		if ( ! isset( $custom_fields['nmv_pg_nogallery'] ) ) {
			return true;
		}
		return 'false' === $custom_fields['nmv_pg_nogallery'][0] ||
	  '0' === $custom_fields['nmv_pg_nogallery'][0];
	}

	/**
	 * Checks if this post is gallery or not.
	 *
	 * @param Array $data The post custom attributes.
	 */
	private function is_gallery( $data ) {
	  if ( ! isset( $data['is_gallery'] ) ) {
			return false;
	  }
	  return 'true' === $data['is_gallery'] ||
		'on' === $data['is_gallery'] ||
		'1' === $data['is_gallery'];
	}

	/**
	 * Retrieves the post sub title.
	 *
	 * @param Array $custom_fields The post custom attributes.
	 */
	private function get_subtitle( $custom_fields ) {
		if ( ! isset( $custom_fields['nmv_pg_caption'] ) ) {
			return '';
		}
		return $custom_fields['nmv_pg_caption'][0];
	}

	/**
	 * Retrieves the masonry item class.
	 *
	 * @param Array $custom_fields The post custom attributes.
	 */
	private function get_custom_masonry_item_class( $custom_fields ) {
		if ( ! isset( $custom_fields['nmv_pg_masonry_class'] ) ) {
			return '';
		}
		$r = sanitize_text_field( $custom_fields['nmv_pg_masonry_class'][0] );
		return ! empty( $r ) ? ' ' . $r : '';
	}

	/**
	 * Prepares the scripts that will be used.
	 *
	 * @param Array $sc_data The shortcode attribute data.
	 */
	private function prepare_scripts( $sc_data ) {
		Logger::log( 'Preparing scripts' );
		if ( $this->is_gallery( $sc_data ) ) {
			Logger::log( 'Adding gallery plugin' );
			$this->load_gallery_scripts();
		}
		$this->load_default_scripts();
	}

	/**
	 * Loads the gallery scripts.
	 */
	private function load_gallery_scripts() {
		Logger::log( 'Loading gallery scripts' );
		wp_enqueue_script( 'nmv-pg-slick' );
		wp_enqueue_style( 'nmv-pg-slick' );
		wp_enqueue_style( 'nmv-pg-slick-theme' );
		wp_enqueue_script( 'nmv-pg-gallery' );
		wp_localize_script(
		  'nmv-pg-gallery',
		  'gridGalleryConfig',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( NMV_POSTSGRID_NONCE ),
		)
	  );
	}

	/**
	 * Loads the default scripts.
	 */
	private function load_default_scripts() {
		Logger::log( 'Loading default scripts' );
		wp_enqueue_script( 'load-images' );
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'nmv-pg-masonry-setup' );
	}
}
