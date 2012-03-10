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
 * Deluxe template: index - home page (unless overridden by a custom homepage with keyword) 
 *
 * 
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= _xls_get_conf('LANG_CODE' , 'en') ?>" dir="ltr">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= _xls_get_conf('ENCODING' , 'utf-8') ?>" />
	<?php
		$meta_desc = _xls_stack_pop('xls_meta_desc');
		
		if($meta_desc){
	?>	
		<meta name="description" content="<?= $meta_desc; ?>">
	<?php
		}
	?><?php
		$meta_keywords = _xls_stack_pop('xls_meta_keywords');
		
		if($meta_keywords){
	?>	
		<meta name="keywords" content="<?= $meta_keywords; ?>">
	<?php
		}
	?><?php
		$redirect = _xls_stack_pop('xls_meta_redirect');
		
		if($redirect && isset($redirect['url']) && isset($redirect['delay'])){
	?>	
		<meta http-equiv="refresh" content="<?= $redirect['delay']; ?>;URL=<?= $redirect['url']; ?>"/>
	<?php
		}
	?>
	
	<meta name="Author" content="<?= _xls_get_conf('STORE_NAME' , 'Web Store.') ?>" />
	<meta name="Copyright" content="<?= _xls_get_conf('COPYRIGHT_MSG' , 'Xsilva Inc.') ?>" />
	<meta name="Generator" content="LightSpeed Webstore <?= _xls_version(); ?>" />
	<meta http-equiv="imagetoolbar" content="false" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<base href="<?= _xls_site_dir(); ?>/"/>

	<?php global $strPageTitle; ?>		
	<?php if (isset($strPageTitle)): ?>
			<title><?=  _xls_get_conf('STORE_NAME', _sp('Shopping cart'));   ?> : <?php _xt($strPageTitle); ?></title>
	<?php endif; ?>		

	<link rel="Shortcut Icon" href="favicon.ico" type="image/x-icon" />

	<link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/webstore.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/pushup.css" />
	
	<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/ie7.css" />
	<![endif]-->
	
	<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/ie6.css" />	
	<![endif]-->
	
	<link rel="stylesheet" type="text/css" href="assets/css/search.css" id="searchcss"  />
	<link rel="stylesheet" type="text/css" href="assets/css/dummy.css" id="dummy_css"  />
	<link rel="stylesheet" type="text/css" href="assets/css/datepicker.css" />

	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/fancybox/jquery.fancybox-1.3.4.js"></script>	
	<script type="text/javascript" src="assets/js/fancybox/jquery.easing-1.4.pack.js"></script>	
	<link rel="stylesheet" href="assets/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	
	<script type="text/javascript" src="assets/js/webstore.js"></script>
	<script type="text/javascript" src="assets/js/pushup.js"></script>
	
	<script type="text/javascript">	
		var XLSTemplate = "<?= templateNamed(''); ?>";
		
		//<![CDATA[
			window.onload = function () {
			applesearch.init(); 
		}
	//]]>
	</script>
	</head>
	
	<?php $this->RenderBegin(); ?>
	

	
	

	
	<?php $this->dxLogin->Render(); ?>
		<div id="container">	
		<div id="header">

			<div id="login" class="rounded-bottom">
				<?php if(!$this->isLoggedIn()): ?>
				
				<div class="text">
					<div class="left" style="margin: 0 55px 0 0;"><?php _xt("Welcome!"); ?></div>
					<div class="right"><a href="#" <?php $this->pxyLoginLogout->RenderAsEvents() ?>class="loginbox"><?php _xt("Login"); ?></a> &nbsp;|&nbsp; <a href="index.php?xlspg=customer_register"><?php _xt("Register"); ?></a></div>
				</div>
				
				<?php else: ?>
				<div class="text"><div style="margin: 0 105px 0 0; display: block; float: left;"><a href="index.php?xlspg=myaccount"><?= _xt("My Account"); ?></a></div> <?php $this->lblLogout->Render(); ?></div>
				<?php endif; ?>
			
				
			</div>	
			<a href="index.php">
				<img src="<?php
			     $img =  _xls_get_conf('HEADER_IMAGE' ,  false ); 
			     
			     if(!$img)
			      $img = templateNamed('images') . '/webstore_installation.png';
			     else{
			      $img = _xls_get_url_resource($img);
			     }
			     echo $img;
			     ?>" />
			</a>
		</div>

		<div id="body">
		<div id="content" class="rounded">
		
		<?php $this->menuPnl->Render() ?>
		
    
		<div id="nav" class="rounded-top">
			<ul>
				<li id="products"><a href="index.php"></a></li>
				<?php
				foreach ($this->arrTopTabs as $arrTab)
					echo '<li id="tab'.count($this->arrTopTabs).'"><a href="'.$arrTab->Link.'">'._sp($arrTab->Title).'</a></li>';
				?>				
			</ul><div id="searchedge"></div><div id="searchentry"><?php $this->searchPnl->Render(); ?></div>				
		</div>

			<?php $this->crumbTrail->Render(); ?>
		</div>

	<noscript>
	<h1>
	<?php _xt('This store requires you to have a JavaScript enabled web browser.'); ?>
	</h1>
	</noscript>	

			<?php $this->mainPnl->Render(); ?>
	</div>


		<div id="rightside">

		<?php if($this->showCart()) $this->cartPnl->Render(); ?>

		<div style="clear: both;">
		<?php if($this->showSideBar()) $this->sidePnl->Render(); ?>
		</div>

		</div>
		</div>
		
	<div id="footer" class="rounded">    	
			<div class="left">&copy; <?php _xt('Copyright'); ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME' , 'Your Store') ?>. <?php _xt('All Rights Reserved'); ?>.</div>
			<div class="right">
			<?php
				foreach ($this->arrBottomTabs as $arrTab)
					echo '<a href="'.$arrTab->Link.'">'._sp($arrTab->Title).'</a> |';
				?><a href="index.php?xlspg=sitemap"><?php _xt('Sitemap'); ?></a>
			</div>
	</div>
	</div>
	
	<!-- place google analytics here -->


	<?php $this->dummy_drag_drop->Render(); ?>
	    
	<?php $this->RenderEnd(); ?>		
	<?php
	if(QApplication::$Database[1]->EnableProfiling)
		echo QApplication::$Database[1]->OutputProfiling();
	?>    		
	
	<script type="text/javascript">	
	<?php if($this->blnGetScreenRes):   ?>
		$.post(document.forms[0].action , { store_screen: "true", width: screen.width , height: screen.height } );
	<?php endif; ?>	
		//done
	</script>

<?php if(_xls_get_conf('DEBUG_TEMPLATE' , false)):  ?>
 	<?php $files = array();  ?>
<!-- 
	Template files used
	<?php while($filename = _xls_stack_pop('template_used')): ?>
		<?php $files[] = $filename; ?><?= $filename; ?> 
	<?php endwhile; ?>
-->	
 	<?php _xls_log(sprintf(_sp("Template files used %s") , implode(", " , $files)));  ?>
<?php endif; ?>

<?php if($expires = _xls_page_session_expiry_duration()):  ?>
	<script type="text/javascript"> 
<?php
		// in case of session expiry, reload the page so we don't get ajax/javascript errors.
		// the added 5 seconds will ensure that user will be logged out due to inactivity
?>		
		window.setTimeout("document.location.href='<?= _xls_site_dir() ?>/index.php'" , <?= $expires ?> * 1000 + 5000 );
	</script>
<?php endif; ?>
</html>
