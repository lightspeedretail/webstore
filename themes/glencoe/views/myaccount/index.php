
<div id="orderdisplay">


		<div class="row">
	        <div class="col-sm-12">
	            <h1 id="myaccountHeader"><?= Yii::t('global','Welcome'); ?> <?= $model->first_name ?></h1>
	        </div>
	        <div class="col-sm-12 subcategories">
		        <?= CHtml::link(Yii::t('global','Edit Profile'),Yii::app()->createUrl('myaccount/edit')); ?>
	        </div>
		</div>



	<div class="clearfix spaceafter"></div>

	<fieldset>
		<div class="row">
			<div class="col-sm-12"><h2><?= Yii::t('global','My Addresses') ?>:</h2></div>
			<div class="col-sm-12 subcategories"><?= CHtml::link(Yii::t('global','Add new address'),Yii::app()->createUrl('myaccount/address')); ?></div>
		</div>

		<div class="row">
			<?php foreach($model->customerAddresses as $objAddress): ?>
				<div class="col-xs-6 col-sm-3 myaddress">
					<?= CHtml::link("<strong>".$objAddress->address_label."</strong><br>".$objAddress->address1."<br>".$objAddress->city." ".$objAddress->state." ".$objAddress->postal.
						($objAddress->id==$model->default_billing_id ? "<br><span class='default'>".Yii::t('global','Default Billing Address')."</span>" : "").
						($objAddress->id==$model->default_shipping_id ? "<br><span class='default'>".Yii::t('global','Default Shipping Address')."</span>" : ""),
						Yii::app()->createUrl('myaccount/address',array('id'=>$objAddress->id))); ?>
				</div>
			<?php endforeach; ?>
			<div class="clearfix spaceafter"></div>

		</div>
	</fieldset>
	<div class="clearfix spaceafter"></div>

	<fieldset>
	    <div class="row">
	        <div class="col-sm-9"><h2><?= Yii::t('global','My Orders') ?>:</h2></div>
	    </div>


		<?php if(count($model->carts(array('scopes'=>'complete'))) > 0): ?>
		  <?php foreach($model->carts(array('scopes'=>'complete')) as $objCart): ?>
	        <div class="row">
	            <div class="col-xs-4 col-sm-2">
	                <?= CHtml::link($objCart->id_str,Yii::app()->createUrl('cart/receipt',array('getuid'=>$objCart->linkid))); ?>
	            </div>
	            <div class="col-xs-4 col-sm-3">
					<?= $objCart->DatetimeCreated; ?>
	            </div>
	            <div class="col-xs-4 col-sm-3">
					<?= Yii::t('global',$objCart->status); ?>
	            </div>
	        </div>
		  <?php endforeach; ?>
		<?php else: ?>
			<?= Yii::t('global','You have not placed any orders with us yet'); ?>
		<?php endif; ?>

	</fieldset>
	<div class="clearfix spaceafter"></div>

	<fieldset>
	    <div class="row">
	        <div class="col-sm-12"><h2><?= CHtml::link(Yii::t('global','My Wish Lists'),Yii::app()->createUrl('/wishlist')); ?></h2></div>
	        <div class="col-sm-12 subcategories"><?= CHtml::link(Yii::t('global','Click here to create a wish list.'),Yii::app()->createUrl('wishlist/create')); ?></div>
	    </div>
	    <div class="row" style="margin-left: 0px">
			<?php if(count($model->wishlists) > 0): ?>
			<ul class="col-sm-12 whishlists_row">
				<?php foreach($model->wishlists as $objWishlist): ?>
		            <li >
			            <?= CHtml::link($objWishlist->registry_name,Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code))); ?>
		            </li>
				<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<?= Yii::t('global',"You have not created any wish list yet."); ?><br>

			<?php endif; ?>
	    </div>
	</fieldset>

	<?php if(_xls_get_conf('ENABLE_SRO')):   ?>
	<fieldset>
	    <div class="row">
	        <div class="col-sm-12">
		        <h2><?= Yii::t('global','My Repairs') ?></h2>
	        </div>
	    </div>
	    <div class="row">
			<?php if(count($model->sros) > 0): ?>
			<?php foreach($model->sros as $sro): ?>
	            <div class="col-sm-3">
		            <?php echo CHtml::link($sro->ls_id,Yii::app()->createUrl('sro/view',array('code'=>$sro->GenerateLink()))); ?>
	            </div>
				<?php endforeach; ?>
			<?php else: ?>
		    <div class="col-sm-12">
				<?php echo Yii::t('global',"You have not placed any repair orders with us."); ?>
			</div>
			<?php endif; ?>
	    </div>
	</fieldset>
	<?php endif;  ?>

</div>