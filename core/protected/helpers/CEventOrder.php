<?php
/*
 * Event for Orders
 *
 * onDownloadOrders
 * onCheckout
 */
class CEventOrder extends CEvent
{
	/**
	 * @var str onAction
	 */
	public $onAction;
	/**
	 * @var str order_id
	 */
	public $order_id;

	/**
	 * @param mixed|null $sender
	 * @param mixed|null $onAction
	 * @param null $order_id
	 */
	public function __construct($sender,$onAction,$order_id=null)
	{
		$this->onAction=$onAction;
		$this->order_id=$order_id;
		parent::__construct($sender);
	}

}