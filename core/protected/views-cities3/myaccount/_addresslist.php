<?php
	Yii::app()->clientScript->registerScript(
		'instantiate Address',
		'$(function(){
			var address = new Address(' .
				CJSON::encode(
					array(
						'defaultBillingId' => $model->default_billing_id,
						'defaultShippingId' => $model->default_shipping_id,
						'MY_ACCOUNT_ADDRESS_URL' => Yii::app()->createUrl('myaccount/address'),
						'MY_ACCOUNT_REMOVE_ADDRESS_URL' => Yii::app()->createUrl('myaccount/removeaddress'),
						'GET_STATES_URL' => Yii::app()->createUrl('myaccount/getstates'),
						'GET_STATES_BY_CODE_URL' => Yii::app()->createUrl('myaccount/getstatebycode'),
						'DEFAULT_BILLING' => Yii::t('profile', 'Default Billing Address'),
						'DEFAULT_SHIPPING' => Yii::t('profile', 'Default Shipping Address'),
						'ADD_ADDRESS' => Yii::t('profile', 'Add Address'),
						'EDIT_ADDRESS' => Yii::t('profile', 'Edit Address'),
						'CONFIRM_ADD_ADDRESS' => Yii::t('profile', 'Confirm Add Address'),
						'CONFIRM_EDIT_ADDRESS' => Yii::t('profile', 'Confirm Edit Address'),
					)
				) .
			');
		});',
		CClientScript::POS_END
	);
?>

<?php $this->renderPartial('_addressform', array('model' => $model)); ?>

<div class="address-info">
	<h4 class="title"><?= Yii::t('profile', 'Addresses'); ?></h4>
	<ul class="address-blocks">
		<?php if(count($activeAddresses) === 0): ?>
			<li class="address-block">
				<p>
					<span class="to-label"><?= Yii::t('profile', 'to'); ?></span>
					<?= Yii::t('profile', 'You have no saved addresses') ?><br>
					<span id="add-first-account-address" class="controls">
						<a href=""><?= Yii::t('profile', 'Add your default address to your account') ?>
						</a>
					</span>
				</p>
			</li>
		<?php else:?>
			<?php foreach($activeAddresses as $activeAddress):?>
				<li class="address-block" data-address-id="<?= $activeAddress->id ?>">
					<p class="webstore-label">
						<span class="to-label">
							<?= Yii::t('profile', 'to'); ?>
						</span>
						<span class="address-text">
							<?=
								(strtolower($activeAddress->address_label) != 'unlabeled address' ? $activeAddress->address_label . '<br>' : '') .
								$activeAddress->first_name . ' ' .
								$activeAddress->last_name . '<br>' .
								$activeAddress->address1 .'<br>'.
								(!empty($activeAddress->address2) ? $activeAddress->address2 . '<br>' : '') .
								$activeAddress->city . ' ' .
								$activeAddress->state . ' ' .
								$activeAddress->postal . '<br>' .
								$activeAddress->country_name;
							?>
						</span>
						<span class="controls">
							<a class="edit-account-address" href="#" data-address-id="<?= $activeAddress->id ?>">
								<?= Yii::t('profile', 'Edit Address'); ?>
							</a>
							<?= Yii::t('profile', 'or'); ?>
							<a class="remove-account-address delete" href="#" data-address-id="<?= $activeAddress->id ?>">
								<?= Yii::t('profile', 'Remove'); ?>
							</a>
						</span>
						<span class="default-address">
							<?php
								if ($activeAddress->id == $model->default_billing_id &&
									$activeAddress->id == $model->default_shipping_id)
								{
									echo '<span class="superscript-label  default-shipping">' .
										Yii::t('profile', 'Default') .
										'</span>';
								}
								else
								{
									echo ($activeAddress->id == $model->default_billing_id ?
											'<span class="superscript-label default-billing">' .
											Yii::t('profile', 'Default Billing Address') .
											'</span>' : '') .

										($activeAddress->id == $model->default_shipping_id ?
											'<span class="superscript-label default-shipping">' .
											Yii::t('profile', 'Default Shipping Address') .
											'</span>' : '');
								}
							?>
						</span>
					</p>
				</li>
			<?php endforeach;?>
		<?php endif;?>
		<li class="add">
			<a href="" class="small button add-account-address"><?= Yii::t('profile', 'Add New Address') ?></a>
		</li>
	</ul>
</div>