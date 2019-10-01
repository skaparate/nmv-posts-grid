<?php
/**
 * Template used to display the grid items
 *
 * @package nicomv/postsgrid/templates
 */

?>
<div id="posts-grid-item-<?php echo esc_html( $item_id ); ?>" class="<?php echo esc_html( $item_class ); ?>">
	<?php
		/**
		 * Hook into this action to prepend any content to each item.
		 * You should echo the output, not return it.
		 */
		do_action( 'nicomv/postsgrid/preped_griditem_content' );
	?>
	<img class="posts-grid-item--image" src="<?php echo esc_url( $item_img_url ); ?>" />
	<div class="posts-grid-item--overlay">
		<div class="posts-grid-item--overlay__wrapper">
			<a href="<?php echo esc_url( false === $hide_show_more ? '#' : $item_link ); ?>" class="posts-grid-item--show-more">
				<h4 class="posts-grid-item--title"><?php echo esc_html( $item_title ); ?></h4>
				<?php if ( '' !== $item_subtitle ) : ?>
				<h5 class="posts-grid-item--subtitle"><?php echo esc_html( $item_subtitle ); ?></h5>
				<?php endif; ?>
				<?php if ( '' !== $item_date ) : ?>
				<p class="posts-grid-item--date"><?php echo esc_html( $item_date ); ?></p>
				<?php endif; ?>
				<?php if ( $is_gallery && $has_gallery ) : ?>
				<button type="button" class="posts-grid-item--gallery-button" data-articleid="<?php echo esc_html( $item_id ); ?>">
					<?php esc_html_e( 'Ver más', 'nmv-postsgrid' ); ?>
				</button>
				<?php endif; ?>
			</a>
		</div>
	</div>
</div>
