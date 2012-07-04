/*
 * SimpleModal Basic Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 */

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
    for (var i = 0; i < CONTROL.length -1; i++) {
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

						$.get("xls_admin_js.php?item=promorestrict&id=" + $('#PromoId').val(), function(data){
							// create a modal dialog with the data
							$('#basic-modal-content').html(data);
							
							
							$('.basic-send').bind('click', function() { 
							  choosepromo.send();
							  return false;
							});
							$('.basic-cancel').bind('click', function() { 
							  choosepromo.close();
							  return false;
							});
							
														
						});
					
				$('#basic-modal-content').modal({
					onClose: choosepromo.close }
					);	
							
			});
	
		},
		setup: function (e) {
			$.get("xls_admin_js.php?item=current&val=" + e, function(data){
				
				var q=0;
				$.each(data, function(key,value) {
				  initset[q]=value;
				  q++;
				});
				$('#google1').val(initset[0]);
				initset.splice(0, 0);
				choosepromo.change(1);
		
			}, 'json');
		
		},
		close: function () {
			$.modal.close();
		},				
		send: function () {	
			var categories = []; 
			var families = [];
			var classes = [];
			var keywords = [];
			var products = [];
			$('#ctlCategories :selected').each(function(i, selected){ categories[i] = 'category:' + $(selected).val(); });
			$('#ctlFamilies :selected').each(function(i, selected){ families[i] = 'family:' + $(selected).val(); });
			$('#ctlClasses :selected').each(function(i, selected){ classes[i] = 'class:' + $(selected).val(); });
			$('#ctlKeywords :selected').each(function(i, selected){ keywords[i] = 'keyword:' + $(selected).val(); });
			$('#ctlProducts :selected').each(function(i, selected){ products[i] = $(selected).val(); });
			
			var Lscodes = categories;
			$.merge(Lscodes,families);
			$.merge(Lscodes,classes);
			$.merge(Lscodes,keywords);
			$.merge(Lscodes,products);
			$('#LsCodesEdit').val(Lscodes);
			
			var intMatch = $("#ctlMatchWhen").val();
			if (typeof(intMatch) != "undefined")
				$('#ExceptEdit').val(intMatch);
			if (typeof(Lscodes) != "undefined" && Lscodes=='')
				$('#ExceptEdit').val('0');
				
			$.modal.close();
			return;
		}
	
	};

	
	choosepromo.init();

	
	
    

});

