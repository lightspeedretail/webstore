<?php
/*
 * Event for Photo
 *
 * Used for actions:
 * onUploadPhoto
 *
 */
/**
 * Class CEventPhoto
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
	 * @var cloud_image_id associated Image model (we use just to have a holder for certain values)
	 */
	public $cloud_image_id;
	public $cloudinary_public_id;
	public $cloudinary_cloud_name;
	public $cloudinary_version;

	public $s3_path;

	/**
	 * Constructor
	 * @param mixed|null $sender sender of the event
	 * @param mixed|null $onAction
	 * @param $blbImage
	 * @param $objProduct
	 * @param int $intSequence
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