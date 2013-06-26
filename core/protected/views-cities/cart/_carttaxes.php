<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
			<div class="row-fluid remove-bottom">
		        <div class="span2 offset6 cart_price"><span class="cart_label"><?= $tax; ?></span></div>
		        <div class="span2 cart_price"><?= _xls_currency($taxvalue); ?></div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>