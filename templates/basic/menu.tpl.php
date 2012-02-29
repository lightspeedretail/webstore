<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Basic template: Products menu bar including javascript breakout menus 
 *
 * 
 *
 */

function print_childs($categ){
	
	$childs = $categ->categ_childs;
	
	if(!$childs || (count($childs) == 0)){
		echo "</a>";
		return;
	}
		
		echo "<img src=\"" . templateNamed('css')  . "/images/arrow-right.gif\" class=\"arrow\" alt=\"Submenu\" /></a><ul>\n";
	foreach($childs as $category){
		
		if(!$category->HasChildOrProduct())
			continue;
		
		?>
		
			<li><a href="<?= $category->Link; ?>"><?= $category->Name; ?><?php print_childs($category); ?></li>
		<?php
		
	}
	echo "</ul>\n";
	
	
}

function print_families(){

	$strLabel=_xls_get_conf('ENABLE_FAMILIES_MENU_LABEL' , 'By Manufacturer');
	echo '<li><a href="#">'.$strLabel;
	echo '<img src="'.templateNamed('css').'/images/arrow-right.gif" class="arrow" style="margin: 1px 0 0 10px;" alt="'.$strLabel.'" /></a>';
	echo '<ul>';
	$families = Family::LoadAll();
	foreach($families as $family) {
		echo '<li><a href="index.php?family='.urlencode($family->Family).'">'.$family->Family.'</a></li>';
	}
	echo '</ul></li>';	

}
?>
		
		<div id="nav_products">
			<ul>
				<li><a href="javascript:{}"><?php _xt('Products'); ?></a>
					<ul>
						<?php if(_xls_get_conf('ENABLE_FAMILIES', 0)==2)
							print_families();
						?>
						<?php foreach($this->menu_categories as $category):  ?>	
							<li><a href="<?= $category->Link; ?>"><?= $category->Name; ?><?php print_childs($category); ?></li>
						<?php endforeach; ?>
					
						<?php if(_xls_get_conf('ENABLE_FAMILIES', 0)==1)
							print_families();
						?>

					</ul>
				</li>
			</ul>
		</div>

	
