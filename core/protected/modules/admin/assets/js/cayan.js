jQuery(function ($) {
    var setcayan = {
        message: null,
        init: function () {
            $('.setcayan').live('click',function (e) {
                $.get(yii.urls.base + "/admin/payments/cayan" , function(data){

                    // create a modal dialog with the data
                    $('#setcayan-modal').html(data);

                    $('#buttonSavePCR').bind('click', function() {
                        setcayan.send();
                        return false;
                    });

                    $('#setcayan-modal').dialog("open");

                }); //end of $.get

            });

            $('.viewcayan').live('click',function (e) {
                $.get(yii.urls.base + "/admin/payments/cayandemo" , function(data){

                    // create a modal dialog with the data
                    $('#viewcayan-modal').html(data);


                    $('#buttonSavePCR').bind('click', function() {
                        //setcayan.send();
                        return false;
                    });

                    $('#viewcayan-modal').dialog("open");

                }); //end of $.get

            });

        },

        close: function () {
            $('#setcayan-modal').dialog("close");
            $('#viewcayan-modal').dialog("close");
        },
        send: function () {

            $.post(yii.urls.base + "/admin/payments/cayan", $("#cayanForm").serialize(), function(data){
                if (data=='success') {
                    window.location.reload(true);
                }
                else {
                    $("#alert-box").html(data);
                    $("#alert-box").dialog("open");
                }
            });

            return;
        }

    };

    setcayan.init();
});

