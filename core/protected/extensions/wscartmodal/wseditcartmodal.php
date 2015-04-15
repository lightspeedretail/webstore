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
		$this->assetUrl = $assets = Yii::app()->getAssetManager()->publish(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets'
		);
		
		$cs->registerCssFile('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
		$cs->registerCssFile($assets . '/css/wseditcartmodal.css');
		$this->widget('ext.jquery-history-js.jqueryHistoryJs');

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

		Yii::app()->clientScript->registerScript(
			'instantiate wsEditCartModal',
			sprintf(
				'$(document).ready(function () {
					wsEditCartModal = new WsEditCartModal(%s);
					wsEditCartModal.checkout = checkout;
				});',
				CJSON::encode(
					array(
						'checkoutUrl' => Yii::app()->createUrl('checkout'),
						'updateCartItemEndpoint' => Yii::app()->createUrl('cart/updatecartitem'),
						'csrfToken' => Yii::app()->request->csrfToken,
						'cartId' => CHtml::activeId('EditCart', 'promoCode'),
						'invalidQtyMessage' => Yii::t(
							'checkout',
							'<strong>Only {qty} are available at this time.</strong><br> If you’d like ' .
							'to order more please return at a later time or contact us.'
						)
					)
				)
			),
			CClientScript::POS_HEAD
		);

		$this->render('editcartmodal');
	}

	public static function renderSellPrice($cartItem)
	{
		if ($cartItem->discounted === true)
		{
			$renderedPrice = sprintf(
				'%s%s',
				CHtml::tag(
					'strike',
					array(),
					$cartItem->sellFormatted . ' ' . Yii::t('checkout', 'ea')
				),
				$cartItem->sellDiscountFormatted . ' ' . Yii::t('checkout', 'ea')
			);
		} else {
			$renderedPrice = $cartItem->sellFormatted;
		}

		return $renderedPrice;
	}
}

