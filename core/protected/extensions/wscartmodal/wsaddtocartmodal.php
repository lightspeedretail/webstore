<?php

Yii::import('ext.umber.wsmodal');

class wsaddtocartmodal extends wsmodal
{
	private $intCountRelated = 3;

	// right side
	protected $objCart;
	public $intItemCount;
	public $strItems;

	// left side
	public $objCartItem;
	public $intImageID = null;

	// bottom
	public $arrObjRelated;
	public $arrObjAutoAdd;  // this isn't actually used anywhere in the view yet

	public function run()
	{
		parent::run();
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', false, -1, true);
		$cs = Yii::app()->clientScript;
		$cs->registerCssFile($assets . '/css/wsaddtocartmodal.css');

		Yii::app()->clientScript->registerScript(
			'instantiate checkout',
			sprintf(
				'$(document).ready(function () {
					checkout = new Checkout(%s);
				});',
				Checkout::getCheckoutJSOptions()
			),
			CClientScript::POS_HEAD
		);

		// No item added to cart.
		if(empty($this->objCartItem))
		{
			return;
		}

		$this->objCart = Yii::app()->shoppingcart;
		$this->intItemCount = Yii::app()->shoppingcart->totalItemCount;
		$this->strItems = $this->intItemCount > 1 ? Yii::t('cart', 'items') : Yii::t('cart', 'item');

		$arrItems = Yii::app()->shoppingcart->cartItems;

		if (count($arrItems))
		{
			$this->intImageID = $this->objCartItem->Prod->image_id;

			$dataProvider = $this->objCartItem->Prod->related();
			$arrRel = $dataProvider->Data;

			$dataProvider = $this->objCartItem->Prod->autoadd();
			$arrAuto = $dataProvider->Data;

			$arr = array_merge($arrAuto, $arrRel);

			// right now we only want 3 related items at most
			while (count($arr) > $this->intCountRelated)
			{
				array_pop($arr);
			}

			$this->arrObjRelated = $arr;

		} else {
			$this->objCartItem = new CartItem(); // empty object
		}

		$this->render('addtocartmodal');
	}
}
