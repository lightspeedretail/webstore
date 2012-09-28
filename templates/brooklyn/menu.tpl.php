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
 * template Products menu bar including javascript breakout menus
 *
 * 
 *
 */

function print_childs($categ) {
	
	$childs = $categ->categ_childs;
	
	if(!$childs || (count($childs) == 0)){
		echo "</a>";
		return;
	}
		
	echo "<img src=\"" . templateNamed('css')  . "/images/arrow-right.gif\" class=\"arrow\" /></a><ul>\n";
	foreach($childs as $category){
		
		if(!$category->HasChildOrProduct())
			continue;
		?>
			<li><a href="<?= $category->Link; ?>"><?= $category->Name; ?><?php print_childs($category); ?></li>
		<?php
	}
	echo "</ul>\n";
}

function print_families() {
	$strLabel=_xls_get_conf('ENABLE_FAMILIES_MENU_LABEL' , 'By Manufacturer');
	echo '<li><a href="#">'.$strLabel;
	echo '<img src="'.templateNamed('css').'/images/arrow-right.gif" class="arrow"  alt="'.$strLabel.'" /></a>';
	echo '<ul>';
	$families= Family::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Family()->Family)));
	foreach($families as $family) {
		echo '<li><a href="'.$family->RequestUrl.'/f/">'.$family->Family.'</a></li>';
	}
	echo '</ul></li>';	
}

if(_xls_is_idevice()): ?>
	<script language="javascript">
		function showdropdown()	{
			$("#nav_products li ul").style.left = "auto";
			$("#nav_products li ul ul").style.left = "-999em";
		}
	</script>
<?php endif; ?>

	<div class="two columns alpha menuheight">
		<div id="nav_products" class="menutab" onClick="showdropdown();">
			<ul>
				<li class="productstab"><?php _xt('Products'); echo '<img src="'.templateNamed('css').'/images/arrow-down.png" class="arrow"  alt="" />'; ?>
					<ul class="dropspace">
						<?php if(_xls_get_conf('ENABLE_FAMILIES', 0)==2)
							print_families();
						?>
						<?php foreach($this->menu_categories as $category): ?>
							<li><a href="<?= $category->Link; ?>"><?= $category->Name; ?><?php if(!_xls_is_idevice()) print_childs($category); else echo "</a>"; ?></li>
						<?php endforeach; ?>

						<?php if(_xls_get_conf('ENABLE_FAMILIES', 0)==1)
							print_families();
						?>
					</ul>
				</li>
			</ul>
		</div>
	</div>



		<?php
		$ct = count($this->arrTopTabs);
		switch ($ct){
			case 6:case 5: $strW = "two"; break;
			case 4: case 3: $strW = "twohalf"; break;
			default: $strW = "three";
		}
		foreach ($this->arrTopTabs as $arrTab) {
				echo "<div class='".$strW." columns omega menutab menuheight menuunderline' onclick=\"window.location='".$arrTab->Link."'\" >";
			echo '<span class="innertab"><a href="'.$arrTab->Link.'">'.$arrTab->Title.'</a></span>';
			echo '</div>';
		}
		?>


