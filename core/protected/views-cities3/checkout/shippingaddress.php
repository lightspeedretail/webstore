<?php
$form = $this->beginWidget(
	'CActiveForm',
	array('htmlOptions' => array('class' => "section-content",'id' => "shipping", 'novalidate' => '1')
	)
);
?>
<nav class="steps">
	<ol>
		<li class="current"><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Shipping')?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Payment')?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Confirmation')?></li>
	</ol>
</nav>


<h1><?php echo Yii::t('checkout', 'Shipping'); ?></h1>
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
<!--------------------------------------------- Layout Markup --------------------------------------------->
<?php if ($shouldDisplayShippingAddresses): ?>
<div class="modal-conditional-block <?= ($onLoadDisplayInStorePickup === false) ? 'active' : ''?>">
	<?php $this->renderPartial('_shippingheader', array('model' => $model)); ?>
	<?php $this->renderPartial('//site/_flashmessages'); ?>
	<div class="error-holder"><?= $error ?></div>
	<ol class="address-blocks">
		<?php if(count($model->objAddresses) > 0): ?>
			<?php foreach ($model->objAddresses as $key => $objAddress): ?>
				<li class="address-block address-block-pickable">
					<p class="webstore-label">
						<?php
						echo $objAddress->formattedblockcountry;
						?>
						<span class="controls">
							<?php
								echo CHtml::link(
									Yii::t('checkout', 'Edit Address'),
									Yii::app()->createUrl(
										'/checkout/editaddress',
										array(
											'id' => $objAddress->id,
											'type' => 'shipping'
										)
									)
								);
							?>
							<?php
							echo CHtml::ajaxLink(
								Yii::t('checkout', 'Remove'),
								Yii::app()->createUrl('myaccount/removeaddress'),
								array(
									'type' => 'POST',
									'data' => array(
										'CustomerAddressId' => $objAddress->id,
										'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken
									),
									'success' => 'function(data) {
										var addressBlock = $(this).parents(".address-block")[0];
										$(addressBlock).remove();
									}.bind(this)'
								),
								array(
									'class' => 'delete'
								)
							);
							?>
					</p>
					<div class="buttons">
						<button name="Address_id" value="<?= $objAddress->id ?>" class="small <?= $key == 0 ? 'default' : ''; ?>">
							<?php echo Yii::t('checkout', 'Ship to this address'); ?>
						</button>
					</div>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		<li class="add">
			<?php
				echo CHtml::link(
					Yii::t('checkout', 'Add New Address'),
					Yii::app()->createUrl(
						'/checkout/newaddress',
						array('type' => 'shipping')
					),
					array('class' => 'small button')
				);
			?>
		</li>
	</ol>
</div>
<?php endif; ?>
<!--------------------------------------------- Layout Markup --------------------------------------------->
<?php $this->endWidget();?>
<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>
