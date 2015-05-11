<?php
Yii::app()->clientScript->registerScript(
	'instantiate Helper',
	'var helper = new Helper();',
	CClientScript::POS_END
);
?>

<div class="section-content">
	<h1><?= Yii::t('profile', 'My Account'); ?></h1>

	<?php $this->renderPartial('_accountinfo', array('model' => $model)); ?>

	<?php $this->renderPartial('_addresslist', array('model' => $model, 'activeAddresses' => $activeAddresses)); ?>

	<?php $this->renderPartial('_orderhistory', array('model' => $model)); ?>

	<?php $this->renderPartial('_wishlistinfo', array('model' => $model)); ?>

	<?php $this->renderPartial('_sroinfo', array('model' => $model)); ?>
</div>

<input id="yii-csrf-token" type="hidden" name="YII_CSRF_TOKEN" value="<?= Yii::app()->request->csrfToken ?>">


