<?php echo CHtml::form(); ?>
	<div id="langdrop">
		<?php echo CHtml::dropDownList('_lang', $currentLang, _xls_avail_languages(), array('submit' => '')); ?>
	</div>
<?php echo CHtml::endForm(); ?>