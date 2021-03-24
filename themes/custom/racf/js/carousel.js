(function ($, Drupal) {
    Drupal.behaviors.slickjs = {
        attach: function(context, settings) {
            $("#event-images-carousel", context).once('slickjs').slick({
                dots: true,
                arrows: true,
                infinite: true,
                fade: true,
                cssEase: 'linear',
                autoplay: true,
                autoplaySpeed: 20000,
            });

            $("#general-event-image-carousel", context).once('slickjs').slick({
                dots: true,
                arrows: true,
                infinite: true,
                fade: true,
                cssEase: 'linear',
                autoplay: true,
                autoplaySpeed: 20000,
            });
        }
    }
})(jQuery, Drupal);
