(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {
            $("#access-pass", context).once('myBehavior').click(function() {
                $(".access-pass__content").fadeIn("slow");

                $eventTitle = $('#event-title').text();
                $.ajax({
                    type: "POST",
                    url: settings.path.baseUrl + 'passgenerated' + '/' + $eventTitle,
                });

                $("#access-pass").off('click'); 
            });

            $("#print-text", context).once('myBehavior').click(function() {
                window.print();
            });
        }
    }
})(jQuery, Drupal);