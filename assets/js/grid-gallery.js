(function($) {
  var $grid;
  var initialPosition;
  var ajaxCalls = 0;
  var lastTap;

  function removeLoader($el) {
    var $overlay = $el.parent().parent().parent().find('.loading-overlay');
    $overlay.off('click', handleClicks);
    $overlay.hide();
  }

  function handleClicks(event) {
    event.preventDefault();
  }

  function removeSlick() {
    $('.nmv-gallery').css('opacity', 0);
    $('.nmv-gallery .text-container button').off('click', removeSlick);
    $('.nmv-gallery .wrapper').slick('unslick');
    $('.nmv-gallery .thumbnails').slick('unslick');
    $('.nmv-gallery').remove();
    $grid.find('.posts-grid-item').fadeIn();
    ajaxCalls = 0;
    var scrollTo = $(initialPosition).offset().top - $(initialPosition).parent().parent().height();
    // Scroll to last known position:
    $('html, body').animate({
      scrollTop: scrollTo
    }, 1000);
  }

  function afterSlickInit(event) {
    // Go to the slider position:
    $('html, body').animate({
      scrollTop: $(event.currentTarget).offset().top
    }, 1000);
  }

  function initSlick(data) {
    $('.nmv-posts-grid .posts-grid-item .posts-grid-overlay .posts-grid-item-gallery-btn').on('click', onShowMoreClick);
    $grid.find('.posts-grid-item').hide();
    $grid.css('opacity', 0);
    $grid.append(data);
    $('.nmv-gallery .wrapper')
      .on('init', afterSlickInit)
      .slick({
        setPosition: 0,
        slidesToShow: 1,
        slidesToScroll: 1,
        mobileFirst: true,
        arrows: false,
        fade: false,
        asNavFor: '.thumbnails'
      });
    $('.nmv-gallery .thumbnails').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      asNavFor: '.wrapper',
      centerMode: false,
      focusOnSelect: true,
      mobileFirst: true,
      variableWidth: false,
      arrows: false,
      responsive: [{
        breakpoint: 400,
        settings: {
          arrows: true
        }
      }]
    });
    // Remove active class from all thumbnail slides
    $('.nmv-gallery .thumbnails .slick-slide').removeClass('slick-active');

    // Set active class to first thumbnail slides
    $('.nmv-gallery .thumbnails .slick-slide').eq(0).addClass('slick-active');

    // On before slide change match active thumbnail to current slide
    $('.nmv-gallery .wrapper').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
      var mySlideNumber = nextSlide;
      $('.nmv-gallery .thumbnails .slick-slide').removeClass('slick-active');
      $('.nmv-gallery .thumbnails .slick-slide').eq(mySlideNumber).addClass('slick-active');
    });

    $grid.css('opacity', 1);
    $('.nmv-gallery .text-container button').on('click', removeSlick);
  }

  function onResize() {
    $grid.find('.nmv-gallery .gallery-wrapper').css('width', $grid.width());
    $('.nmv-posts-grid .loading-overlay').css('width', $('.nmv-posts-grid').width());
    $('.nmv-posts-grid .loading-overlay').css('height', $('.nmv-posts-grid').height());
  }

  function beforeSend($el) {
    var $parent = $el.parent().parent().parent();
    var $overlay;
    $parent.append('<div class="loading-overlay"><span class="loader"></span></div>');
    $overlay = $parent.find('.loading-overlay');
    $overlay.hide();
    $overlay.on('click', handleClicks);
    $overlay.css({
      width: $parent.width(),
      height: $parent.height(),
      top: 0,
      left: 0
    });
    $overlay.fadeIn();
  }

  function updateLoaderSize() {
    $('.nmv-posts-grid .loading-overlay').css('width', $('.nmv-posts-grid').width());
    $('.nmv-posts-grid .loading-overlay').css('height', $('.nmv-posts-grid').height());
  }

  function loadGalleryContent($el) {
    var data = {
      'articleno': $el.data('articleid'),
      'security': gridGalleryConfig.nonce,
      'action': 'query_post_content'
    };
    ajaxCalls++;
    console.log('Ajax calls', ajaxCalls);
    $.ajax({
      url: gridGalleryConfig.ajaxurl,
      method: 'get',
      dataType: 'html',
      data: data,
      beforeSend: function() {
        beforeSend($el);
      }
    }).done(function(data) {
      initSlick(data);
    }).fail(function(error) {
      console.log(error);
    }).then(function() {
      removeLoader($el);
    });
  }

  function onShowMoreClick(event) {
    event.stopPropagation();
    // Get current scroll position
    var el = event.currentTarget;
    initialPosition = el;
    $(el).off('click', onShowMoreClick);
    if (ajaxCalls > 0) {
      return false;
    }
    loadGalleryContent($(el));
  }

  function onGridItemClick(event) {
    event.stopPropagation();
  }

  function docReady() {
    $grid = $('.nmv-posts-grid');
    $('.nmv-posts-grid .posts-grid-item').on('click', onGridItemClick);
    $grid.find('.posts-grid-item .posts-grid-item-gallery-btn').on('click', onShowMoreClick);
    window.resize = onResize;
  }
  $(document).ready(docReady);
})(jQuery);