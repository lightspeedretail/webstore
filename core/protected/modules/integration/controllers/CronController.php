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
				$actionName = "OnAction".ucfirst($objTask->action);

				$objEvent = new CEventTaskQueue(get_class($this),$objTask->data_id,$objTask->product_id);

				//Run the action and get a true/false if it was successful
				$retVal = $component->$actionName($objEvent);

				if($retVal)
					$objTask->delete(); //Successfully ran, so delete entry
				else {
					$objTask->modified = new CDbExpression('NOW()');
					$objTask->save();
				}

			}


		}


	}




}