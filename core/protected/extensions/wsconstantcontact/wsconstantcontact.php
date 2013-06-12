<?php


class wsconstantcontact extends CApplicationComponent {


	public $category = "CEventCustomer";
	public $name = "Constant Contact";

	protected $api;
	protected $objModule;

	//Event map
	//onAddCustomer()
	//onUpdateCustomer()

	public function init()
	{

		$this->objModule = Modules::LoadByName(get_class($this)); //Load our module entry so we can access settings

	}

	/**
	 * Attached event for anytime a new customer is created
	 * @param $event
	 * @return bool
	 */
	public function onAddCustomer($event)
	{

	}


	/**
	 * Attached event for anytime a new customer is created
	 * @param $event
	 * @return bool
	 */
	public function onUpdateCustomer($event)
	{

		//We were passed these by the CEventPhoto class

		return true;


	}



}


?>