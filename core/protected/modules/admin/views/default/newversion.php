<div class="span9">
	<div class="hero-unit documentation">
		<h4 class="newalert">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New Version Alert</h4>

		<div class="editinstructions">
			<strong>Web Store version <?php echo $oXML->displayversion ?> is now available.</strong>
			<p>
				<?php if (_xls_get_conf('LIGHTSPEED_HOSTING',0)=="1"): ?>
					Because you are a hosted customer, your Web Store will be updated for you. We are including this notice as a courtesy only.
				<?php endif; ?>

				<?php if (_xls_get_conf('LIGHTSPEED_HOSTING',0)=="0")
					echo "You can apply this update by clicking on <strong>".
						CHtml::link('APPLY WEBSTORE UPDATE',$strUpdateUrl)."</strong>.";
				?>

			</p><p>Note, you can also enable Auto Update in <?php echo CHtml::link('System Configuration',$this->createUrl('/admin/system/edit?id=1')); ?> to apply future updates automatically.</p>

			<strong>Release Notes:</strong><br>
			<iframe class="span9 documentation" scrolling="no" src="<?php echo $strReleaseNotesUrl; ?>"></iframe>
		</div>

	</div>

</div>
