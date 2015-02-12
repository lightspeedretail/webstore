<?php

/**
 * This is the model class for table "{{cart_messages}}".
 *
 * @package application.models
 * @name CartMessages
 *
 */
class CartMessages extends BaseCartMessages
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CartMessages the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function __toString() {
		return sprintf('CartMessages Object %s', $this->id);
	}
}
