<div class="langlinks">
<?php foreach (_xls_avail_languages() as $key=>$value)
	echo CHtml::link($value,"?_lang=".$key)." "; ?>
</div>
