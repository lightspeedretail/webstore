<?php

Yii::import('ext.umber.wsmodal');

class wseditcartmodal extends wsmodal
{
	public $arrProducts;
	public $assetUrl;

	public function run()
	{
		parent::run();

		$cs = Yii::app()->clientScript;
		$this->assetUrl = $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets',false,-1,true);

		$cs->registerCssFile('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
		$cs->registerCssFile($assets . '/css/wseditcartmodal.css');
		$cs->registerScriptFile($assets . '/js/wseditcartmodal.js');
		$this->widget('ext.jquery-history-js.jqueryHistoryJs');

		Yii::app()->clientScript->registerScript(
			'editable',
			"
			function removeItem(input) {
				var pk = input.getAttribute('data-pk');
				input.id = 'CartItem_qty_' + pk;
				input.value = 0;
				updateCart(input);
			};
		   function updateCart(input) {
					var pk = input.id;
					var obj = {'YII_CSRF_TOKEN': '".Yii::app()->request->csrfToken."'};
					obj[pk] = input.value;

					$.ajax({url: '".Yii::app()->controller->createUrl('cart/updatecart')."',
							type: 'POST',
							dataType: 'json',
							success: function(data) {
								if(data.action=='success') {
									wseditcartmodal.redrawCart(JSON.parse(data.cartitems));

									// Update the shipping estimate.
									if (typeof wsShippingEstimator !== 'undefined') {
										wsShippingEstimator.calculateShippingEstimates();
									}
								}
								else if(data.errorId === 'invalidQuantity'){
									var qty = $('#' + pk);
									qty.val(data.availQty);
									wseditcartmodal.tooltip.createTooltip(pk, '<strong>Only ' + data.availQty + ' are available at this time.</strong><br> If you’d like to order more please return at a later time or contact us.');
								}
								else {
									alert(data.errormsg);
								}
							},
							error: function(data) { alert('error'); },
							data: obj
					});

			}",
			CClientScript::POS_HEAD
		);

		$this->render('editcartmodal');
	}

	public static function renderSellPrice($cartItem)
	{
		$renderedPrice = '';
		if ($cartItem->discounted === true)
		{
			$renderedPrice = CHtml::tag(
					'strike',
					array(),
					$cartItem->sellFormatted
				).
				$cartItem->sellDiscountFormatted;
		}
		else
			$renderedPrice = $cartItem->sellFormatted;

		return $renderedPrice;
	}
}

