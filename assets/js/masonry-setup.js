(function($) {
  $(document).ready(function() {
    const grid = document.querySelector('.grid');
    const masonry = new Masonry(grid, {
      // options
      itemSelector: '.grid-item',
      gutter: 6,
      fitWidth: true,
      isFitWidth: true,
    });

    const imgLoad = imagesLoaded(grid);
    imgLoad.on('progress', function() {
      masonry.layout();
    });
  });
})(jQuery);
