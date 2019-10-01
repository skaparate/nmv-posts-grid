<?php
/**
 * Template used to display a gallery, if there is one.
 *
 * @package nicomv/postsgrid/templates
 */

?>

<div class="nmv-gallery">
  <div class="nmv-gallery--wrapper">
	<?php echo esc_html( $images ); ?>
  </div>
  <div class="nvm-gallery--thumbnails">
	<?php echo esc_html( $images ); ?>
  </div>
  <div class="nvm-gallery--text-container">
	<h4><?php echo esc_html( $title ); ?></h4>
	<button type="button"><?php esc_html_e( 'Volver', 'nmv-postsgrid' ); ?>
  </div>
</div>
