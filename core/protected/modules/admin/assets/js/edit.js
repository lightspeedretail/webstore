

jQuery(function ($) {
    var initset=new Array('0');
    var editpending = {
        message: null,
        init: function () {
            $('.basic').live('click',function (e) {
                $.get(yii.urls.base + "/admin/databaseadmin/edit?id=" + $(this).attr("id") , function(data){

                    // create a modal dialog with the data
                    $('#setpromo-modal').html(data);

                    $('#buttonSavePCR').bind('click', function() {
                      editpending.send();
                      return false;
                    });

                    $('#setpromo-modal').dialog("open");

                }); //end of $.get

            });

        },

        close: function () {
            $('#setpromo-modal').dialog("close");
        },
        send: function () {

            $.post(yii.urls.base + "/admin/databaseadmin/delete", $("#editpending").serialize(), function(data){
                if (data=='success')
                    window.location.reload(true);
                else {
                    $("#alert-box").html(data);
                    $("#alert-box").dialog("open");
                }
            });
            //$('#setpromo-modal').dialog("close");

            return;
        }

    };


    editpending.init();



});

