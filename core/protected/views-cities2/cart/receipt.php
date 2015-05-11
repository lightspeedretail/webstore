<?php

//$model for this view file is Cart::model()

if($model): ?>
<div id="orderdisplay" class="col-sm-12">
	<div class="row">
        <h1 class="center"><?= Yii::t('cart','Thank you for your order!') ?></h1>
	</div>
    <div id="order-info">
    <div class="row">
        <div class="col-sm-2 cartlabel"><?php echo Yii::t('cart','Order ID') ?>:</div>
        <div class="col-sm-3"><?= $model->id_str; ?></div>
        <div class="col-sm-3 cartlabel"><?php echo Yii::t('cart','Date') ?>:</div>
        <div class="col-sm-3"><?= $model->datetime_cre; ?></div>
	</div>
	<div class="row">
        <div class="col-sm-2 cartlabel"><?php echo Yii::t('cart','Status') ?>:</div>
        <div class="col-sm-3"><?= $model->status; ?></div>
        <div class="col-sm-3 cartlabel"><?php echo Yii::t('cart','Payment') ?>:</div>
        <div class="col-sm-3"><?= $model->payment->payment_name; ?></div>
	</div>
	<div class="row spaceafter">
        <div class="col-sm-2 cartlabel"><?php echo Yii::t('cart','Shipping') ?>:</div>
        <div class="col-sm-3"><?= $model->shipping->shipping_data; ?></div>
        <div class="col-sm-3 cartlabel"><?php echo Yii::t('cart','Authorization') ?>:</div>
        <div class="col-sm-3"><?= $model->payment->payment_data; ?></div>
    </div>

	<div class="clearfix spaceafter"></div>

    <div class="row spaceafter">
        <div class="ten column alpha omega"><span class="cartlabel cartNotes"><?php echo Yii::t('cart','Notes') ?>:</span></div>
        <div class="ten column offset-by-one"><?= nl2br($model->printed_notes) ?></div>
    </div>
    </div>

  	<?php echo $this->renderPartial('/cart/_cartitems',array('model'=>$model),true); ?>

	<?php echo $this->renderPartial('/cart/_facebookwall',array(),true); ?>

	<?php echo $this->renderPartial('/cart/_googleconversion',array('model'=>$model),true); ?>

</div>


<?php endif; ?>