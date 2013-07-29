<div class="span9">
	<div class="hero-unit">
		<h4 class="newalert">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New Version Alert</h4>

		<div class="editinstructions">
			<strong>Web Store version <?php echo $oXML->displayversion ?> is now available.</strong>
			<p>
				<?php if (_xls_get_conf('LIGHTSPEED_HOSTING',0)=="1"): ?>
					Because you are a hosted customer, your Web Store will be updated for you. We are including this notice as a courtesy only.
				<?php endif; ?>

				<?php if (_xls_get_conf('LIGHTSPEED_HOSTING',0)=="0")
					echo "You can apply this update by clicking on <strong>".
						CHtml::link('APPLY WEBSTORE UPDATE',$this->createUrl("upgrade/index",array('patch'=>$oXML->autopathfile)))."</strong>.";
				?>

			</p>

			<strong>Release Notes:</strong></br>
			<p>
				<?php echo $oXML->releasenotes; ?>
			</p>
		</div>

	</div>

</div>
