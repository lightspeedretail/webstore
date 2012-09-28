<fieldset>
	<legend><?php echo _sp('Promo Code') ?></legend>
	<div class="row">
		<?php _xt('Enter a Promotional Code here to receive a discount.') ?>
	</div>
	<div class="row">
		<div class="three columns alpha" >
			<?php $this->txtPromoCode->RenderWithError() ?>
		</div>
		<div class="three columns offset-by-one omega" >
			<?php $this->btnPromoVerify->Render('Text=' . _sp("Apply Promo Code")) ?>
		</div>
	</div>
	<div class="row">
		<div class="five columns alpha omega" ><?php $this->lblPromoErr->Render(); ?></div>
	</div>
</fieldset>