
var arrOldValues;

function SelectAllList(CONTROL) {
    for (var i = 0; i < CONTROL.length; i++) {
        CONTROL.options[i].selected = true;
    }
}

function DeselectAllList(CONTROL) {
    for (var i = 0; i < CONTROL.length; i++) {
        CONTROL.options[i].selected = false;
    }
}


function FillListValues(CONTROL) {
    var arrNewValues;
    var intNewPos = -1;
    var strTemp = GetSelectValues(CONTROL);
    arrNewValues = strTemp.split(",");
    for (var i = 0; i < arrNewValues.length - 1; i++) {
        if (arrNewValues[i] == 1) {
            intNewPos = i;
        }
    }

    if (intNewPos > -1)
        for (var i = 0; i < arrOldValues.length - 1; i++) {
            if (arrOldValues[i] == 1 && i != intNewPos) {
                CONTROL.options[i].selected = true;
            } else if (arrOldValues[i] == 0 && i != intNewPos) {
                CONTROL.options[i].selected = false;
            }

            if (arrOldValues[intNewPos] == 1) {
                CONTROL.options[intNewPos].selected = false;
            } else {
                CONTROL.options[intNewPos].selected = true;
            }
        }
}


function GetSelectValues(CONTROL) {
    var strTemp = "";
    for (var i = 0; i < CONTROL.length; i++) {
        if (CONTROL.options[i].selected == true) {
            strTemp += "1,";
        } else {
            strTemp += "0,";
        }
    }
    return strTemp;
}

function GetCurrentListValues(CONTROL) {
    var strValues = "";
    strValues = GetSelectValues(CONTROL);
    arrOldValues = strValues.split(",")
}


jQuery(function ($) {
    var initset=new Array('0');
    var choosepromo = {
        message: null,
        init: function () {
            $('.basic').live('click',function (e) {
                $.get(yii.urls.base + "/admin/ajax/shippingset?id=" + $(this).attr("id") , function(data){

                    // create a modal dialog with the data
                    $('#setpromo-modal').html(data);

                    $('#buttonSavePCR').bind('click', function() {
                      choosepromo.send();
                      return false;
                    });

                    $('#setpromo-modal').dialog("open");
                    $('#RestrictionForm_categories').focus();
                }); //end of $.get

            });

        },

        close: function () {
            $('#setpromo-modal').dialog("close");
        },
        send: function () {

            $.post(yii.urls.base + "/admin/ajax/UpdateRestrictions", $("#restrictions").serialize(), function(data){
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


    choosepromo.init();



});

