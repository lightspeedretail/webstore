<?php
/*
 * Event for Customers
 *
 * onAddCustomer
 * onEditCustomer
 */
class CEventCustomer extends CEvent
{
	/**
	 * @var str onAction
	 */
	public $onAction;
	/*
	 * @var Customer associated product model
	 */
	public $objCustomer;

	/**
	 * Constructor.
	 * @param mixed $sender sender of the event
	 * @param customer $objCustomer error message
	 */
	public function __construct($sender,$onAction,$objCustomer)
	{
		$this->onAction=$onAction;
		$this->objCustomer=$objCustomer;
		parent::__construct($sender);
	}
}