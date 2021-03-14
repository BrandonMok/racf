(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {
            $('#accordion', context).once('myBehavior').accordion({
                collapsible: true,
                active: false
            })
            .click(function() {
                $("#accordion-content").css("display", "block");

                // TODO: For analytics, do work here to somehow modify field of generated passes for the event
                
            });
        }
    }
})(jQuery, Drupal);