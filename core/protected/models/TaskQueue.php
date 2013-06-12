<?php

/**
 * This is the model class for table "{{task_queue}}".
 *
 * @package application.models
 * @name TaskQueue
 *
 */
class TaskQueue extends BaseTaskQueue
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TaskQueue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function CreateEvent($strModule, $strController, $strAction, $data_id = null, $product_id = null)
	{

		if ($strController=="amazon")
		{
			$MerchantID = _xls_get_conf('AMAZON_MERCHANT_ID');
			$MarketplaceID = _xls_get_conf('AMAZON_MARKETPLACE_ID');
			$MWS_ACCESS_KEY_ID = _xls_get_conf('AMAZON_MWS_ACCESS_KEY_ID');
			$MWS_SECRET_ACCESS_KEY = _xls_get_conf('AMAZON_MWS_SECRET_ACCESS_KEY');

			if (empty($MerchantID) ||
			empty($MarketplaceID) ||
			empty($MWS_ACCESS_KEY_ID) ||
			empty($MWS_SECRET_ACCESS_KEY))
				return false;
		}


		//Check to make sure it's not duplicate
		$objTask = TaskQueue::model()->findByAttributes(array('module'=>$strModule,'controller'=>$strController,'action'=>$strAction,'data_id'=>$data_id,'product_id'=>$product_id));
		if ($objTask instanceof TaskQueue) return;

		$objTask = new TaskQueue();
		$objTask->module = $strModule;
		$objTask->controller = $strController;
		$objTask->action = $strAction;
		$objTask->data_id = $data_id;
		$objTask->product_id = $product_id;
		if (!$objTask->save())
			Yii::log("Error creating Task $strModule, $strController, $strAction " . print_r($objTask->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

	}

	/**
	 * Fix any required fields before validation
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');

		return parent::beforeValidate();
	}

}