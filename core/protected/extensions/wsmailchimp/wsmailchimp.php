<?php


class wsmailchimp extends ApplicationComponent {


	public $category = "CEventCustomer";
	public $name = "MailChimp";
	public $version = 1;

	protected $api;
	protected $objModule;

	//Event map
	//onAddCustomer()
	//onUpdateCustomer()


	public function init()
	{
		Yii::import('application.vendors.Mailchimp.*'); //Required to set our include path so the required_once's everywhere work
		require_once('MCAPI.class.php');


		$this->objModule = Modules::LoadByName(get_class($this)); //Load our module entry so we can access settings
		$this->api = new MCAPI($this->objModule->getConfig('api_key'));

	}

	/**
	 * Attached event for anytime a new customer is created
	 * @param $event
	 * @return bool
	 */
	public function onAddCustomer($event)
	{
		$this->init();

		$objCustomer = $event->objCustomer;

		if($objCustomer->newsletter_subscribe)
		{
			$intListId = $this->getListId($this->objModule->getConfig('list'));

			if (!is_null($intListId))
			{
				$merge_vars = array(
					'FNAME'=>$objCustomer->first_name,
					'LNAME'=>$objCustomer->last_name,
					'OPTIN_IP'=>_xls_get_ip(),
					'OPTIN_TIME'=>date("Y-m-d H:i:s"),
					'GROUPINGS'=>array(),
					'double_optin'=>false, //since we already have the opt-in checkbox on our form
					'update_existing'=>true,
					'send_welcome'=>true,

				);

				$this->api->listSubscribe($intListId, $objCustomer->email,$merge_vars);

				if ($this->api->errorCode)
				{
					Yii::log("Unable to load listSubscribe() Code=".$this->api->errorCode." Msg=".$this->api->errorMessage, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					return false;
				} else
					return true;

			} else {
				Yii::log("Mailchimp - can't find list ".$this->objModule->getConfig('list'), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		} else return true; //if the person doesn't want to subscribe, return true because it's not an error result, just do nothing

	}


	/**
	 * Update a customer, which could involve removing them from the list
	 * @param $event
	 * @return bool
	 */
	public function onUpdateCustomer($event)
	{

		$this->init();

		$objCustomer = $event->objCustomer;


		$intListId = $this->getListId($this->objModule->getConfig('list'));

		if (!is_null($intListId))
		{
			$merge_vars = array(
				'FNAME'=>$objCustomer->first_name,
				'LNAME'=>$objCustomer->last_name
			);
			if($objCustomer->newsletter_subscribe)
			{

				//Verify this person is really on the mailing list
				$arrReturn = $this->api->listMemberInfo($intListId, $objCustomer->email);

				Yii::log("listMemberInfo returned ".print_r($arrReturn,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				if ($arrReturn['success'] &&
					isset($arrReturn['data'][0]['status']) &&
					$arrReturn['data'][0]['status'] != "unsubscribed" &&
					$arrReturn['data'][0]['status'] != "pending"
				)
					$retval = $this->api->listUpdateMember($intListId, $objCustomer->email,$merge_vars);
				else
					return $this->onAddCustomer($event); //just send this to the Add routine

				Yii::log("listUpdateMember returned ".print_r($retval,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			}
			else
			{
				$this->api->listUnsubscribe($intListId, $objCustomer->email); //goodbye
				Yii::log("listUnsubscribe sent for  ".$objCustomer->email, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			}

			if ($this->api->errorCode)
			{
				Yii::log("Unable to load listUpdateMember() Code=".$this->api->errorCode." Msg=".$this->api->errorMessage, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			} else
				return true;

		} else {
			Yii::log("Mailchimp - can't find list ".$this->objModule->getConfig('list'), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}



		return true;


	}


	/**
	 * Look up ListID on Mailchimp by name
	 * @param $strList
	 * @return null
	 */
	protected function getListId($strList)
	{

		$arrLists = $this->api->lists();
		foreach($arrLists['data'] as $list)
		{
			if ($list['name']==$strList)
				return $list['id'];
		}
		return null;

	}


}


?>