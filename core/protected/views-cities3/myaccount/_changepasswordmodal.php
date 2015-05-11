<div class="webstore-modal webstore-modal-overlay webstore-modal-narrow" id="modal-password">
	<section id="webstore-createaccount" class="narrow-modal-section">
		<header>
			<h1><?= Yii::t('profile', 'Change Password'); ?></h1>
			<a href="#" class="cancel"><?= Yii::t('forms', 'Cancel &amp; Discard Changes'); ?></a>
		</header>
		<form id="change-password-form">
			<div class="error-holder hide">
				<div class="form-error">
				</div>
			</div>
			<ol>
				<li class="field-container">
					<label class="placeheld"><?= Yii::t('forms', 'Password'); ?></label>
					<input type="password" name="password" required="" placeholder="<?= Yii::t('forms', 'New Password'); ?>">
				</li>
				<li class="field-container ">
					<label class="placeheld"><?= Yii::t('forms', 'Password'); ?></label>
					<input type="password" name="password_repeat" required="" placeholder="<?= Yii::t('forms', 'Verify Password'); ?>">
				</li>
			</ol>
			<footer class="submit">
				<input id="change-password-submit" type="submit" value="<?= Yii::t('forms', 'Change Password'); ?>">
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