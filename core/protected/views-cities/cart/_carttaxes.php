<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
			<tr>
				<td class="tax-mobile"><span class="cart_label visible1-mobile"><?= $tax; ?></span></td>
				<td><span class="cart_label hidden1-mobile"><?= $tax; ?></span></td>
				<td class="cart_price"><?= _xls_currency($taxvalue); ?></td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>