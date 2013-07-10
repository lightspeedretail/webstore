<?php $this->beginContent('//layouts/main'); ?>
<div class="row-fluid">
	<div class="span9">

		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
	        'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/css/images/breadcrumbs_home.png'), array('/site/index')),
			'separator'=>' / ',
	        ));	?> <!-- breadcrumbs -->
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
			'block'=>true, // display a larger alert block?
			'fade'=>true, // use transitions?
			'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
			'alerts'=>array( // configurations per alert type
				'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'danger'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			),
			)); ?><!-- flash messages -->
		<div id="viewport" class="row-fluid">
		    <?php echo $content; ?>
	    </div>
	</div>

	<div class="span3">

        <div id="shoppingcart">
			<?= $this->renderPartial('/site/_sidecart',null, true); ?>
		</div>

        <div id="shoppingcartcheckout" onclick="window.location.href='<?php echo Yii::app()->createUrl('cart/checkout') ?>'">
            <div class="checkoutlink"><?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')) ?></div>
            <div class="checkoutarrow"><?php echo CHtml::image(Yii::app()->theme->baseUrl."/css/images/checkoutarrow.png"); ?></div>
        </div>

        <div id="shoppingcarteditcart" onclick="window.location.href='<?php echo Yii::app()->createUrl('/cart') ?>'">
            <div class="editlink"><?php echo CHtml::link(Yii::t('cart','Edit Cart'),array('/cart')) ?></div>
        </div>

        <div id="sidebar" class="span12">
			<?php $this->widget("application.extensions.wsborderlookup.wsborderlookup",array()); ?>
	        <?php if(_xls_get_conf('ENABLE_WISH_LIST'))
				echo $this->renderPartial('/site/_wishlists',array(),true); ?>
	    </div>
	</div>
</div>

<?php $this->endContent();