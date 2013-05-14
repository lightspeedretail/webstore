<?php

//$model for this view file is Cart::model()

if($model): ?>
<div id="orderdisplay" class="span12">
	<div class="row-fluid">
        <h1 class="center"><?= Yii::t('cart','Thank you for your order!') ?></h1>
	</div>
    <div class="row-fluid">
        <div class="span2"><span class="cartlabel"><?php echo Yii::t('cart','Order ID') ?>:</span></div>
        <div class="span3"><?= $model->id_str; ?></div>
        <div class="span3"><span class="cartlabel"><?php echo Yii::t('cart','Date') ?>:</span></div>
        <div class="span3"><?= $model->datetime_cre; ?></div>
	</div>
	<div class="row-fluid">
        <div class="span2"><span class="cartlabel"><?php echo Yii::t('cart','Status') ?>:</span></div>
        <div class="span3"><?= $model->status; ?></div>
        <div class="span3"><span class="cartlabel"><?php echo Yii::t('cart','Payment') ?>:</span></div>
        <div class="span3"><?= $model->payment->payment_name; ?></div>
	</div>
	<div class="row-fluid spaceafter">
        <div class="span2"><span class="cartlabel"><?php echo Yii::t('cart','Shipping') ?>:</span></div>
        <div class="span3"><?= $model->shipping->shipping_data; ?></div>
        <div class="span3"><span class="cartlabel"><?php echo Yii::t('cart','Authorization') ?>:</span></div>
        <div class="span3"><?= $model->payment->payment_data; ?></div>
    </div>

	<div class="clearfix spaceafter"></div>

    <div class="row-fluid spaceafter">
        <div class="ten column alpha omega"><span class="cartlabel"><?php echo Yii::t('cart','Notes') ?>:</span></div>
        <div class="ten column offset-by-one"><?= nl2br($model->printed_notes) ?></div>
    </div>

  	<?php echo $this->renderPartial('/cart/_cartitems',array('model'=>$model),true); ?>

	<?php echo $this->renderPartial('/cart/_facebookwall',array(),true); ?>

	<?php echo $this->renderPartial('/cart/_googleconversion',array('model'=>$model),true); ?>

</div>


<?php endif; ?>