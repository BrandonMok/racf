(function ($, Drupal) {
    Drupal.behaviors.myBehavior = {
        attach: function(context, settings) {
            let redeemed = $('#redeemed');
            if ( Object.keys(redeemed).length == 0 ) {
                // Empty - element hasn't been redeemed
                $("#access-pass", context).once('myBehavior').click(function() {
                    $(".access-pass__content").fadeIn("slow");

                    $.ajax({
                        type: "POST",
                        url: settings.path.baseUrl + 'passgenerated' + '/' + drupalSettings.eventID,
                    });

                    $("#access-pass").off('click'); 

                    $("#download-text").trigger("click");
                });
            }
            else {
                $(".access-pass__content").css('display', 'block');
                $("#download-text").trigger("click");
            }

            $("#print-text", context).once('myBehavior').click(function() {
                window.print();
            });


            $("#download-text", context).click(function() {
                var element = $(".access-pass");
                var downloadbtn = $("#download-text");

                html2canvas(element, { 
                    letterRendering: 1, 
                    allowTaint : false, 
                    useCORS: true, 
                    onrendered : function (canvas) { 
                        var imageData = canvas.toDataURL("image/png");
            
                        var newData = imageData.replace(
                        /^data:image\/png/, "data:application/octet-stream");
                    
                        downloadbtn.attr("download", $('#event-title').text().trim() + ".png");
                        downloadbtn.attr("href", newData);
                    } 
                });
            });
        }
    }
})(jQuery, Drupal);