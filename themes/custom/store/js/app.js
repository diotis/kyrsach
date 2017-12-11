(function ($) {
    Drupal.behaviors.myModuleBehavior = {
        attach: function (context, settings) {
                $('.slider').glide({
                    autoplay: 4000,
                    hoverpause: true,
                    arrowRightText: '&rarr;',
                    arrowLeftText: '&larr;'
                });
        }
    };
})(jQuery);