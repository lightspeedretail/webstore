<head>
	<meta charset="utf-8">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link rel="canonical" href="<?= $this->CanonicalUrl; ?>"/>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<meta name="description" content="<?= $this->pageDescription; ?>">
	<meta property="og:title" content="<?= $this->pageTitle; ?>"/>
	<meta property="og:description" content="<?= $this->pageDescription; ?>"/>
	<meta property="og:image" content="<?= $this->pageImageUrl; ?>"/>
	<meta property="og:url" content="<?= $this->CanonicalUrl; ?>"/>

	<meta name="google-site-verification" content="<?= $this->pageGoogleVerify; ?>"/>
	<?= $this->pageGoogleFonts; ?>

	<link rel="shortcut icon" href="<?=Yii::app()->baseUrl."/images/favicon.ico" ?>" />

	<?php
	foreach(Yii::app()->theme->info->cssfiles as $cssfile)
		Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl($cssfile));
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl(Yii::app()->theme->config->CHILD_THEME));
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl('custom'));
	?>

	<?php $this->widget('ext.wsiosorientationbugfix.iosorientationbugfix'); ?>

	<?php echo $this->renderPartial("/site/_google",null,true); ?>
</head>


