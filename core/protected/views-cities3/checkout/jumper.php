<div id="wrapper">
	<div class="webstore-overlay webstore-modal-overlay webstore-overlay-narrow webstore-checkout">
		<section id="start">
			<div class="section-inner jumper">
				<h4>
					<?php echo Yii::t('global',"Please wait while your request is processed"); ?>
				</h4>
				<div class="field-container">
					<?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?>
				</div>
			</div>
			<?php echo $form; ?>
		</section>
	</div>
</div>