<?php

//For webstore menuing, we take the built-in Yii Menuing system and slightly modify the output
//to use divs instead of UL lists
//Since we're already inside a widget, let's build from the db here

Yii::import('zii.widgets.CMenu');
class WsMenu extends CMenu {


	public $activateItemsOuter = true;
	public $categories = array();

	public $menuheader = "Products";
	public $showarrow = true;
	public $ipadhack = true;
	public $id = "nav_products";
	public $CssClass="menutab";


	/**
	 * Renders the menu items.
	 * @param array $items menu items. Each menu item will be an array with at least two elements: 'label' and 'active'.
	 * It may have three other optional elements: 'items', 'linkOptions' and 'itemOptions'.
	 */
	public function run()
	{



		$this->renderProductsDropdown();

	}


	protected function renderProductsDropdown()
	{

		if(_xls_is_idevice()) {
        echo '<script>
            function showdropdown()	{
                $("#nav_products li ul").style.left = "auto";
                $("#nav_products li ul ul").style.left = "-999em";
            }
        </script>';
		}

		echo '<div id="'.$this->id.'" class="'.$this->CssClass.'" '.(($this->ipadhack && _xls_is_idevice()) ? 'onClick="showdropdown();"' : '').'>
			<ul>
				<li class="nav_menuheader">'. Yii::t('tabs',$this->menuheader);
				if ($this->showarrow)
					echo CHtml::image(Yii::app()->theme->baseUrl.'/css/images/arrow-down.png','',array('class'=>'arrow'));
				echo '<ul class="dropspace">';

						if(_xls_get_conf('ENABLE_FAMILIES', 0)==2)
							$this->renderFamilies();
						 foreach($this->categories as $category) {
							echo '<li><a href="'.$category['link'].'">'.Yii::t('category',$category['label']);
							 if(!_xls_is_idevice()) self::renderChildren($category); else echo "</a>";
							 echo '</li>';
							 }
						if(_xls_get_conf('ENABLE_FAMILIES', 0)==1)
							$this->renderFamilies();
		echo '
					</ul>
				</li>
			</ul>
		</div>';

	}

	protected function renderChildren($arrCategory) {

		if(!$arrCategory['hasChildren']) {
			echo "</a>";
			return;
		}
		$arrChildren = $arrCategory['children'];
		echo "<img src=\"" .  Yii::app()->theme->baseUrl  . "/css/images/arrow-right.gif\" class=\"arrow\" alt='' /></a><ul>\n";
		foreach($arrChildren as $arrChild) {
			echo '<li><a href="'.$arrChild['link'].'">'.Yii::t('category',$arrChild['label']);
			self::renderChildren($arrChild);
			echo '</li>';
		}

		echo "</ul>\n";
	}

	protected function renderFamilies()
	{


		$strLabel=_xls_get_conf('ENABLE_FAMILIES_MENU_LABEL' , 'By Manufacturer');
		echo '<li class="producttabs"><a href="#">'.$strLabel;
		echo "<img src=\"" .  Yii::app()->theme->baseUrl  . "/css/images/arrow-right.gif\" class=\"arrow\" alt='' /></a>";
		echo '<ul>';
		if (_xls_get_conf('DISPLAY_EMPTY_CATEGORY')==1)
			$families= Family::model()->findAll('child_count>=0 order by family');
		else
			$families= Family::model()->findAll('child_count>0 order by family');
		foreach($families as $family) {
			echo '<li><a href="'.$family->Link.'">'.$family->family.'</a></li>';
		}
		echo '</ul></li>';

	}

}


?>