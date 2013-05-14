<?php

/*
 * This event is for our task queue, to be able to pass along parameters easily
 */
class CEventTaskQueue extends CEvent
{
	/**
	 * @var str third party data id if applicable
	 */
	public $data_id;
	/*
	 * @var product id if applicable
	 */
	public $product_id;

	/**
	 * Constructor.
	 * @param mixed $sender sender of the event
	 * @param customer $objCustomer error message
	 */
	public function __construct($sender,$data_id = null,$product_id = null)
	{
		$this->data_id=$data_id;
		$this->product_id=$product_id;
		parent::__construct($sender);
	}
}