<div class="address-new edit">
	<h4 id="address-form-title"></h4>
	<div class="error-holder hide">
		<div class="form-error">
		</div>
	</div>
	<form class="address-form outer-address-form" id="account-address-form" novalidate="novalidate">
		<input type="hidden" name="id" id="recipient-address-id">
		<ol class="field-containers-small field-container-gap">
			<li class="field-container field-container-split">
				<label class="placeheld" for="recipient-firstname">
					<?= Yii::t('profile', 'First Name'); ?>
				</label>
				<input type="text" name="first_name" placeholder="First Name" id="recipient-firstname"
					   class="no-right-border" required="" autofocus="">
			</li>
			<li class="field-container field-container-split field-container-split-latter">
				<label class="placeheld" for="recipient-lastname"><?= Yii::t('profile', 'Last Name'); ?></label>
				<input placeholder="Last Name" required="required" name="last_name" id="recipient-lastname" type="text">
			</li>
			<li class="field-container-toggle">
				<a href="#" id="recipient-company-toggle">
					<?= Yii::t('profile', 'Company'); ?>
				</a>
			</li>
			<li class="field-container company-container" style="display: none;">
				<label class="placeheld" for="recipient-company"><?= Yii::t('profile', 'Company'); ?></label>
				<input type="text" name="company" placeholder="Company" class="no-top-border" id="recipient-company">
			</li>
		</ol>

		<ol class="select-options">
			<li>
				<label class="checkbox" for="recipient-residential">
					<input type="checkbox" name="residential"
						   id="recipient-residential" class="label-toggle">
					<?= Yii::t('profile', 'This is a residential address'); ?>
				</label>
			</li>
		</ol>

		<ol class="field-containers-small field-container-gap">
			<li class="field-container field-container-nobottomborder">
				<label class="placeheld" for="recipient-address1"><?= Yii::t('profile', 'Address 1'); ?></label>
				<input type="text" name="address1" placeholder="Mailing address" id="recipient-address1"
					   class="no-bottom-border" required="">
			</li>
			<li class="field-container">
				<label class="placeheld" for="recipient-address2"><?= Yii::t('profile', 'Address 2'); ?></label>
				<input type="text" name="address2" placeholder="Suite, Floor, etc." id="recipient-address2">
			</li>
			<li class="fieldgroup city-fieldgroup">
				<ol>
					<li class="field-container">
						<label class="placeheld" for="recipient-zip"><?= Yii::t('profile', 'Zip'); ?></label>
						<input type="text" name="postal" size="6" placeholder="Zip" id="recipient-zip"
							   required="">
					</li>
					<li class="field-container">
						<label class="placeheld" for="recipient-city"><?= Yii::t('profile', 'City'); ?></label>
						<input type="text" name="city" size="26" placeholder="City" id="recipient-city"
							   required="">
					</li>
					<li class="field-container">
						<label class="placeheld"
							   for="recipient-state-code"><?= Yii::t('profile', 'State / Province'); ?></label>
						<input type="text" size="4" placeholder="State" id="recipient-state-code"
							   required="">
						<input id="recipient-state-id" type="hidden" name="state_id">
					</li>
				</ol>
			</li>
			<li class="field-container country-container">
				<select name="country_id" id="recipient-country" class="no-style-select">
					<?php
						foreach (Country::sortShippingCountries('US') as $recipientCountry):
							printf(
								"<option value='%s'>%s</option>",
								$recipientCountry->id,
								$recipientCountry->country
							);
						endforeach;
					?>
				</select>
			</li>
		</ol>

		<ol class="select-options">
			<li>
				<label class="checkbox">
					<input type="checkbox" name="makeDefaultShipping" id="make-default-shipping" class="label-toggle"><?= Yii::t('profile', ' Default Shipping'); ?>
				</label>
				<label class="checkbox">
					<input type="checkbox" name="makeDefaultBilling" id="make-default-billing" class="label-toggle"><?= Yii::t('profile', 'Default Billing'); ?>
				</label>
			</li>
		</ol>

		<footer class="submit submit-small">
			<input id="confirm-address" class="button" type="submit">
			<p>
				<a id="cancel-address" class="alternate" href="">
					<?= Yii::t('profile', 'Cancel &amp; Discard Changes'); ?>
				</a>
			</p>
		</footer>
	</form>
</div>