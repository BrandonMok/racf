(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {
            // "Generate Access Pass" button onClick
            $("#access-pass", context).once('myBehavior').click(function() {
                $(".access-pass__content").fadeIn("slow");



                // Update passes generated field
                $eventTitle = $('#event-title').text();
                $.ajax({
                    type: "POST",
                    url: settings.path.baseUrl + 'passgenerated' + '/' + $eventTitle,
                });
            });

            // PRINT btn onClick
            $("#print-text", context).once('myBehavior').click(function() {
                window.print();
            });
        }
    }
})(jQuery, Drupal);