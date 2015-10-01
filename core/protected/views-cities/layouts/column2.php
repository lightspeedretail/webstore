<?php $this->beginContent('//layouts/main'); ?>
<div class="row-fluid">
	<div class="span9">

		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
	        'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/css/images/breadcrumbs_home.png'), array('/site/index')),
			'separator'=>' / ',
	        ));	?> <!-- breadcrumbs -->
		<?= $this->renderPartial('/site/_flashmessages',null, true); ?><!-- flash messages -->
		<div id="viewport" class="row-fluid">
			<?php echo $content; ?>
		</div>
	</div>

	<div class="span3">
		<?php if (!_xls_get_conf('DISABLE_CART', false)): ?>
			<?= $this->renderPartial('/site/_sidecart', null, true); ?>

			<?php $checkoutUrl = Yii::app()->createUrl('cart/checkout') ?>
			<div id="shoppingcartcheckout"
				onclick="window.location.href='<?php echo $checkoutUrl ?>'">
				<div class="checkoutlink">
					<?= CHtml::link(Yii::t('cart', 'Checkout'), array('cart/checkout')); ?>
				</div>

				<div class="checkoutarrow">
					<?= CHtml::image(Yii::app()->theme->baseUrl."/css/images/checkoutarrow.png"); ?>
				</div>
			</div>

			<?php $cartUrl = Yii::app()->createUrl('/cart') ?>
			<div id="shoppingcarteditcart"
				 onclick="window.location.href='<?php echo $cartUrl ?>'">
				<div class="editlink">
					<?= CHtml::link(Yii::t('cart', 'Edit Cart'), array('/cart')) ?>
				</div>
			</div>
		<?php endif ?>

		<div id="sidebar" class="span12">
			<?php if(_xls_get_conf('ENABLE_WISH_LIST')): ?>
				<?= $this->renderPartial('/site/_wishlists', array(), true); ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
$this->endContent();
