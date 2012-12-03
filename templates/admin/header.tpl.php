<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Web Store Configuration</title>

	<script type="text/javascript" src="<?=  adminTemplate('js/jquery.min.js');  ?>"></script>
	<script type="text/javascript" src="<?=  adminTemplate('js/jquery.ui.js');  ?>"></script>
	<script type="text/javascript" src="<?=  adminTemplate('js/admin.js'); ?>"></script>
<!--	<script type="text/javascript" src="--><?//=  adminTemplate('js/corners.js'); ?><!--"></script>-->
	<link rel="stylesheet" type="text/css" href="<?=  adminTemplate('css/superfish.css'); ?>" id="superfishcss"  />
	<link rel="stylesheet" type="text/css" href="<?=  adminTemplate('css/admin.css'); ?>" id="admincss"  />

	<script type="text/javascript">
//		$(document).ready(function(){
//			$("ul.sf-menu").superfish();
//		});
		function doRefresh() {
//			$('.rounded').corners();
//			$('.rounded').corners();
			/* test for double rounding */
			$('table', $('#featureTabsc_info .tab')[0]).each(function () {
				$('.native').hide();
			});
			$('#featureTabsc_info').show();
			tab(0);
//			tooltip();

		}
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
//			$('.rounded').corners();
//			$('.rounded').corners(); /* test for double rounding */
			$('table', $('#featureTabsc_info .tab')[0]).each(function(){$('.native').hide();});
			$('#featureTabsc_info').show();
		});
		function tab(n) {
			$('#featureTabsc_info .tab').removeClass('tab_selected');
			$($('#featureTabsc_info .tab')[n]).addClass('tab_selected');
			$('#featureElementsc_info .feature').hide();
			$($('#featureElementsc_info .feature')[n]).show();
		}
	</script>

</head>
<body>
<?php
if(isset($this->AlertRibbon))
	if (strlen($this->AlertRibbon)>0)
		echo '<div style="margin: 10px 70px 5px 70px; padding: 4px; background:  url('.adminTemplate('css/images/header.png').'); height: 28px;"><img style="padding-right: 5px;width:18px; height:17px;" align="left" src="'.adminTemplate('css/images/btn_info.png').'"><span style="color: white; text-shadow: 0px; ">'.$this->AlertRibbon.'</span></div>';

include_once(adminTemplate('pages.tpl.php'));
