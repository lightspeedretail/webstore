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

jQuery(function ($) {
	var initset=new Array('0');
	var choosegoogle = {
		message: null,
		init: function () {
			$('.basic').live('click',function (e) {

						$.get("xls_admin_js.php?item=google&rurl=" + $('#RequestUrl').val(), function(data){
							// create a modal dialog with the data
							$('#basic-modal-content').html(data);
							$('#google1').live('change', function() { choosegoogle.change(1); });
							$('#google2').live('change', function() { choosegoogle.change(2); });
							$('#google3').live('change', function() { choosegoogle.change(3); });
							$('#google4').live('change', function() { choosegoogle.change(4); });
							$('#google5').live('change', function() { choosegoogle.change(5); });
							$('#google6').live('change', function() { choosegoogle.change(6); });
							$('.basic-send').live('click', function() { 
							  choosegoogle.send();
							  return false;
							});
							$('.basic-cancel').live('click', function() { 
							  choosegoogle.close();
							  return false;
							});
							
							if ($('#GoogleCatEdit').val()>0)
								choosegoogle.setup($('#GoogleCatEdit').val());
							else if ($('#GoogleCatParentEdit').val()>0)
								choosegoogle.setup($('#GoogleCatParentEdit').val());
							
						});
					
				$('#basic-modal-content').modal({
					onClose: choosegoogle.close }
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
				choosegoogle.change(1);
		
			}, 'json');
		
		},
		close: function () {
			var google1 = $("#google1").val();
			//alert(google1);
			$.modal.close();
		},		
		change: function (e) {
			$.get("xls_admin_js.php?item=google" + e + "&lv=" + e + "&selected=" + encodeURIComponent($("#google" + e).val()), function(data){
				var $el = $("#google" + (e+1));
				$el.removeAttr("disabled"); 
				$('#google' + (e+1) + ' option:gt(0)').remove();
				$.each(data, function(key, value) {
				  $el.append($("<option></option>")
				     .attr("value", value).text(key));
				});
				
				if(initset[(e)]) {
					$("#google" + (e+1)).val(initset[(e)]);
					choosegoogle.change((e+1));

				}
				
				for(q=(e+2); q<=7; q++) {
					var $el = $("#google" + q);
					$('#google' + (q) + ' option:gt(0)').remove();
					$el.attr("disabled","disabled");
				}

				
				
			}, 'json');
		},		
		send: function () {
			var googlestring = '';
			for(q=1; q<=7; q++) {
				var $el = $("#google" + q);
				if ($el.val()>'0')
					if (q>1) googlestring = googlestring + ' > ' + $el.val();
						else googlestring = $el.val();		
			}
			if (googlestring > '') {
				$('#GoogleCatEdit').val(googlestring);
				$('#googlecat').html(googlestring.substring(0,15) + '...');
			}
			
			$.modal.close();
			return;
		}
	
	};

	
	choosegoogle.init();

	
	
    

});

