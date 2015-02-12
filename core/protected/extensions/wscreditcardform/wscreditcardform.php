<?php

class wscreditcardform extends CWidget
{
	public $assetUrl;
	public $model;
	public $form;

	public function init()
	{
		$this->assetUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR, false, -1, true);
	}

	public function run()
	{
		Yii::app()->clientScript->registerScriptFile($this->assetUrl . '/assets/jquery.payment.js');
		Yii::app()->clientScript->registerScriptFile($this->assetUrl . '/assets/wscreditcardform.js');

		$arrEnabledCreditCardLabel = array_map(
			function ($creditCard) {
				return $creditCard->label;
			},
			CreditCard::model()->enabled()->findAll()
		);

		$this->render(
			'creditcardform',
			array(
				'model' => $this->model,
				'form' => $this->form,
				'arrEnabledCreditCardLabel' => $arrEnabledCreditCardLabel,
				'strCardTypeNotSupported' => Yii::t(
					'checkout',
					MultiCheckoutForm::DISABLED_CARD_TYPE
				)
			)
		);
	}

}
