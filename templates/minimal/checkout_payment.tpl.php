
<fieldset>
<legend><?= _sp($_CONTROL->Name) ?></legend>

	<div class="six columns alpha omega">
		<span class="label"><?php echo _sp("Payment Method"); ?></span>
<!--		--><?php //if ($_CONTROL->Method) { $_CONTROL->Method->RenderAsDefinition();} ?>


		<div class="six columns alpha omega">
			<?php
			if ($_CONTROL->ModuleControl) {
				$_CONTROL->ModuleControl->Name = '';
				$_CONTROL->ModuleControl->RenderAsDefinition();
			}
			?>
		</div>


		<div class="six columns alpha omega">
			<?php
			if ($_CONTROL->MethodControl) {
				$_CONTROL->MethodControl->AutoRenderChildren = true;
				$_CONTROL->MethodControl->RenderAsDefinition();
			}
			?>
		</div>


		<?php if ($_CONTROL->ValidationError): ?>
		<span class="warning">
	                    <?= $_CONTROL->ValidationError ?>
	                </span>
		<?php endif; ?>
	</div>

</fieldset>



