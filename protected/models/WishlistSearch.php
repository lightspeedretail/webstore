<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class WishlistSearch extends CFormModel
{
	public $email;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('email', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			array('email', 'validAccount'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'Email Address',
		);
	}


	public function validAccount($attribute,$param)
	{
		$objCustomer = Customer::LoadByEmail($this->email);
		if (!($objCustomer instanceof Customer)) {
			$this->addError($attribute,
				Yii::t('global','Email address not found')
			);
			return;
		}

		$intQty = Yii::app()->db->createCommand(
			"SELECT COUNT(*) from ".Wishlist::model()->tableName()."
					WHERE
					customer_id=". $objCustomer->id." AND
					visibility=".Wishlist::PUBLICLIST.";")->queryScalar();
		if ($intQty==0) {
			$this->addError($attribute,
				Yii::t('wishlist','No publicly searchable wish lists for this email address.')
			);
			return;
		}

	}

}