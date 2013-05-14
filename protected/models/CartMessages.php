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
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function CreateMessage($intCartId,$strMessage) {
		$msg = new CartMessages;
		$msg->cart_id=$intCartId;
		$msg->message=$strMessage;
		try {
			$msg->save();

			return true;

		} catch(Exception $e) {
			Yii::log("Error on Cart ".$intCartId." for msg ".$strMessage, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}
	}

	public function __toString() {
		return sprintf('CartMessages Object %s',  $this->id);
	}

	public static function DeleteByCartId($intCartId) {
		_dbx("delete from xlsws_cart_messages where cart_id=".$intCartId);

	}
}