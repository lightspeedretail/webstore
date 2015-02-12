<div id="orderdisplay">

	<fieldset class="row-fluid">
		<div class="span8">
			<h2><?= Yii::t('global','Welcome'); ?> <?= $model->first_name ?></h2>
		</div>
		<div class="span4 subcategories right">
			<?= CHtml::link(Yii::t('global','Edit Profile'),Yii::app()->createUrl('myaccount/edit')); ?>
		</div>
	</fieldset>

	<div class="clearfix spaceafter"></div>

	<fieldset class="row-fluid">

		<div class="span8"><h2><?= Yii::t('global','My Addresses') ?>:</h2></div>
		<div class="span4 subcategories right"><?= CHtml::link(Yii::t('global','Add new address'),Yii::app()->createUrl('myaccount/address')); ?></div>

		<div class="clearfix spaceafter"></div>

		<div class="row-fluid">
			<?php foreach(CustomerAddress::getAllAddresses() as $objAddress): ?>
				<div class="span4 myaddress">
					<?= CHtml::link("<strong>".$objAddress->address_label."</strong><br>".$objAddress->htmlblock.
						($objAddress->id==$model->default_billing_id ? "<br><span class='default'>".Yii::t('global','Default Billing Address')."</span>" : "").
						($objAddress->id==$model->default_shipping_id ? "<br><span class='default'>".Yii::t('global','Default Shipping Address')."</span>" : ""),
						Yii::app()->createUrl('myaccount/address',array('id'=>$objAddress->id))); ?>
				</div>
			<?php endforeach; ?>
			<div class="clearfix spaceafter"></div>

		</div>
	</fieldset>

	<div class="clearfix spaceafter"></div>

	<fieldset class="row-fluid">

		<div class="span12"><h2><?= Yii::t('global','My Orders') ?>:</h2></div>
		<div id="order-info">
			<?php if(count($model->carts(array('scopes'=>'complete'))) > 0): ?>
				<?php foreach($model->carts(array('scopes'=>'complete')) as $objCart): ?>
					<div class="row-fluid">
						<div class="span2 order-id">
							<?= CHtml::link($objCart->id_str,Yii::app()->createUrl('cart/receipt',array('getuid'=>$objCart->linkid))); ?>
						</div>
						<div class="span3 order-date">
							<?= $objCart->DatetimeCreated; ?>
						</div>
						<div class="span4 order-status">
							<?= Yii::t('global',$objCart->status); ?>
						</div>
					</div>

				<?php endforeach; ?>
			<?php else: ?>
				<?= Yii::t('global','You have not placed any orders with us yet'); ?>
			<?php endif; ?>
		</div>
		<div class="clearfix spaceafter"></div>
	</fieldset>

	<div class="clearfix spaceafter"></div>


	<?php if(_xls_get_conf('ENABLE_WISH_LIST')):   ?>
		<fieldset class="row-fluid">

			<div class="span7"><h2><?= CHtml::link(Yii::t('global','My Wish Lists'),Yii::app()->createUrl('/wishlist')); ?></h2></div>
			<div class="span5 subcategories right"><?= CHtml::link(Yii::t('global','Click here to create a wish list'),Yii::app()->createUrl('wishlist/create')); ?></div>

			<div class="clearfix spaceafter"></div>

			<div id="wishlist-info" class="row-fluid">
				<?php if(count($model->wishlists) > 0): ?>
					<?php foreach($model->wishlists as $objWishlist): ?>
						<div class="span3">
							<?= CHtml::link($objWishlist->registry_name,Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code))); ?>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<?= Yii::t('global',"You have not created any wish list yet."); ?>
					<div class="clearfix spaceafter"></div>

				<?php endif; ?>
			</div>
		</fieldset>
	<?php endif;  ?>

	<?php if(_xls_get_conf('ENABLE_SRO')):   ?>
		<fieldset id="srorepair" class="span12">
			<div class="row-fluid">
				<div class="span8"><h2><?= Yii::t('global','My Repairs') ?></h2></div>
			</div>
			<div class="row-fluid">
				<?php if(count($model->sros) > 0): ?>
					<?php foreach($model->sros as $sro): ?>
						<div class="span3">
							<?php echo CHtml::link($sro->ls_id,Yii::app()->createUrl('sro/view',array('code'=>$sro->GenerateLink()))); ?>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<?= Yii::t('global',"You have not placed any repair orders with us."); ?><br>
				<?php endif; ?>
			</div>
		</fieldset>
	<?php endif;  ?>

</div>
