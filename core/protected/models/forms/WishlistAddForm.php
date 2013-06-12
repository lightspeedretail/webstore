<?php

/**
 * WishlistAdd class.
 * mini form
 */
class WishlistAddForm extends CFormModel
{
	public $id;
	public $qty;
	public $size;
	public $color;

	public $gift_code;
	public $lists;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('id,qty,', 'required'),
			array('size,color,gift_code','safe')
			// verifyCode needs to be entered correctly
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
			'gift_code'=> Yii::t('wishlist','Add to what list'),

		);
	}

	public function getLists()
	{
		$retValue = Wishlist::LoadUserLists();
		if(!is_null($retValue))
			return CHtml::listData($retValue, 'gift_code', 'registry_name');
		else return null;
	}


}