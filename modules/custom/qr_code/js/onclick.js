(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {

            let redeemed = $('#redeemed');
            if ( Object.keys(redeemed).length == 0 ) {
                // Empty - element hasn't been redeemed
                $("#access-pass", context).once('myBehavior').click(function() {
                    $(".access-pass__content").fadeIn("slow");
    
                    let eventTitle = $('#event-title').text();
                    let nodeType;

                    if ($("event-date")) {
                        nodeType = "event";
                    }
                    else {
                        nodeType = "generalEvent";
                    }

                    $.ajax({
                        type: "POST",
                        url: settings.path.baseUrl + 'passgenerated' + '/' +  nodeType  + '/' + eventTitle,
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


            $("#download-text", context).click(function() {
                var element = $(".access-pass");

                html2canvas(element, { letterRendering: 1, allowTaint : false, useCORS: true , onrendered : function (canvas) { 
                    var imageData = canvas.toDataURL("image/png");
          
                    var newData = imageData.replace(
                    /^data:image\/png/, "data:application/octet-stream");
                
                    $("#download-text").attr("download", $('#event-title').text() + ".png").attr("href", newData);
                } });
            });
        }
    }
})(jQuery, Drupal);