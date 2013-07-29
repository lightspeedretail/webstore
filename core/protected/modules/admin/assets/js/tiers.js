
jQuery(function ($) {
    var settiers = {
        message: null,
        init: function () {
            $('.settiers').live('click',function (e) {
                $.get(yii.urls.base + "/admin/shipping/tiers" , function(data){

                    // create a modal dialog with the data
                    $('#settiers-modal').html(data);


                    $('#buttonSavePCR').bind('click', function() {
                        settiers.send();
                      return false;
                    });

                    $('#settiers-modal').dialog("open");

                }); //end of $.get

            });

        },

        close: function () {
            $('#settiers-modal').dialog("close");
        },
        send: function () {

            $.post(yii.urls.base + "/admin/shipping/tiers", $("#tier-grid").serialize(), function(data){
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


    settiers.init();



});

