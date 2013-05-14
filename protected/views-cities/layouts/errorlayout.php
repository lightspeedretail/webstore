<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="<?= _xls_get_conf('LANG_CODE', 'en-US') ?>"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="<?= _xls_get_conf('LANG_CODE', 'en-US') ?>"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="<?= _xls_get_conf('LANG_CODE', 'en-US') ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="<?= _xls_get_conf('LANG_CODE', 'en-US') ?>"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<?php echo $content; ?>
</body>