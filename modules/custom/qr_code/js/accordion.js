(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {
            $('#accordion', context).once('myBehavior').accordion({
                collapsible: true,
                active: false
            })
            .click(function() {
                $("#accordion-content").css("display", "block");
                $("#accordion-title").text("Access Pass Generated");
            });
        }
    }
})(jQuery, Drupal);