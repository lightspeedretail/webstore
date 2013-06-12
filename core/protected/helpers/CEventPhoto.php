<?php
/*
 * Event for Photo
 *
 * Used for actions:
 * onUploadPhoto
 *
 */
class CEventPhoto extends CEvent
{
	/**
	 * @var str onAction
	 */
	public $onAction;
	/**
	 * @var blob Image Data
	 */
	public $blbImage;
	/**
	 * @var int Images Sequence #
	 */
	public $intSequence;
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
	public function __construct($sender,$onAction,$blbImage,$objProduct,$intSequence = 0)
	{
		$this->onAction=$onAction;
		$this->blbImage=$blbImage;
		$this->objProduct=$objProduct;
		$this->intSequence=$intSequence;
		parent::__construct($sender);
	}
}