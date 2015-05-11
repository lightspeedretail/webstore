<?php
if ($colorTextBoxBorder != '' || $colorTextBoxBorderFocus != '')
{
	echo '<style type="text/css">';
	if ($colorTextBoxBorder != '')
	{
		echo sprintf(".myForm input[type=text], .myForm input[type=password]  { %s }\n", $colorTextBoxBorder);
	}

	if ($colorTextBoxBorderFocus != '')
	{
		echo sprintf(".myForm input[type=text]:focus, .myForm input[type=password]:focus { %s }\n", $colorTextBoxBorderFocus);
	}
	echo '</style>';
}

?>
<?php if ($logoUrl != ''): ?>
<div id="divLogo" style="<?= $colorLogoBackground.$colorLogoBorder ?>">
	<img id="imgBanner" src="<?= $logoUrl ?>" />
</div>
<?php endif; ?>

<div id="divInstructions" <?php echo $config['hideInstructions'] == 1 ? 'style="display:none;"' : '';?> >
	<img alt="This transaction is secure" src="https://transport.merchantware.net/v4/style/lockgreen.gif" class="vam">
	<span>Enter your credit card information below and click Submit.</span>
</div>

<form id="cayanForm" class="myForm" autocomplete="off" style="<?= $colorContainerBackground.$colorContainerBorder ?>">
	<div id="divCardEntry">
		<h4 style="<?= $colorContainerBorder ?>">Sale Amount: $1.23</h4>

		<div id="divKeyed" style="margin-top: 15px">
			<div style="margin-top: 20px;">
				<label for="txtKeyedCardNumber" class="field">
					Card Number<br /><span class="help">Key in card number here</span></label>
				<input type="<?php echo $config['maskCardNumber'] == 1 ? 'password' : 'text'; ?>"
				       maxlength="19" tabindex="2" placeholder="enter card number" />
			</div>

			<div id="divExpDate" class="mt15">
				<label class="field" for="txtExpDate" style="display: inline-block; text-align: right; font-size: 13px; font-weight: 700;">
					Expiration Date<br /><span class="help">Enter date as mmyy</span></label>
				<input name="txtExpDate" type="text" autocomplete="off" maxlength="4" id="txtExpDate" tabindex="3" class="tb short" placeholder="mmyy"/>
			</div>

			<div id="divCVV" class="mt15">
				<label class="field" for="txtCVV">CVV<br /><span class="help">3 or 4 digits</span></label>
				<input name="txtCVV" type="text" autocomplete="off" maxlength="4" id="txtCVV" tabindex="4" class="tb short" placeholder="xxx" />
			</div>

			<div id="divKeyedCardHolder" class="mt15">
				<label class="field" for="txtKeyedCardholder">Cardholder<br /><span class="help">Name on the card</span></label>
				<input name="txtKeyedCardholder" type="text" autocomplete="off" value="Test Customer" maxlength="50" id="txtKeyedCardholder" tabindex="6" class="tb long" placeholder="cardholder"/>
			</div>

			<div id="divKeyedStreet" class="mt15">
				<label class="field" for="txtKeyedStreet">Street Address<br /><span class="help">Address for the card</span></label>
				<input name="txtKeyedStreet" type="text" autocomplete="off" value="123 Test St" maxlength="25" id="txtKeyedStreet" tabindex="7" class="tb long" placeholder="street address"/>
			</div>

			<div id="divKeyedZip" class="mt15">
				<label class="field" for="txtKeyedZip">Zip Code<br /><span class="help">Zip code for the card</span></label>
				<input name="txtKeyedZip" type="text" autocomplete="off" value="12345" maxlength="5" id="txtKeyedZip" tabindex="8" class="tb short" placeholder="zip code" onkeypress="return blockSubmit(event);" />
			</div>

			<div id="divKeyedDowngrade" class="mt15" <?php echo $config['hideDowngradeMessage'] == 1 ? 'style="display:none;"' : ''; ?> >
				<span class="help">Not entering the street address or zip code may result in additional fees.</span>
			</div>
		</div>

		<div id="divCustomButtons" class="buttonMargin">
			<a href="#"><img title="Click here to submit this transaction." src="https://transport.merchantware.net/v4/style/buttons/submit.gif" /></a>
			<a href="#"><img title="Click here to cancel this transaction." src="https://transport.merchantware.net/v4/style/buttons/cancel2.gif" /></a>
		</div>

	</div>
</form>

<div id="additional" class="myForm" style="<?= $colorContainerBackground.$colorContainerBorder ?>">
	<h4 style="<?= $colorContainerBorder ?>">Transaction Details<br /><span class="help">Please confirm the transaction information below.</span></h4>
	<div>
		<div id="divOrderContainer">
			Order #:
			12345
		</div>
		<div id="divClerkIDContainer">
			Clerk ID / Register:
			123
		</div>

		<div class="right">
			<img id="PoweredByMerchantWARE" class="poweredByGrey" class="poweredBy" title="Powered by MerchantWARE" src="https://transport.merchantware.net/v4/style/mwstampg.png" style="border-width:0px;" />
		</div>

	</div>
</div>