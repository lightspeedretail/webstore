
jQuery(function ($) {
    var setdest = {
        message: null,
        init: function () {
            $('.destinationrates').live('click',function (e) {
                $.get(yii.urls.base + "/admin/shipping/destinationrates" , function(data){

                    // create a modal dialog with the data
                    $('#setdest-modal').html(data);
                    $('#setdest-modal').width(300);
                    $('#setdest-modal').height(300);

                    $('#buttonSavePCR').bind('click', function() {
                        setdest.send();
                      return false;
                    });

                    $('#setdest-modal').dialog("open");

                }); //end of $.get

            });

        },

        close: function () {
            $('#setdest-modal').dialog("close");
        },
        send: function () {

            $.post(yii.urls.base + "/admin/shipping/destinationrates", $("#destinations").serialize(), function(data){
                if (data=='success')
                    window.location.reload(true);
                else {
                    $("#alert-box").html(data);
                    $("#alert-box").dialog("open");
                }
            });

            return;
        }

    };


    setdest.init();



});

