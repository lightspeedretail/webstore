<div id="genericcart">
	<div class="row-fluid">
        <div class="span4"><span class="cart_header"><?= Yii::t('cart','Description'); ?></span></div>
        <div class="span2 rightitem"><span class="cart_header"><?= Yii::t('cart','Price'); ?></span></div>
        <div class="span1">&nbsp;</div>
        <div class="span1 centeritem"><span class="cart_header"><?= Yii::t('cart','Qty'); ?></span></div>
        <div class="span1">&nbsp;</div>
        <div class="span2 rightitem"><span class="cart_header"><?= Yii::t('cart','Total'); ?></span></div>
	</div>

	<?php foreach($model->sroItems as $item): ?>
		<div class="row-fluid">
		    <div class="span4">
		        <a href="<?php echo $item->product->Link; ?>"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></a>
		    </div>

		    <div class="span2 cart_price">
			    <?= ($item->discount) ? sprintf("<strike>%s</strike> ", _xls_currency($item->sell_base))._xls_currency($item->sell_discount) : _xls_currency($item->sell);  ?>
		    </div>

		    <div class="span1 centeritem cartdecor">x</div>

		    <div class="span1 centeritem"><span class="cart_qty"><?php
			        if (isset($this->intEditMode) && $this->intEditMode)
					    echo CHtml::textField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('class'=>'cart_qty_box'));
				        else echo $item->qty;
			    ?></span></div>

		    <div class="span1 centeritem cartdecor">=</div>

		    <div class="span2 cart_price"><?= _xls_currency($item->sell_total) ?></div>

		</div>

	<?php endforeach; ?>

</div>
