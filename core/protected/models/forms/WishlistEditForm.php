<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class WishlistEditForm extends CFormModel
{
	public $qty;
	public $qty_received;
	public $priority;
	public $comment;

	public $code;
	public $id;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('qty,priority', 'numerical'),
			array('qty_received','numerical','allowEmpty' => 'true'),
			array('comment,id,code', 'safe'),
			array('comment', 'length', 'min'=>0, 'max'=>500),
//			// rememberMe needs to be a boolean
//			array('rememberMe', 'boolean'),
//			// password needs to be authenticated
//			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'qty'=>Yii::t('wishlist','Qty Desired'),
			'qty_received'=>Yii::t('wishlist','Qty Received'),
			'priority'=>Yii::t('wishlist','Priority'),
			'comment'=>Yii::t('wishlist','Item Comment (max 500 characters)'),

		);
	}

	/**
	 * @return array
	 */
	public function getPriorities()
	{

		return array(
			'0'=> Yii::t('wishlist','Low Priority'),
			'1'=> Yii::t('wishlist','Normal Priority'),
			'2'=> Yii::t('wishlist','High Priority'),
		);

	}


}
