<?php


class wscloud extends ApplicationComponent {


	public $category = "CEventOrder,CEventPhoto";
	public $name = "Cloud";
	public $version = 1;

	protected $api;
	protected $objModule;
	public $arrCloudinary;

	//Event map
	//onCreateOrder()
	//onUploadPhoto()
	//onFlushTable()


	public function init()
	{
		Yii::import('ext.yii-aws.components.*'); //Required to set our include path so the required_once's everywhere work
		$this->objModule = Modules::LoadByName(get_class($this)); //Load our module entry so we can access settings
		if(file_exists(Yii::getPathOfAlias('webroot.config').'/cloudinary.php'))
		{
			$this->arrCloudinary = require(Yii::getPathOfAlias('webroot.config').'/cloudinary.php');
			Yii::import('application.vendors.cloudinary.src.*');
		}
	}

	/**
	 * Attached event for anytime a new customer is created
	 * @param $event
	 * @return bool
	 */
	public function onAddCustomer($event)
	{
		$this->init();
		$topicArn = $this->getTopicArn();

		//don't run this unless we actually have a cloud acct
		if (_xls_get_conf('LIGHTSPEED_CLOUD') == '0' || empty($topicArn))
		{
			return true;
		}

		$objCustomer = $event->objCustomer;
		$strSignal = $this->buildCustomerSignal($objCustomer);

		$this->sendSignal($strSignal, $topicArn);
	}

	/**
	 * Update a customer
	 * @param $event
	 * @return bool
	 */
	public function onUpdateCustomer($event)
	{
		//The signal building takes care of add or update, so just save code
		$this->onAddCustomer($event);

	}

	/**
	 * Attached event for anytime a new customer is created
	 * @param $event
	 * @return bool
	 */
	public function onCreateOrder($event)
	{
		$this->init();
		$topicArn = $this->getTopicArn();

		//don't run this unless we actually have a cloud acct
		if (Yii::app()->params['LIGHTSPEED_CLOUD'] == '0' || empty($topicArn))
		{
			return true;
		}

		$objCart = Cart::LoadByIdStr($event->order_id);
		$strSignal = $this->buildOrderSignal($objCart);

		$this->sendSignal($strSignal, $topicArn);
		return true;
	}

