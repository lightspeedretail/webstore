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
 * template index - home page (unless overridden by a custom homepage with keyword)
 * Note, this template was build using a grid system from getskeleton.com
 * Please consult their website for formatting information.
 *
 */

?><!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= _xls_get_conf('ENCODING', 'utf-8') ?>"/>
	<?php
	$redirect = _xls_stack_pop('xls_meta_redirect');
	if ($redirect && isset($redirect['url']) && isset($redirect['delay'])) {
		echo '<meta http-equiv="refresh" content="' . $redirect['delay'] . ';URL=' . $redirect['url'] . '"/>';
	}
	?>
	<meta name="Author" content="<?= _xls_get_conf('STORE_NAME', 'Web Store.') ?>"/>
	<meta name="Copyright" content="<?= _xls_get_conf('COPYRIGHT_MSG', 'Xsilva Inc.') ?>"/>
	<meta name="Generator" content="LightSpeed Webstore <?= _xls_version(); ?>"/>
	<meta http-equiv="imagetoolbar" content="false"/>
	<base href="<?= _xls_site_dir(); ?>/"/>

	<title><?php echo _xls_stack_get('xls_page_title'); ?></title>
	<link rel="canonical" href="<?php echo _xls_stack_pop('xls_canonical_url'); ?>"/>

	<meta name="description" content="<?php echo _xls_stack_get('xls_meta_desc'); ?>">
	<meta property="og:title" content="<?php echo _xls_stack_pop('xls_page_title'); ?>"/>
	<meta property="og:description" content="<?php echo _xls_stack_pop('xls_meta_desc'); ?>"/>
	<meta property="og:image" content="<?php echo _xls_stack_pop('xls_meta_image'); ?>"/>

	<meta name="google-site-verification" content="<?php echo _xls_get_conf('GOOGLE_VERIFY'); ?>"/>

	<link rel="Shortcut Icon" href="favicon.ico" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="stylesheet" type="text/css" href="assets/css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/base.css">
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/skeleton.css">
	<link rel="stylesheet" type="text/css" href="<?= templateNamed('css') ; ?>/webstore.css"/>
	<link rel="stylesheet" type="text/css" href="assets/css/pushup.css"/>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="assets/css/search.css" id="searchcss"/>
	<link rel="stylesheet" type="text/css" href="assets/css/dummy.css" id="dummy_css"/>
	<link rel="stylesheet" type="text/css" href="assets/css/datepicker.css"/>

	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/fancybox/jquery.fancybox-1.3.4.js"></script>
	<script type="text/javascript" src="assets/js/fancybox/jquery.easing-1.4.pack.js"></script>
	<link rel="stylesheet" href="assets/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen"/>

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
<?php $this->lblSharingHeader->Render(); ?>


<?php $this->dxLogin->Render(); ?>
	<div class="container">
		<div class="sixteen columns">
			<div id="headerimage">
				<a href="<?php echo _xls_site_url(); ?>">
					<img src="<? echo _xls_site_url(_xls_get_conf('HEADER_IMAGE', false)); ?>"/>
				</a>
			</div>
		</div>

		<div class="twelve columns content clearfix">
			<div id="menubar">
				<?php $this->menuPnl->Render(); ?>
			</div>
			<div id="viewport">
				<?php $this->mainPnl->Render(); ?>
			</div>
		</div>

		<div class="four columns alpha omega sidebar">

			<div id="searchentry">
				<?php $this->searchPnl->Render(); ?>
			</div>

			<?php if ($this->showCart()) {
			$this->cartPnl->Render();
			} ?>

			<div>
				<?php if ($this->showSideBar()) {
				$this->sidePnl->Render();
			} ?>
			</div>
		</div>

		<div id="footer" class="sixteen columns">

			<div class="addresshours">


			</div>
				<div class="bottomtabs">
					<?php
					foreach ($this->arrBottomTabs as $arrTab) {
						echo '<a href="' . $arrTab->Link . '">' . _sp($arrTab->Title) . '</a> / ';
					}
					echo '<a href="'._xls_site_url('sitemap/pg').'">'._sp('Sitemap').'</a>';
					?>
				</div>
				<div class="copyright">
					&copy; <?php _xt('Copyright'); ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME', 'Your Store') ?>. <?php _xt('All Rights Reserved'); ?>.
				</div>
			</div>

			<?php $this->lblGoogleAnalytics->Render(); ?>

			<?php if ($expires = _xls_page_session_expiry_duration()): ?>
			<script type="text/javascript">
					<?php
					// in case of session expiry, reload the page so we don't get ajax/javascript errors.
					// the added 5 seconds will ensure that user will be logged out due to inactivity
					?>
				window.setTimeout("document.location.href='<?= _xls_site_url() ?>'", <?= $expires ?> * 1000 + 5000
				)
				;
			</script>
				<?php endif; ?>

	</div>

<?php $this->lblSharingFooter->Render(); ?>
<?php $this->RenderEnd(); ?>
</html>
