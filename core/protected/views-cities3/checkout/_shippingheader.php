<div class="<?php echo $model->isInStorePickupActive() ? 'or-block': '' ?>"></div>
<h3><?php
	if (_xls_get_conf('SHIP_SAME_BILLSHIP') == 1)
		echo Yii::t('checkout', "Shipping & Billing Address");
	else
		echo Yii::t('checkout', "Shipping Address");
	?>
</h3>
<?php
if (_xls_get_conf('SHIP_SAME_BILLSHIP') == 1) :
	?>
	<div class="notice">
		<p>
			<?php
			echo '<strong>' . Yii::t('checkout', 'NOTE') . ': </strong>';
			echo Yii::t('checkout', 'Shipping and billing address must match to make a credit card purchase on this site.');
			?>
		</p>
	</div>
<?php
endif;
?>