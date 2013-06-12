<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
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