(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {
            $("#access-pass", context).once('myBehavior').click(function() {
                $(".access-pass__content").fadeIn("slow");
            });

            // @TODO for analytics - do another .click and need to make an AJAX call to update bc in JS.

            
        }
    }
})(jQuery, Drupal);