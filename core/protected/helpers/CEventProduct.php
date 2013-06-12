<?php
/*
 * Event for Product
 *
 * Used for actions:
 * onSaveProduct
 * onUpdateInventory
 *
 */
class CEventProduct extends CEvent
{
	/**
	 * @var str onAction
	 */
	public $onAction;
	/**
	 * @var Product associated product model
	 */
	public $objProduct;

	/**
	 * Constructor.
	 * @param mixed $sender sender of the event
	 * @param mixed $blbImage error code
	 * @param Product $objProduct error message
	 */
	public function __construct($sender,$onAction,$objProduct)
	{
		$this->onAction=$onAction;
		$this->objProduct=$objProduct;
		parent::__construct($sender);
	}
}