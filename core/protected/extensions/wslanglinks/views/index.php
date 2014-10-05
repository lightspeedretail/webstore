<div class="langlinks">
<?php foreach (_xls_avail_languages() as $langCode=>$langName)
	echo CHtml::link(ucfirst($langName),"?_lang=".$langCode)." "; ?>
</div>
