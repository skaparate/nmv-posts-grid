(function($) {
    $(document).ready(function() {
        let $grid = $('.nmv-posts-grid').masonry({
            // options
            itemSelector: '.posts-grid-item',
            columnWidth: '.posts-grid-sizer',
            percentPosition: true
        });

        $grid.imagesLoad().progress(function() {
            $grid.masonry('layout');
        });
    });
    
})(jQuery);