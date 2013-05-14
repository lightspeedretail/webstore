<?php /* The payment form is rendered hidden until needed */ ?>

<div id="payform<?= $moduleName ?>" style="display: none" class="span9">
	<?php
		/* note we purposely do not echo our renderBegin() and renderEnd() to prevent nested <form> tags */
		$form->renderBegin();
		echo $form->renderBody();
		$form->renderEnd()
    ?>
</div>