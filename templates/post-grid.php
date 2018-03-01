<article id="posts-grid-item-<?php echo $item_id; ?>" class="posts-grid-item">
    <img class="posts-grid-image" src="<?php echo $item_img_url; ?>" />
    <div class="posts-grid-overlay">
      <div class="posts-grid-overlay-wrapper">
        <h2 class="posts-grid-item-title">
          <?php echo $item_title; ?>
        </h2>
        <?php if ($item_subtitle !== ''): ?>
        <h3 class="posts-grid-item-subtitle"><?php echo $item_subtitle ?></h3>
        <?php endif; ?>
        <?php if( $item_date !== '' ): ?>
        <p class="posts-grid-date"><?php echo $item_date; ?></p>
        <?php endif; ?>
        <?php if ( $is_gallery === 'true' && $has_gallery ): ?>
          <button type="button" class="posts-grid-item-gallery-btn" data-articleid="<?php echo $item_id; ?>">
            <?php _e( 'Ver más', 'nmv-postsgrid' ); ?>
          </button>
        <?php else: ?>
          <a href="<?=$item_link?>" class="posts-grid-show-more"><?=__('Ver más', 'nmv-postsgrid')?></a>
        <?php endif; ?>
      </div>
    </div>
</article>