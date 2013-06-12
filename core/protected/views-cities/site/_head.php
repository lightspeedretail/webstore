<head>
	<meta charset="utf-8">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link rel="canonical" href="<?= $this->CanonicalUrl; ?>"/>

	<meta name="description" content="<?= $this->pageDescription; ?>">
	<meta property="og:title" content="<?= $this->pageTitle; ?>"/>
	<meta property="og:description" content="<?= $this->pageDescription; ?>"/>
	<meta property="og:image" content="<?= $this->pageImageUrl; ?>"/>
	<meta property="og:url" content="<?= $this->CanonicalUrl; ?>"/>

	<meta name="google-site-verification" content="<?= $this->pageGoogleVerify; ?>"/>

	<link rel="Shortcut Icon" href="<?=Yii::app()->baseUrl."/images/favicon.ico" ?>" type="image/x-icon"/>

	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/base.css'); ?>
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css'); ?>
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/'._xls_get_conf('CHILD_THEME').'.css'); ?>
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/custom.css'); ?>
	<?php echo $this->renderPartial("/site/_google",null,true); ?>
</head>