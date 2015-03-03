<?php
	$assets = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext') . '/wssinglepagecheckout/assets');
	Yii::app()->clientScript->registerScriptFile(
		$assets . '/SinglePageCheckout.js',
		CClientScript::POS_HEAD
	);

	Yii::app()->clientScript->registerScript(
		'instantiate checkout',
		sprintf(
			'$(document).ready(function () {
				singlePageCheckout = new SinglePageCheckout(%s);
			});',
			CJSON::encode(
				array(
					'calculateShippingEndpoint' => Yii::app()->createUrl('cart/ajaxcalculateshipping'),
					'paymentModulesThatUseCard' => array_keys($model->getAimPaymentMethods()),
					'paymentModulesThatUseForms' => array_keys($model->getAlternativePaymentMethodsThatUseSubForms()),
					'shippingProviderId' => CHtml::activeId($model, 'shippingProvider'),
					'shippingPriorityId' => CHtml::activeId($model, 'shippingPriority'),
					'paymentProviderId' => CHtml::activeId($model, 'paymentProvider'),
					'intShippingAddressName' => CHtml::activeName($model, 'intShippingAddress'),
					'shippingAddress1Id' => CHtml::activeId($model, 'shippingAddress1'),
					'shippingAddress2Id' => CHtml::activeId($model, 'shippingAddress2'),
					'shippingCityId' => CHtml::activeId($model, 'shippingCity'),
					'shippingStateId' => CHtml::activeId($model, 'shippingState'),
					'shippingPostalId' => CHtml::activeId($model, 'shippingPostal'),
					'promoCode' => CHtml::activeId($model, 'promoCode'),
					'promoCodeError' => CHtml::activeId($model, 'promoCode_em_'),
					'savedShippingProviders' => $model->getSavedProvidersRadioArr(),
					'savedShippingPriorities' => $model->getSavedPrioritiesRadioArr(),
					'savedTaxes' => $model->getSavedTaxArr(),
					'savedShippingPrices' => $model->getSavedFormattedPricesArr(),
					'savedTotalScenarios' => $model->getSavedScenariosArr(),
					'savedCartScenarios' => $model->getSavedCartScenariosArr(),
					'pickedShippingProvider' => $model->shippingProvider,
					'pickedShippingPriority' => $model->shippingPriority,
				)
			)
		),
		CClientScript::POS_HEAD
	);
