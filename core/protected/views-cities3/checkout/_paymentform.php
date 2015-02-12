<div class="subform payform_<?= $moduleId ?>">
	<?php
	/* note we purposely do not echo our renderBegin() and renderEnd() to prevent nested <form> tags */
	$form->renderBegin();
	foreach($form->getElements() as $element)
	{
		echo $element->render();
	}

	$form->renderEnd();
	?>
</div>