


	<legend><?= _sp($_CONTROL->Name) ?> <?php if ($_CONTROL->Wait) $_CONTROL->Wait->Render(); ?></legend>

		<div class="four columns alpha">
			<span class="label"><?php echo _sp("Shipping Method"); ?></span>
			<?php if ($_CONTROL->Module) $_CONTROL->Module->RenderAsDefinition(); ?>
		</div>

		<div class="four columns alpha omega">
			<span class="label"><?php echo _sp("Delivery Speed"); ?></span>
			<?php if ($_CONTROL->Method) $_CONTROL->Method->RenderAsDefinition(); ?>
			<?php
			if ($_CONTROL->Enabled) {
				$_CONTROL->Price->Render();

				if ($_CONTROL->Label->Visible)
					print("&nbsp;&ndash;&nbsp;");
			}

			$_CONTROL->Label->Render();
			?>
			<?php if ($_CONTROL->ValidationError): ?>
				<span class="warning">
                    <?= $_CONTROL->ValidationError ?>
                </span>
			<?php endif; ?>
		</div>

