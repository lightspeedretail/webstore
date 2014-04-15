<?php

/**
 * Class CronController
 * Run cron jobs to transfer data from third-party systems
 * Cron job should be set up as: curl http://www.example.com/integration/cron
 */
class CronController extends CController
{

	public function init()
	{
		Controller::initParams();
		//do nothing so we don't create a cart
	}

	/**
	 * See if we have any events to fire
	 */
	public function actionIndex()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('active',1);
		$criteria->compare('category','CEvent',true,'AND');
		$objModules=Modules::model()->findAll($criteria);

		foreach ($objModules as $objModule)
		{


			//Find and our tasks (one of each type this cron cycle)
			$criteria=new CDbCriteria;
			$criteria->select='action';
			$criteria->distinct = true;
			$criteria->compare('controller',$objModule->module);
			$objTaskTypes=TaskQueue::model()->findAll($criteria);

			Yii::import('ext.'.$objModule->module.".".$objModule->module);

			$component = new $objModule->module;
			$component->init(); //Run init on module first

			foreach ($objTaskTypes as $objType)
			{
				//Locate a task of this type
				$objTask = TaskQueue::model()->findByAttributes(array(
					'module'=>'integration',
					'controller'=>$objModule->module,
					'action'=>$objType->action)
				);
				Yii::log("Found TaskQueue item ".$objTask->controller." ".$objTask->action,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);
				$actionName = "OnAction".ucfirst($objTask->action);

				$objEvent = new CEventTaskQueue(get_class($this),$objTask->data_id,$objTask->product_id);
				Yii::log("Cron action ".$actionName." on object ".
					print_r($objEvent,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				//Run the action and get a true/false if it was successful
				$retVal = $component->$actionName($objEvent);

				if($retVal)
				{
					Yii::log("Successfully processed by Amazon, so deleting task", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
					$objTask->delete(); //Successfully ran, so delete entry
				}
				else {
					Yii::log("Still waiting on Amazon, will check again next time",
						'info', 'application.'.__CLASS__.".".__FUNCTION__);
					$objTask->modified = new CDbExpression('NOW()');
					$objTask->save();
				}

			}


		}

		//Create a Download Orders event to force any other subsystems to check for new orders
		if(date("i") % 10 == 0) //every 10 minute increment
		{
			$objEvent = new CEventOrder('CronController','onDownloadOrders');
			_xls_raise_events('CEventOrder',$objEvent);

		}



	}




}