	public function onFlushTable($event)
	{
		if(!isset($_SERVER['amazon_key'])) return true;

		$this->init();
		$s3 = new S3($_SERVER['amazon_key'], $_SERVER['amazon_secret']);
		$s3->deleteObject('lightspeedwebstore',_xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL').'/product');
	}


	/**
	 * Attached event for anytime a product photo is uploaded
	 * @param $event
	 * @return bool
	 */
	public function onUploadPhoto($event)
	{

		if(!isset($_SERVER['amazon_key']))
		{
			Yii::log("Attempted Cloud transaction but amazon_key not set", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return true;
		}

		$this->init();

		//We were passed these by the CEventPhoto class
		$blbImage = $event->blbImage; //$image resource
		$objProduct = $event->objProduct;
		$intSequence = $event->intSequence;

		//Check to see if we have an Image record already
		$criteria = new CDbCriteria();
		$criteria->AddCondition("`product_id`=:product_id");
		$criteria->AddCondition("`index`=:index");
		$criteria->AddCondition("`parent`=`id`");
		$criteria->params = array (':index'=>$intSequence,':product_id'=>$objProduct->id);
		$objImage = Images::model()->find($criteria);

		if (!($objImage instanceof Images))
			$objImage = new Images();

		//Assign width and height of original
		$objImage->width = imagesx($blbImage);
		$objImage->height = imagesy($blbImage);

		//Assign filename this image, actually write the binary file
		$objImage->strImageName = Images::AssignImageName($objProduct,$intSequence);


		$objImage->product_id=$objProduct->id;
		$objImage->index=$intSequence;


		//Save image record
		Yii::trace("saving ".$objImage->strImageName,'application.'.__CLASS__.".".__FUNCTION__);
		if (!$objImage->save()) {
			Yii::log("Error saving image " .
				print_r($objImage->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		$objImage->parent = $objImage->id; //Assign parent to self
		$objImage->save();

		//Update product record with imageid if this is a primary
		if ($intSequence==0)
		{
			$objProduct->image_id = $objImage->id;
			if (!$objProduct->save()) {
				Yii::log("Error updating product " .
					print_r($objProduct->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		}



		$url=null;

		//Save as temporary file in Pro mode so we can upload
		if(Yii::app()->params['LIGHTSPEED_MT'] == '1' && Yii::app()->params['LIGHTSPEED_CLOUD'] == '0')
		{
			$d = YiiBase::getPathOfAlias('webroot')."/runtime/cloudimages/"._xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL');
			@mkdir($d,0777,true);
			$tmpOriginal = tempnam($d,"img");
			@unlink($tmpOriginal);
			$tmpOriginal .= ".png";
			$retVal = Images::check_transparent($blbImage);
			if($retVal)
			{
				imagealphablending($blbImage, false);
				imagesavealpha($blbImage, true);
			}
			imagepng($blbImage,$tmpOriginal);

			$retVal = $this->saveToCloudinary($objImage->strImageName,$tmpOriginal);
			$event->cloud_image_id = $objImage->id;
			$event->cloudinary_public_id = $retVal['public_id'];
			$event->cloudinary_cloud_name = $this->arrCloudinary['cloud_name'];
			$event->cloudinary_version = $retVal['version'];

			$url = substr($retVal['url'],5);
		}

		$this->updateCloudId($event,$objImage->id);

		if(!empty($url))
		{
			$objImage->image_path = $url;
			$objImage->save();
		}

		@unlink($tmpOriginal);

		return true;


	}

	/**
	 * Attached event for anytime a product photo is deleted
	 * @param $event
	 * @return bool
	 */
	public function onDeletePhoto($event)
	{

		//We've either called this accidentally or with a local path we don't want to process, so bail
		if (empty($event->cloudinary_public_id))
			return true;

		if(Yii::app()->params['LIGHTSPEED_MT']==1 && Yii::app()->params['LIGHTSPEED_CLOUD']==0)
			$this->RemoveImageFromCloudinary($event->cloudinary_public_id);


	}
	public function Resynccloud()
	{
		$this->init();
		$topicArn = $this->getTopicArn();

		//don't run this unless we actually have a cloud acct
		if (Yii::app()->params['LIGHTSPEED_CLOUD'] == '0' || empty($topicArn))
		{
			return true;
		}

		$strSignal = $this->buildResyncSignal();

		$this->sendSignal($strSignal, $topicArn);
	}

	protected function buildOrderSignal($objCart)
	{

		$response = array();
		$response['message_type']='ws_event';
		$response['accountID'] = Yii::app()->params['LIGHTSPEED_CLOUD'];
		$response['object']='Order';
		$response['objectID']=$objCart->id_str;
		$response['action']='Create';
		$response['storeHost']=Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];
		$response['url']="https://".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];

		return json_encode($response);

	}
	protected function buildResyncSignal()
	{

		$response = array();
		$response['message_type']='re_sync';
		$response['accountID']=_xls_get_conf('LIGHTSPEED_CLOUD');
		$response['action']='all';

		return json_encode($response);

	}

	protected function buildCustomerSignal($objCustomer)
	{

		$response = array();
		$response['message_type']='ws_event';
		$response['accountID']=_xls_get_conf('LIGHTSPEED_CLOUD');
		$response['object']='Customer';
		$response['objectID']=$objCustomer->id;
		if (!is_null($objCustomer->lightspeed_id))
			$response['action']='Update';
			else $response['action']='Create';
		$response['url']=Yii::app()->createAbsoluteUrl('/');

		return json_encode($response);

	}

	public function updateCloudId($event,$imageId)
	{
		//Find or create a Cloud ID record if we have it
		if(isset($event->cloud_image_id))
		{
			$objImageCloud = ImagesCloud::model()->findByAttributes(array('image_id'=>$imageId));
			if(!($objImageCloud instanceof ImagesCloud))
			{
				$objImageCloud = new ImagesCloud();
				$objImageCloud->image_id = $imageId;
			}

			$objImageCloud->cloud_image_id = $event->cloud_image_id;
			$objImageCloud->cloudinary_public_id = $event->cloudinary_public_id;
			$objImageCloud->cloudinary_cloud_name = $event->cloudinary_cloud_name;
			$objImageCloud->cloudinary_version = $event->cloudinary_version;

			if (!$objImageCloud->save()) {
				Yii::log("Error updating ImageCloud " .
					print_r($objImageCloud->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

		}
	}


	public function SaveToCloudinary($keyPath,$pathToFile)
	{
		$this->init();
		require_once("Uploader.php");

		if(is_null($this->arrCloudinary)) return false;

		Yii::log('Uploading /'.$keyPath." ".print_r($this->arrCloudinary,true),
			'info', 'application.'.__CLASS__.".".__FUNCTION__);


		\Cloudinary::config(array(
			"cloud_name" => $this->arrCloudinary['cloud_name'],
			"api_key" => $this->arrCloudinary['api_key'],
			"api_secret" => $this->arrCloudinary['api_secret']
		));


		$arrReturn = \Cloudinary\Uploader::upload($pathToFile,array("invalidate" => TRUE));

		Yii::log('Returned from Cloudinary '.print_r($arrReturn,true),
			'info', 'application.'.__CLASS__.".".__FUNCTION__);

		return $arrReturn;

	}

	public function RemoveImageFromCloudinary($public_id)
	{
		$this->init();
		require_once("Uploader.php");

		if(is_null($this->arrCloudinary)) return false;

		Yii::log('Deleting /'.$public_id." ".print_r($this->arrCloudinary,true),
			'info', 'application.'.__CLASS__.".".__FUNCTION__);


		\Cloudinary::config(array(
			"cloud_name" => $this->arrCloudinary['cloud_name'],
			"api_key" => $this->arrCloudinary['api_key'],
			"api_secret" => $this->arrCloudinary['api_secret']
		));


		$arrReturn = \Cloudinary\Uploader::destroy($public_id,array("invalidate" => TRUE));

		Yii::log('Returned from Cloudinary '.print_r($arrReturn,true),
			'info', 'application.'.__CLASS__.".".__FUNCTION__);

		return $arrReturn;

	}

	public function SaveToS3($keyPath,$pathToFile)
	{
		$this->init();

		if(!isset($_SERVER['amazon_key']) || !isset($_SERVER['amazon_secret'])) return false;

		Yii::log("Uploading /"._xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL').'/'.$keyPath,
			'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$mimeType="text/html";
		if(substr($keyPath,-4)==".css")
			$mimeType="text/css";
		if(substr($keyPath,-4)==".jpg")
			$mimeType="image/jpeg";
		if(substr($keyPath,-5)==".jpeg")
			$mimeType="image/jpeg";
		if(substr($keyPath,-4)==".png")
			$mimeType="image/png";
		$s3 = new S3($_SERVER['amazon_key'], $_SERVER['amazon_secret']);
		$result = $s3->putObjectFile($pathToFile,
			"lightspeedwebstore",
			_xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL').'/'.$keyPath,
			S3::ACL_PUBLIC_READ,
			array(),
			$mimeType
		);

		if($result)
			return '//lightspeedwebstore.s3.amazonaws.com/'._xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL').'/'.$keyPath;
		else
		{
			Yii::log("Error saving to cloud "._xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL').'/'.$keyPath,
				'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

	}

	public function RemoveImageFromS3($objImage,$image_path = null)
	{
		$this->init();
		$s3 = new S3($_SERVER['amazon_key'], $_SERVER['amazon_secret']);

		if(is_null($image_path))
		{
			$criteria = new CDbCriteria();
			$criteria->AddCondition("`product_id`=:product_id");
			$criteria->AddCondition("`index`=:index");
			$criteria->params = array (':index'=>$objImage->index,':product_id'=>$objImage->product_id);
			$objImages = Images::model()->findAll($criteria);

			foreach($objImages as $image)
			{
				Yii::log("Attempting to delete  ".$image->image_path,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);

				$key = str_replace("//lightspeedwebstore.s3.amazonaws.com/","",$image->image_path);
				if (!empty($image->image_path))
					$s3->deleteObject('lightspeedwebstore',$key);
			}
		} else {
			$image_path = str_replace("http:","",$image_path);
			$image_path = str_replace("//lightspeedwebstore.s3.amazonaws.com/","",$image_path);

			$s3->deleteObject('lightspeedwebstore',$image_path);
		}


	}

	protected function sendSignal($strSignal, $topicArn)
	{

		Yii::log(
			sprintf(
				'Attempting SNS Cloud signal to %s: %s',
				$topicArn,
				$strSignal
			),
			'info',
			'application.'.__CLASS__.".".__FUNCTION__
		);

		try
		{
			$msgId = self::sendAwsSnsSignal($strSignal,$topicArn);
			Yii::log("Returned message ID ".$msgId['MessageId'], 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		}
		catch (Exception $objEx)
		{
			Yii::log("Failed to send SNS signal: ".$objEx, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Retrying SNS Cloud signal ".$strSignal, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

			try
			{
				$msgId = self::sendAwsSnsSignal($strSignal,$topicArn);
				Yii::log("Returned message ID ".$msgId['MessageId'], 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			} catch (Exception $objEx) {
				Yii::log("Failed to send SNS signal: ".$objEx, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}

	}

	private static function sendAwsSnsSignal($strSignal, $topicArn)
	{
		$sns = new A2Sns();

		$msgId = $sns->publish(array(
			'TopicArn' => $topicArn,
			'Message' => $strSignal,
		));

		return $msgId;
	}

	/**
	 * Generate cloudinary image url for a cloud image
	 * @param ImagesCloud [$objCloudImage] A cloud image object
	 * @param integer [$intWidth] Image width
	 * @param integer [$intHeight] Image height
	 * @param bool [$limitSize] True to get image by its original dimension if
	 * it is smaller than the requested dimension
	 * @return string Cloudinary image url
	 */
	public function getCloudImage($objCloudImage, $intWidth, $intHeight,
		$limitSize = false)
	{
		$this->init();

		\Cloudinary::config(array(
			"cloud_name" => $objCloudImage->cloudinary_cloud_name,
			"api_key" => "",
			"api_secret" => ""
		));
		$arrOptions = array();
		if ($limitSize)
		{
			/* The limit mode is used for creating an image that does not exceed
			the given width or height. If the original image is smaller than
			the given limits, the generated image is identical to the original
			one. If the original is bigger than the given limits, it will be
			resized while retaining original proportions
			*/
			$crop = "limit";
		}
		else
		{
			/* Resize the image to fill the given width & height while retaining
			original proportions.
			*/
			$crop = "pad";
		}

		if($intWidth > 0 && $intHeight > 0)
		{
			$arrOptions = array("width" => $intWidth,
								"height" => $intHeight,
								"crop" => $crop);
		}

		if(!empty(Yii::app()->params['IMAGE_BACKGROUND']))
		{
			$arrOptions['background'] = "rgb:".str_replace("#", "", Yii::app()->params['IMAGE_BACKGROUND']);
		}

		if(!empty(Yii::app()->params['IMAGE_SHARPEN']))
		{
			$arrOptions['sharpen'] = Yii::app()->params['IMAGE_SHARPEN'];
		}

		if(!empty(Yii::app()->params['IMAGE_QUALITY']))
		{
			$arrOptions['quality'] = Yii::app()->params['IMAGE_QUALITY'];
		}

		$url = cloudinary_url(
			$objCloudImage->cloudinary_public_id.
			".".
			Yii::app()->params['IMAGE_FORMAT'],
			$arrOptions
		);

		//we remove schema so either http or httpd will work
		$url = str_replace("http:", "", str_replace("https:", "", $url));
		return $url;

	}

	/**
	 * Get the configured SNS Topic ARN.
	 *
	 * Use the environment variable if set, otherwise fall back to the database
	 * configuration.
	 *
	 * TODO WS-4151 - Remove support for legacy configuration.
	 *
	 * @return {string|null} The configured topic ARN.
	 */
	protected function getTopicArn() {
		if (isset($_SERVER['amazon_sns_topic_arn']))
		{
			return $_SERVER['amazon_sns_topic_arn'];
		}

		return $this->objModule->getConfig('topic_arn');
	}
}
