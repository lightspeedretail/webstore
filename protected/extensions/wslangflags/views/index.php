<div class="langlinks">
	<?php foreach (_xls_avail_languages() as $key=>$value)
		echo CHtml::link(CHtml::image($this->assetUrl."/flags/".$key.".gif"),"?_lang=".$key)." "; ?>
</div>
