

jQuery(function ($) {
    var initset=new Array('0');
    var resetpw = {
        message: null,
        init: function () {
            $('.resetpw').live('click',function (e) {
                initset = $(this).attr("id");
                $.get(yii.urls.base + "/admin/databaseadmin/resetpassword?id=" + $(this).attr("id") , function(data){


                    // create a modal dialog with the data
                    $('#setpw-modal').html(data);

                    $('#buttonSavePCR').bind('click', function() {
                        resetpw.send();
                      return false;
                    });

                    $('#setpw-modal').dialog("open");
                    $('#RestrictionForm_categories').focus();
                }); //end of $.get

            });

        },

        close: function () {
            $('#setpw-modal').dialog("close");
        },
        send: function () {
            $.post(yii.urls.base + "/admin/databaseadmin/resetpassword?id=" + initset, $("#tier-grid").serialize(), function(data){
                if (data=='success')
                    window.location.reload(true);
                else alert(data);
            });
            //$('#setpw-modal').dialog("close");

            return;
        }

    };


    resetpw.init();



});

