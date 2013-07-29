<div class="span9">
	<div class="hero-unit">
		<h4 class="newalert">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Theme Upgrade Available</h4>

		<div class="editinstructions">
			<strong>Your selected theme <?php echo strtoupper(Yii::app()->theme->name) ?> has an update available, version <?php echo $oXML->themedisplayversion ?>.</strong>
			<p>
				<?php
					echo "To update your Web Store to this latest version, follow the upgrade directions found under <strong>".
						CHtml::link('Upgrade Theme',$this->createUrl("theme/manage",array("n"=>1)))."</strong>.";
				?>

			</p>

			<strong>Release Notes:</strong></br>
			<p>
				<?php echo $oXML->releasenotes; ?>
			</p>
		</div>

	</div>

</div>
