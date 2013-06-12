<div class="span9">
	<div class="hero-unit">
		<h4 class="newalert">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New Version Alert</h4>

		<div class="editinstructions">
			<strong>Web Store version <?php echo $oXML->displayversion ?> is now available.</strong>
			<p>
				<?php if (_xls_get_conf('LIGHTSPEED_HOSTING',0)=="1"): ?>
					Because your are a hosted customer, your Web Store will be updated for you. We are including this notice as a courtesy only.
				<?php endif; ?>

				<?php if (_xls_get_conf('LIGHTSPEED_HOSTING',0)=="0")
					if(Yii::app()->user->fullname=="LightSpeed")
						echo "You can download this update by going to our website and clicking Downloads.";
					else echo "You can download this update directly from ".CHtml::link($oXML->downloadurl,$oXML->downloadurl).".";
				?>

			</p>

			<strong>Release Notes:</strong></br>
			<p>
				<?php echo $oXML->releasenotes; ?>
			</p>
		</div>

	</div>

</div>
