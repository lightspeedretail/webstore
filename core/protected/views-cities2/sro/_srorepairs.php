<div id="genericcart">
	<div class="row">
        <div class="col-sm-4"><span class="cart_header"><?= Yii::t('cart','Description'); ?></span></div>
        <div class="col-sm-3"><span class="cart_header"><?= Yii::t('cart','Purchase Date'); ?></span></div>
        <div class="col-sm-3"><span class="cart_header"><?= Yii::t('cart','Serial Number'); ?></span></div>
	</div>

	<?php foreach($model->sroRepairs as $item): ?>
		<div class="row">
		    <div class="col-sm-4"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></div>
		    <div class="col-sm-3"><?= $item->purchase_date ?></div>
		    <div class="col-sm-3"><?= $item->serial_number ?></div>
		</div>
	<?php endforeach; ?>

</div>
