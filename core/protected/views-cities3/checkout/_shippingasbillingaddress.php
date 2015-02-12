<label class="shippingasbilling">
	<input type="checkbox"
		   checked="checked"
		   value="<?= $checkbox['id'] ?>"
		   onclick="$('.address-form').fadeToggle();$('footer input').fadeToggle();"
		   name="<?= $checkbox['name'] ?>"
		<?= _xls_get_conf('SHIP_SAME_BILLSHIP') == 1 ? 'disabled' : '' ?>
		/>
	<div class="text">
		<?= Yii::t('checkout', $checkbox['label']) ?>
		<p class="address-abbr">
			<?= $checkbox['address']; ?>
		</p>
	</div>
</label>