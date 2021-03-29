(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {

            let redeemed = $('#redeemed');
            if ( Object.keys(redeemed).length == 0 ) {
                // Empty - element hasn't been redeemed
                $("#access-pass", context).once('myBehavior').click(function() {
                    $(".access-pass__content").fadeIn("slow");
    
                    let eventTitle = $('#event-title').text();

                    $.ajax({
                        type: "POST",
                        url: settings.path.baseUrl + 'passgenerated' + '/' + eventTitle,
                    });
    
                    $("#access-pass").off('click'); 
                });
            }
            else {
                $(".access-pass__content").css('display', 'block');
            }

            $("#print-text", context).once('myBehavior').click(function() {
                window.print();
            });
        }
    }
})(jQuery, Drupal);