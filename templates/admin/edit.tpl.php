<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Web Admin panel template called by xlsws_admin class
 * General use for item editing
 * 
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr">
<head>
	<title><?php _xt("Admin configuration") ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= _xls_get_conf('CHARSET' , 'utf-8') ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?= adminTemplate('css/admin.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= adminTemplate('css/superfish.css') ?>" />
	
    <script type="text/javascript" src="<?=  adminTemplate('js/jquery.min.js');  ?>"></script>     
    <script type="text/javascript" src="<?=  adminTemplate('js/jquery.ui.js');  ?>"></script>     
	<script type="text/javascript" src="<?=  adminTemplate('js/admin.js'); ?>"></script>
	<script type="text/javascript" src="<?=  adminTemplate('js/corners.js'); ?>"></script>

	<script type="text/javascript"> 
    $(document).ready(function(){ 
        $("ul.sf-menu").superfish(); 
    });
	</script>
	
	<script type="text/javascript">
		function doRefresh(){
    $('.rounded').corners();
    $('.rounded').corners(); /* test for double rounding */
    $('table', $('#featureTabsc_info .tab')[0]).each(function(){$('.native').hide();});
    $('#featureTabsc_info').show();
    tab(0);
    	tooltip();
		
		}
	
  $(document).ready(function(){
  	doRefresh();
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

<?php include_once(adminTemplate('pages.tpl.php')); ?>


<?php $this->RenderBegin(); ?>



		<br /><br />
			
		<div id="options" class="accord rounded" style="width:890px" > 
		<div id="tabs" style="margin-top: -43px;">
			<ul>
				<?php foreach($this->arrTabs as $type=>$label): ?>
				<a href="<?= $this->get_uri($type); ?>" >
					<li class="rounded 
						<?php if($type == $this->currentTab): ?>
							active
						<?php endif; ?> {5px top transparent}" style="display:block; float: left">
						<?= $label; ?>
					</li>
				</a>
				<?php endforeach; ?>
			</ul>
		</div>

<div class="content">
<?php

if(isset($this->HelperRibbon)) 
	if (strlen($this->HelperRibbon)>0)
		echo '<div style="padding: 5px;"><img style="padding-right: 5px;width:44px; height:35px;" align="left" src="'.adminTemplate('css/images/questionmark.png').'"> '.$this->HelperRibbon.'</div>';

$this->dtgItems->Render('CssClass="rounded wide"');

?>


<div style="margin: -6px 0 0 0; background:  url(<?= adminTemplate('css/images/header.png') ?>); height: 37px;" class="rounded-bottom">
<?php if($this->canNew()): ?>
	<img src="<?= adminTemplate('css/images/btn_add.png') ?>" style="margin: 12px 5px 0 15px; display: block; float: left;" />
	<div class="add" <?php $this->btnNew->RenderAsEvents(); ?>>Add</div>
<?php endif; ?>
</div>
	
<?php if($this->canFilter()): ?>
	<div class="search">
		<?php $this->txtSearch->Render('CssClass=searchBox'); ?>
		<?php $this->btnSearch->Render('CssClass=searchButton button rounded' , 'Width=50'); ?>
	</div>
<?php endif; ?>
</div>
</div>

<?php $this->RenderEnd(); ?>

</body>
</html>
