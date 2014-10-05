<?php
/**
 * Custom validator to ensure a password meets
 * the length requirement configured by administrator
 */

class PasswordLengthValidator extends CValidator {

	/**
	 * Ensure that a password meets our requirements
	 * Return an error message detailing the failure if applicable.
	 *
	 * @param CModel $model
	 * @param string $attribute
	 * @return string | false
	 */
	public function validateAttribute($model, $attribute) {
		$minLength = _xls_get_conf('MIN_PASSWORD_LEN',0);

		if (strlen($model->$attribute) < $minLength) {
			$this->addError($model, $attribute, Yii::t('customer',
				'{attribute} too short. Must be a minimum of {length} characters.',
				array(
					'{attribute}'=>$model->getAttributeLabel($attribute),
					'{length}'=>$minLength
				)
			));
		}
	}
} 