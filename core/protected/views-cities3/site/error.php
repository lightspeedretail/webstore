<?php
$this->pageTitle=Yii::app()->name . ' - '. Yii::t('global','Error');
$this->breadcrumbs=array(
	Yii::t('global','Error'),
);
$this->layout = "/layouts/column2";

if (defined('YII_DEBUG') && YII_DEBUG) {
	echo '<h2>'.Yii::t('global','Error').' '.$code.'</h2>';
	echo '<div class="error">'.CHtml::encode($message).'</div>';
	echo '<P></P><div class="error">NOTE: Comment out YII_DEBUG lines in index.php to hide these messages.</div>';
}
else {
	if ($code=="404" || $code=="500")
	{
		echo '<h2>'.Yii::t('global','Error').' '.$code.'</h2>';
		echo '<div class="error">'.CHtml::encode($message).'</div>';
	}
	else
	{
	echo '<h2>Error</h2>';
	echo "<div class='error'>". Yii::t('global',"We're sorry, an error has occurred with this site. The error has been logged and the administrators have been notified. For additional help, please contact {email}",array("{email}"=>_xls_get_conf('EMAIL_FROM')))."</div>";
	}
}