<?php
$this->layout='//layouts/column1';

//$model for this view file is Cart::model()

if($model): ?>
<div id="orderdisplay" class="">
	<div class="row">
        <h1 class=""><?= Yii::t('cart','Thank you for your order!') ?></h1>
	</div>
    <div class="row">
        <div class="col-sm-2"><span class="cartlabel"><?php echo Yii::t('cart','Order ID') ?>:</span></div>
        <div class="col-sm-3"><?= $model->id_str; ?></div>
        <div class="col-sm-3"><span class="cartlabel"><?php echo Yii::t('cart','Date') ?>:</span></div>
        <div class="col-sm-3"><?= $model->datetime_cre; ?></div>
	</div>
	<div class="row">
        <div class="col-sm-2"><span class="cartlabel"><?php echo Yii::t('cart','Status') ?>:</span></div>
        <div class="col-sm-3"><?= $model->status; ?></div>
        <div class="col-sm-3"><span class="cartlabel"><?php echo Yii::t('cart','Payment') ?>:</span></div>
        <div class="col-sm-3"><?= $model->payment->payment_name; ?></div>
	</div>
	<div class="row spaceafter">
        <div class="col-sm-2"><span class="cartlabel"><?php echo Yii::t('cart','Shipping') ?>:</span></div>
        <div class="col-sm-3"><?= $model->shipping->shipping_data; ?></div>
        <div class="col-sm-3"><span class="cartlabel"><?php echo Yii::t('cart','Authorization') ?>:</span></div>
        <div class="col-sm-3"><?= $model->payment->payment_data; ?></div>
    </div>

	<div class="clearfix spaceafter"></div>

    <div class="row spaceafter">
        <div class="ten column alpha omega"><span class="cartlabel"><?php echo Yii::t('cart','Notes') ?>:</span></div>
        <div class="ten column offset-by-one"><?= nl2br($model->printed_notes) ?></div>
    </div>

  	<?php echo $this->renderPartial('/cart/_cartitems',array('model'=>$model),true); ?>

	<?php echo $this->renderPartial('/cart/_facebookwall',array(),true); ?>

	<?php echo $this->renderPartial('/cart/_googleconversion',array('model'=>$model),true); ?>

</div>


<?php endif; ?>