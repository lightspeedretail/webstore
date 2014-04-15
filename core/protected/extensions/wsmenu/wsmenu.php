<?php

//For webstore menuing, we take the built-in Yii Menuing system and slightly modify the output
//to use divs instead of UL lists
//Since we're already inside a widget, let's build from the db here

Yii::import('zii.widgets.CMenu');
class WsMenu extends CMenu {


	/**
	 * @var bool
	 */
	public $activateItemsOuter = true;
	/**
	 * @var array
	 */
	public $categories = array();

	/**
	 * @var string
	 */
	public $menuheader = "Products";
	/**
	 * Whether to show arrow graphic
	 * @var bool
	 */
	public $showarrow = true;
	/**
	 * Enable click to show menu when using iDevices
	 * @var bool
	 */
	public $ipadhack = true;
	/**
	 * DIV id
	 * @var string
	 */
	public $id = "nav_products";
	/**
	 * DIV id
	 * @var string
	 */
	public $cmenuid = "menutree";
	/**
	 * Since Products occupies one of the tabs, the CSS class that we need to use
	 * @var string
	 */
	public $CssClass="menutab";
	/**
	 * CSS Class to use for Menu Header
	 * @var string
	 */
	public $menuheaderCssClass="nav_menuheader";


	/**
	 * Renders the dropdown menu
	 * Use CMenu within a dropdown wrapper with an optional arrow
	 */
	public function run()
	{
		if($this->activateItemsOuter)
			$this->renderMenuInWrapper();
		else
			$this->renderCMenu();



	}

	public function renderMenuInWrapper()
	{
		if(_xls_is_idevice() && $this->ipadhack) {
			echo '<script>
			function showdropdown()	{
				$("#'.$this->id.' li ul").style.left = "auto";
				$("#'.$this->id.' li ul ul").style.left = "-999em";
			}
		</script>';
		}

		echo '<div id="'.$this->id.'" class="'.$this->CssClass.'" '.
			(($this->ipadhack && _xls_is_idevice()) ? 'onClick="showdropdown();"' : '').'>';

		echo '<ul>
					<li class="'.$this->menuheaderCssClass.'">'. Yii::t('tabs',$this->menuheader);

		//If we show arrow graphic
		if ($this->showarrow)
			echo CHtml::image(Yii::app()->theme->baseUrl.'/css/images/arrow-down.png','',array('class'=>'arrow'));

		$this->renderCMenu();

		echo '</li>
			</ul>
		</div>';
	}


	public function renderCMenu()
	{
		//Just use Yii functionality, way simpler than our own
		$this->widget( 'zii.widgets.CMenu', array(
			'items' => $this->categories,
			'id'=>$this->cmenuid
		));
	}

}
