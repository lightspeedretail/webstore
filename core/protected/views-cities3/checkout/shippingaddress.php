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

<?php $this->renderPartial("_storepickup",array('model' => $model, 'form' => $form) ); ?>
<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
<div class="modal-conditional-block active">
	<div class="<?php echo $model->isInStorePickupActive() ? 'or-block': '' ?>"></div>
	<div class="error-holder"><?= $error ?></div>
	<h3><?php echo Yii::t('checkout','Shipping Address'); ?></h3>
	<ol class="address-blocks">
		<?php if(count($model->objAddresses)>0): ?>
			<?php foreach ($model->objAddresses as $key => $objAddress): ?>
				<li class="address-block address-block-pickable">
					<p class="webstore-label">
						<?php
						echo $objAddress->formattedblockcountry;
						?>
						<span class="controls">
							<a href="/checkout/shipping?address_id=<?= $objAddress->id ?>"><?php echo Yii::t('checkout','Edit Address'); ?></a>
							<?php echo Yii::t('checkout', 'or'); ?>
							<a data-customer-address-id="<?= $objAddress->id ?>" href="" class="delete"><?php echo Yii::t('checkout','Delete'); ?></a>
						</span>
					</p>
					<div class="buttons">
						<button name="Address_id" value="<?= $objAddress->id ?>" class="small <?= $key == 0 ? 'default' : ''; ?>">
							<?php echo Yii::t('cart', 'Ship to this address'); ?>
						</button>
					</div>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		<li class="add">
			<?php echo CHtml::link(Yii::t('cart','Add New Address'), '/checkout/shipping/', array('class' => 'small button')); ?>
		</li>
	</ol>
</div>
<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
<?php $this->endWidget();?>
<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>
