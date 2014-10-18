<ul id="menutree">
	<?php foreach($this->MenuTree as $MenuBranch):
		echo
		'<li><a href="'.$MenuBranch['link'].'">'.$MenuBranch['label'].'</a></li>';
	endforeach;  ?>
</ul>