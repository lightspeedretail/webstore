<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
			<tr>
				<td class="visible1-mobile tax-mobile"><span class="cart_label"><?= $tax; ?></span></td>
				<td class="hidden1-mobile"><span class="cart_label"><?= $tax; ?></span></td>
				<td class="cart_price"><?= _xls_currency($taxvalue); ?></span></td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>