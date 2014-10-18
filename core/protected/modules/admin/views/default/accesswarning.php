<div class="span9">

    <h3>Web Store Access Warning</h3>
    <div class="editinstructions">
		<p><?php echo Yii::t('admin','Make sure to only use allowed tags in the message field.'); ?></p>
    </div>

<?php
	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'enableClientValidation' => true
		)
	);
?>
