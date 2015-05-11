<?php
	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'enableClientValidation' => false,
			'htmlOptions' => array('class' => "section-content", 'id' => "shipping", 'novalidate' => '1',
			)
		)
	);
?>

<nav class="steps">
	<ol>
		<li class="current"><span class="webstore-label"></span><?php echo Yii::t('checkout', "Shipping")?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', "Payment")?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', "Confirmation")?></li>
	</ol>
</nav>
<h1><?php echo Yii::t('checkout', 'Shipping'); ?></h1>
<div class="error-holder">
	<?php echo $error; ?>
</div>
<?php
$shouldDisplayShippingAddresses = $model->shouldDisplayShippingAddresses();
$isStorePickupSelected = $model->isStorePickupSelected();
$onLoadDisplayInStorePickup = ($shouldDisplayShippingAddresses === false || $isStorePickupSelected);
$this->renderPartial("_storepickup", array(
		'model' => $model,
		'form' => $form,
		'shouldDisplayShippingAddresses' => $shouldDisplayShippingAddresses,
		'isStorePickupSelected' => $isStorePickupSelected,
		'onLoadDisplayInStorePickup' => $onLoadDisplayInStorePickup));
?>
<?php if ($shouldDisplayShippingAddresses): ?>
<div class="modal-conditional-block <?= ($onLoadDisplayInStorePickup === false) ? 'active' : ''?>">
	<?php $this->renderPartial('_shippingheader', array('model' => $model)); ?>
	<div class="address-form outer-address-form">
		<?php $this->renderPartial('//site/_flashmessages'); ?>
		<?php $this->renderPartial('_shippingaddress',array('model' => $model, 'form' => $form, 'error' => $error) ); ?>
		<footer class="submit submit-small">
			<?=
				CHtml::submitButton(
						Yii::t(
							'forms',
							'Submit'
						),
						array(
							'type' => 'submit',
							'class' => 'button',
							'value' => Yii::t(
								'checkout',
								'See Shipping Options'
							),
						)
					);
			?>
		</footer>
	</div>
</div>
<?php endif; ?>
<?php $this->endWidget();?>

<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>

