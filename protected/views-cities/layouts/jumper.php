<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="<?= _xls_get_conf('LANG_CODE', 'en') ?>"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/base.css">
    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->


</head>
<body>

<h1 class="jumper" style="text-align: center;"><?php echo Yii::t('global',"Please wait while your request is processed"); ?><P><?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?></h1>

<?php echo $content; ?>

</body>
</html>