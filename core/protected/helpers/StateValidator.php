<?php

/**
 * Custom validator to make sure the state is in the right
 * format for the countries we expect state validation.
 */
class StateValidator extends CValidator {
	const USA = 224;
	const CANADA = 39;
	const AUSTRALIA = 13;

	/**
	 * Validates that the state that the customer enters either
	 * in the shipping form or billing form respects the format
	 * for the countries we want to validate it upon.
	 *
	 * @param CModel $model
	 * @param string $attribute
	 * @return void
	 */
	public function validateAttribute($model, $attribute)
	{
		switch ($attribute)
		{
			case 'shippingState':
				$objCountry = Country::Load($model->shippingCountry);
				break;
			case 'billingState':
				$objCountry = Country::Load($model->billingCountry);
				break;
			default:
				// Cannot validate any other attributes.
				return;
		}

		if ($objCountry === null)
		{
			// Country isn't valid, can't validate the state!
			return;
		}

		$countriesToValidateState = array(
			self::USA,
			self::CANADA,
			self::AUSTRALIA,
		);

		if (in_array($objCountry->id, $countriesToValidateState) === false)
		{
			// Do not attempt to validate the state.
			return;
		}

		if (empty($model->$attribute) === true)
		{
			$this->addError(
				$model,
				$attribute,
				Yii::t(
					'yii',
					'{attributeName} cannot be blank.',
					array('{attributeName}' => $model->getattributeLabel($attribute))
				)
			);
		} else {
			$objState = State::Load($model->$attribute);

			if ($objState === null)
			{
				$this->addError(
					$model,
					$attribute,
					Yii::t(
						'yii',
						'{attributeName} is invalid.',
						array('{attributeName}' => $model->getattributeLabel($attribute))
					)
				);
			}
		}
	}
}