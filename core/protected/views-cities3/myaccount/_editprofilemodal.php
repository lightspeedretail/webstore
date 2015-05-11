<div class="webstore-modal webstore-modal-overlay webstore-modal-narrow" id="modal-profile">
	<section id="webstore-createaccount" class="narrow-modal-section">
		<header>
			<h1><?= Yii::t('profile', 'Edit Profile'); ?></h1>
			<a href="#" class="cancel"><?= Yii::t('profile', 'Cancel &amp; Discard Changes'); ?></a>
		</header>
		<form id="edit-profile-form" class="error">
			<div class="error-holder hide">
				<div class="form-error">
				</div>
			</div>
			<ol>
				<li class="field-container">
					<label class="placeheld"><?= Yii::t('forms', 'Name'); ?></label>
					<input type="text" id="edit-first-name" name="first_name" placeholder="<?= Yii::t('forms', 'First name'); ?>" required="" class="split-2-first" autofocus="">
					<input type="text" id="edit-last-name" name="last_name" placeholder="<?= Yii::t('forms', 'Last name'); ?>" required="" class="split-2">
				</li>
				<li class="field-container ">
					<label class="placeheld"><?= Yii::t('forms', 'Email'); ?></label>
					<input type="email" id="edit-email" name="email" required="" placeholder="<?= Yii::t('forms', 'Email'); ?>">
				</li>
				<li class="field-container ">
					<label class="placeheld"><?= Yii::t('forms', 'Phone'); ?></label>
					<input type="tel" id="edit-phone" name="mainphone" placeholder="<?= Yii::t('forms', 'Phone'); ?>">
				</li>
				<li>
					<label class="checkbox">
						<input type="checkbox" id="edit-newsletter-subscribe" class="label-toggle" name="newsletter_subscribe">
						<?= Yii::t('forms', 'Allow us to send you emails about our products'); ?>
					</label>
				</li>
			</ol>
			<footer class="submit">
				<input type="submit" id="save-profile" value="Save Changes">
			</footer>
		</form>
		<button class="webstore-modal-close">
			<?=
				Yii::t(
					'forms',
					'Close'
				);
			?>
		</button>
	</section>
</div>
