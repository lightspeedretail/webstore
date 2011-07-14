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
 * Deluxe template: Replacement index page when store has been taken offline 
 * in LightSpeed Web Admin panel
 * 
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= _xls_get_conf('LANG_CODE' , 'en') ?>" dir="ltr">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= _xls_get_conf('CHARSET' , 'utf-8') ?>" />
	<meta name="Author" content="<?= _xls_get_conf('STORE_NAME' , 'Xsilva Inc.') ?>" />
	<meta name="Copyright" content="<?= _xls_get_conf('COPYRIGHT_MSG' , 'Xsilva Inc.') ?>" />

	<base href="<?= _xls_site_dir(); ?>/"/>

	<title><?=  _xls_get_conf('STORE_NAME', _sp('Shopping cart'));   ?>: Temporarily offline</title>

    <link rel="Shortcut Icon" href="favicon.ico" type="image/x-icon" />

	<link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/webstore.css" />
	
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/ie7.css" />
	<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
	<![endif]-->
 	
	<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/ie6.css" />
	<![endif]-->
 			
	<script type="text/javascript">	
		var XLSTemplate = "<?= templateNamed(''); ?>";
	</script> 			
 	 			
	</head>
	
		<div id="offline">		
		
			<img src="assets/images/sticky_offline.png" style="display: block; float: left; margin: 25px;" />
		
			<div class="left">
				<img src="<?php
			     $img =  _xls_get_conf('HEADER_IMAGE' ,  false ); 
			     
			     if(!$img)
			      $img = templateNamed('images') . '/webstore_installation.png';
			     else{
			      $img = _xls_get_url_resource($img);
			     }
			     echo $img;
			     ?>" />
				
				<h1><?php _xt("Please check back later."); ?></h1>
				<h2><?php _xt("Feel free to contact us at"); ?> <strong><?= _xls_get_conf('STORE_PHONE') ?></strong> <?php _xt("or by email at"); ?>  <a href="mailto:<?= _xls_get_conf('ADMIN_EMAIL'); ?>"><?= _xls_get_conf('ADMIN_EMAIL'); ?></a></h2>
			</div>
		</div>
		

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

	</body>
</html>


	

