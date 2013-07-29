jQuery(function ($) {
	var initset=new Array('0');
    var thisid;
    var extraitem = "";

	var choosecat = {
		message: null,
		init: function () {
			$('.basic').live('click',function (e) {

                        thisid =  $(this).attr("id");

                        $.get(yii.urls.base + "/admin/ajax/integrationcategories?service=" +
                         service + "&item=name0&id=" + thisid , function(data){
							// create a modal dialog with the data
							$('#set-categories-dialog').html(data);
							$('#name1').live('change', function() { choosecat.change(1); });
							$('#name2').live('change', function() { choosecat.change(2); });
							$('#name3').live('change', function() { choosecat.change(3); });
							$('#name4').live('change', function() { choosecat.change(4); });
							$('#name5').live('change', function() { choosecat.change(5); });
							$('#name6').live('change', function() { choosecat.change(6); });

                            choosecat.setup(thisid);

                            $('#buttonSavePCR').bind('click', function() {
                                choosecat.send();
                                return false;
                            });

                            $('#set-categories-dialog').dialog("open");
						});

			});
	
		},
		setup: function (e) {
			$.get(yii.urls.base + "/admin/ajax/currentcats?service=" + service + "&id=" + e, function(data){

				var q=0;
                $.each(data.cats, function(key,value) {
                    initset[(key-1)]=value;
                    q++;
                });
				$('#name1').val(initset[0]);
				initset.splice(0, 0);
				choosecat.change(1);
                if (service=="google")
                {
                    $('#googleg').val(data.gender);
                    $('#googlea').val(data.age);
                }

                if (service=="amazon")
                {
                    $('#producttype').val(data.producttypes);
                    extraitem = data.producttypes;
                }

			}, 'json');

		},
		change: function (e) {
			$.get(yii.urls.base + "/admin/ajax/intsubcats?service=" + service +
                "&item=name" + e + "&lv=" + e + "&selected=" + encodeURIComponent($("#name" + e).val()), function(data){
				var $el = $("#name" + (e+1));
				$el.removeAttr("disabled"); 
				$('#name' + (e+1) + ' option:gt(0)').remove();
				var count=0;
				$.each(data.cats, function(key, value) {
				  if (key!='') {count=count+1;
				  $el.append($("<option></option>")
				     .attr("value", key).text(value));
				  $el.val(''); }
				});
				if (count<1) $el.attr("disabled",'disabled');
				
				if(initset[(e)]) {
					$("#name" + (e+1)).val(initset[(e)]);
					choosecat.change((e+1));
					initset[(e)]=0;
				}
				
				for(q=(e+2); q<=7; q++) {
					var $el = $("#name" + q);
					$('#name' + (q) + ' option:gt(0)').remove();
					$el.attr("disabled",'disabled');
				}

               if (service=="google")
                   if ($("#name1").val() == 72) //"Apparel & Accessories"
                        $('#extra').show();
                    else
                        $('#extra').hide();


                if (service=="amazon" && data.producttypes) {
                    choosecat.updateamazon(data.producttypes);
                    $('#producttype').val(extraitem);
                }

				
			}, 'json');
		},		
		send: function () {
			var categorystring = '';
			for(q=1; q<=7; q++) {
				var $el = $("#name" + q);
				if ($el.val()>'0')
                    categorystring = $el.val();
			}

            if (service=="google" && $("#name1").val() == 72) //"Apparel & Accessories"
               var stringtosend = thisid + '|' + categorystring + '|' + $("#googleg").val()  + '|' + $("#googlea").val();
            else if (service=="amazon")
                var stringtosend = thisid + '|' + categorystring + '|' + $("#producttype").val();
            else
               var stringtosend = thisid + '|' + categorystring;

			$.get(yii.urls.base + "/admin/ajax/intcatsave?service=" + service +
                "&selected=" + encodeURIComponent(stringtosend), function(data){
					if (data.substr(0,7)=="success")
                    {
                        var item = data.split("|");
                        $('#set-categories-dialog').dialog("close");
                        $('#'+thisid).html(item[1]);
                    }
                        else {
                            $("#alert-box").html(data);
                            $("#alert-box").dialog("open");
                    }
				}, 'json');

			return;
		},
        updateamazon: function(data) {
            var $el = $("#producttype");
            $('#producttype' + ' option:gt(0)').remove();
            var count=0;
            $.each(data, function(key, value) {
                count=count+1;
                    $el.append($("<option></option>")
                        .attr("value", value).text(value));
            });
            $el.val('');
        }
	
	};

	
	choosecat.init();

	
	
    

});

