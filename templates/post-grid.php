<article id="posts-grid-item-<?php echo $item_id; ?>" class="posts-grid-item">
    <img class="posts-grid-image" src="<?php echo $item_img_url; ?>" />
    <div class="posts-grid-overlay">
      <div class="posts-grid-overlay-wrapper">
        <h2 class="posts-grid-item-title">
          <?php if($hide_show_more): ?>
            <a href="<?= $item_link ?>">
              <?php echo $item_title; ?>
            </a>
          <?php else: ?>
            <?php echo $item_title; ?>
          <?php endif; ?>
        </h2>
        <?php if ($item_subtitle !== ''): ?>
        <h3 class="posts-grid-item-subtitle"><?php echo $item_subtitle ?></h3>
        <?php endif; ?>
        <?php if( $item_date !== '' ): ?>
        <p class="posts-grid-date"><?php echo $item_date; ?></p>
        <?php endif; ?>
        <?php if ($is_gallery && $has_gallery): ?>
          <button type="button" class="posts-grid-item-gallery-btn" data-articleid="<?php echo $item_id; ?>">
            <?php _e( 'Ver más', 'nmv-postsgrid' ); ?>
          </button>
        <?php elseif(!$hide_show_more): ?>
          <a href="<?=$item_link?>" class="posts-grid-show-more"><?=__('Ver más', 'nmv-postsgrid')?></a>
        <?php endif; ?>
      </div>
    </div>
</article>