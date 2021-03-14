(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        // attach: function(context, settings) {
        //     $('#accordion', context).once('myBehavior').accordion({
        //         collapsible: true,
        //         active: false
        //     })
        //     .click(function() {
        //         $("#accordion-content").css("display", "block");
        //         $("#accordion-title").text("Access Pass Generated");
        //     });
        // }
        attach: function(context, settings) {
            $("#access-pass").once('myBehavior').click(function() {
                $("#access-pass__content").fadeIn("slow");
            });
        }
    }
})(jQuery, Drupal);