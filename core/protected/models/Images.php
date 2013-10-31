<?php

/**
 * This is the model class for table "{{images}}".
 *
 * @package application.models
 * @name Images
 *
 */
class Images extends BaseImages
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Images the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	// String representation of object
	public function __toString() {
		return sprintf('Images Object %s at %sx%s',
			$this->id, $this->intWidth, $this->intHeight);
	}

	/**
	 * Define constants and lists
	 */

	const NORMAL = "image";
	const SMALL = "smallimage";
	const PDETAIL = "pdetailimage";
	const MINI = "miniimage";
	const LISTING = "listingimage";
	const PREVIEW = "previewimage";
	const SLIDER = "sliderimage";
	const CATEGORY = "categoryimage";

	public static $Sizes;
	public static $SizeConfigKeys;


	/* Returns the URL to the requested photo size, checking to see
	if the size exists. If not, calling this function will trigger
	creation dynamically and return the resulting URL. If the original graphic
	doesn't exist, it will point to a "missing graphic" URL.
	*/
	public static function GetLink($id, $intType = ImagesType::normal, $AbsoluteUrl = false) {

		//See if image exists for chosen size
		$objImage = Images::LoadByRowidSize($id, $intType);
		//If exists, return URL based on stored path
		if ($objImage && $objImage->ImageFileExists())
			return Images::GetImageUri($objImage->image_path,$AbsoluteUrl);


		list($intWidth, $intHeight) = ImagesType::GetSize($intType);

		//Does original size image exist?
		$objParentImage = Images::LoadByParent($id);
		if ($objParentImage && $objParentImage->ImageFileExists()) {

			$objProduct = Product::model()->findByPk($objParentImage->product_id);
			$blbImage = imagecreatefrompng(Images::GetImagePath($objParentImage->image_path));
			$objEvent = new CEventPhoto('Images','onUploadPhoto',$blbImage,$objProduct,$objParentImage->index);
			_xls_raise_events('CEventPhoto',$objEvent);

			$objImage = Images::LoadByRowidSize($id, $intType);
			if ($objImage && $objImage->ImageFileExists())
				return Images::GetImageUri($objImage->image_path,$AbsoluteUrl);
		}

		//If we haven't returned by this point, we don't have any image, so show default missing
		if ($intWidth==0) $intWidth = 100;
		if ($intHeight==0) $intHeight = 100;
		$objParentImage = Images::ShowFallback($intWidth, $intHeight);
		$thumb = $objParentImage->CreateThumb($intWidth, $intHeight);
		if ($thumb) return Images::GetImageUri($thumb->image_path,$AbsoluteUrl);
		else return self::GetImageFallbackPath($AbsoluteUrl);



	}

	/**
	 * Return the size of an Image Type
	 * @param string $strType :: Image constant defined in TypeDefaultSizes
	 * @return array (width,height)
	 */
	public static function GetSize($strType) {
		$intType = ImagesType::ToToken($strType);
		return ImagesType::GetSize($intType);
	}


	public static function GetOriginal($objProduct)
	{
		//Check to see if we have an Image record already
		$criteria = new CDbCriteria();
		$criteria->AddCondition("`product_id`=:product_id");
		$criteria->AddCondition("`index`=:index");
		$criteria->AddCondition("`parent`=`id`");
		$criteria->params = array (':index'=>0,':product_id'=>$objProduct->id);
		return Images::model()->find($criteria);

	}

	public static function AssignImageName($objProduct,$intSequence)
	{


		$strImageName = Images::GetImageName(mb_substr($objProduct->request_url,0,60,'utf8'),0, 0, $intSequence);
		if (Images::ExistsForOtherProduct($strImageName,$objProduct->id))
			$strImageName = Images::GetImageName(mb_substr($objProduct->request_url,0,60,'utf8')."-r".$objProduct->id,0, 0, $intSequence);

		return $strImageName;
	}


	// $strName == id
	//ToDo: Remove isthumb and have this function auto-add the _add instead of in the soap transaction
	public static function GetImageName($strName,
	                                    $intWidth = 0, $intHeight = 0, $intIndex = 0, $strClass = null,
	                                    $blnIsThumb = false, $strSection = 'product') {

		$strName = mb_pathinfo($strName, PATHINFO_FILENAME);

//		if (!empty($strClass))
//			$strName .= '-' . $strClass;

		if (!empty($intIndex))
			$strName .= '-add-' . $intIndex;

		if (!empty($intWidth) && !empty($intHeight))
			$strName .= '-' . $intWidth . 'px-' . $intHeight . "px";

		$fileExt =  strtolower(_xls_get_conf('IMAGE_FORMAT','jpg'));
		if ($intWidth==0 && $intHeight==0) $fileExt="png"; //The file from LS is always png

		return $strSection . "/" . mb_substr($strName,0,1,'utf8') . "/" . $strName . '.' . $fileExt;
	}

	/**
	 * Checks to see if the filename we want to use is being used for another product (in the case of duplicate Descriptions)
	 * Returns a true if the same image filename is already being used for a different product. We pass the image_id of our
	 * current product since we want to exclude ourselves from the test.
	 * @param $strFile
	 * @param $intThisProductId
	 * @return bool
	 */
	public static function ExistsForOtherProduct($strFile, $intThisProductId) {

		$objImages = Images::model()->findAllByAttributes(array('image_path'=>$strFile));
		$blnReturn = false;
		foreach ($objImages as $objImage)
			if ($objImage->product_id != $intThisProductId)
				$blnReturn= true;
		return $blnReturn;
	}

	/* Helper function to get full drive path to passed Image file.
	*/
	public static function GetImagePath($strFile) {
		return Yii::getPathOfAlias('webroot') . "/images/${strFile}";
	}
	/* Return full drive path to No Image graphic.
	*/
	public static function GetImageFallbackPath($AbsoluteUrl = false) {
		if ($AbsoluteUrl)
			return Yii::app()->createAbsoluteUrl('/images/no_product.png');
			else
				return 'no_product.png';
	}

	/* Helper function to get full URL to passed Image file.
	*/
	public static function GetImageUri($strFile,$AbsoluteUrl = false) {
		if ($AbsoluteUrl)
			return Yii::app()->createAbsoluteUrl("/images/".$strFile);
		else return Yii::app()->createUrl("images/".$strFile);
	}


	/* Parse the path to the image file, verify folders exist or create them */
	public static function IsWritablePath($strName) {
		$arrPath = mb_pathinfo($strName);

		if ($arrPath['dirname'] != '') {
			$subFolder = Images::GetImagePath('');
			$strPathToCreate = $subFolder.$arrPath['dirname'];
			$strPathToCreate = str_replace("//","/",$strPathToCreate);

			//if ($strPathToCreate[0]=='/') $strPathToCreate=substr($strPathToCreate,1,999);
			if (!file_exists($strPathToCreate))
				if (!mkdir($strPathToCreate,0777,true)) {
					Yii::log("Error attempting to create ".$strPathToCreate, 'error', 'Images');
					return false;
				}
		}

		if (!is_writable($strPathToCreate)) {
			Yii::log("Directory $strPathToCreate is not writable", 'error', 'Images');
			return false;
		}
		return true;
	}
	/* Is this the original graphic provided by LightSpeed */
	public function IsPrimary() {
		if ($this->id && ($this->id == $this->parent))
			return true;
		return false;
	}

	/* Return drive path for loaded Image object $this. */
	public function GetPath() {
		if ($this->image_path)
			return Images::GetImagePath($this->image_path);
		else return Images::GetImagePath('.NoImageFound.');
	}

	/* Test if actual .jpg/.png file exists on drive */
	public function ImageFileExists() {
		if ($this->image_path &&
			file_exists(Images::GetImagePath($this->image_path)))
			return true;
		return false;
	}

	/* Load .jpg/.png into blob */
	public function GetImageData() {
		if ($this->ImageFileExists())
			return file_get_contents($this->GetPath());
		else
			return;
	}


	public function SaveImageFolder($strFolder) {
		if (!$strFolder)
			return true;

		if (!file_exists($strFolder)) {
			if (!mkdir($strFolder, 0777, true)) {
				Yii::log('Error creating : ' . $strFolder, 'Images', __FUNCTION__);
				return false;
			}
		}

		if (!is_writable($strFolder)) {
			Yii::log('Path is not writeable : ' . $strFolder, 'Images', __FUNCTION__);
			return false;
		}

		return true;
	}

	/* Save blob to file */
	public function SaveImageData($strName, $blbImage) {

		$this->DeleteImage();

		$strPath = Images::GetImagePath($strName);
		$arrPath = mb_pathinfo($strPath);

		$strFolder = $arrPath['dirname'];
		$strSaveFunc = 'imagepng';

		if ($arrPath['extension'] == 'jpg')
			$strSaveFunc = 'imagejpeg';

		if ($arrPath['extension'] == 'gif')
			$strSaveFunc = 'imagegif';

		if ($strSaveFunc=="imagepng")
		{
			//Set transparency
			$retVal = $this->check_transparent($blbImage);
			if($retVal)
			{
				imagealphablending($blbImage, false);
				imagesavealpha($blbImage, true);
			}
		}

		if ($this->SaveImageFolder($strFolder) && $strSaveFunc($blbImage, $strPath))
		{
			$this->image_path = $strName;
			return true;
		}
		else {
			Yii::log("Failed to save file $strName", 'image', __FUNCTION__);
			return false;
		}


	}

	protected function check_transparent($im) {

		$width = imagesx($im); // Get the width of the image
		$height = imagesy($im); // Get the height of the image

		// We run the image pixel by pixel and as soon as we find a transparent pixel we stop and return true.
		for($i = 0; $i < $width; $i++) {
			for($j = 0; $j < $height; $j++) {
				$rgba = imagecolorat($im, $i, $j);
				if(($rgba & 0x7F000000) >> 24) {
					return true;
				}
			}
		}

		// If we don't find any pixel the function will return false.
		return false;
	}

	/* If we do not have an image, pass back our default Not Found graphic URL
	*/
	public static function ShowFallback($intWidth = null, $intHeight = null) {
//		if (is_null($intWidth) || is_null($intHeight)) {
//			$intWidth = 100;
//			$intHeight = 100;
//		}
//
//		$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot') . "/images/no_product.png");
//		$image->resize($intWidth, $intHeight)->quality(_xls_get_conf('IMAGE_QUALITY','75'))->sharpen(_xls_get_conf('IMAGE_SHARPEN','20'));
//
//		header('Content-Type: image/png');
//		imagepng($image, NULL, 100);
//		exit();


		$objImage = new Images();
		$objImage->image_path = self::GetImageFallbackPath();
		$objImage->width = 100;
		$objImage->height = 100;

		//print_r($objImage);die();
		return $objImage;
	}


	/**
	 * ToDo: need to update and make photo processors use a more condensed version of this
	 * Create Thumbnail from LightSpeed original file. Technically to Web Store, any resized copy of the original
	 * whether larger or smaller is considered a "thumbnail".
	 * @param $intNewWidth
	 * @param $intNewHeight
	 * @return bool|Images
	 */
	public function CreateThumb($intNewWidth, $intNewHeight) {
		// Delete previous thumbbnail
		if ($this->id) {
			$objImage = Images::LoadByWidthParent(
				$intNewWidth, $this->id);
			if ($objImage)
				$objImage->Delete();
		}

		//Get our original file from LightSpeed
		$strOriginalFile=$this->image_path;
		$strTempThumbnail = Images::GetImageName($strOriginalFile, $intNewWidth, $intNewHeight,'temp');
		$strNewThumbnail = Images::GetImageName($strOriginalFile, $intNewWidth, $intNewHeight);
		$strOriginalFileWithPath=Images::GetImagePath($strOriginalFile);
		$strTempThumbnailWithPath=Images::GetImagePath($strTempThumbnail);
		$strNewThumbnailWithPath=Images::GetImagePath($strNewThumbnail);


		$image = Yii::app()->image->load($strOriginalFileWithPath);
		$image->resize($intNewWidth,$intNewHeight)->quality(_xls_get_conf('IMAGE_QUALITY','75'))->sharpen(_xls_get_conf('IMAGE_SHARPEN','20'));



		if (Images::IsWritablePath($strNewThumbnail)) //Double-check folder permissions
		{
			if (_xls_get_conf('IMAGE_FORMAT','jpg') == 'jpg')
			{   $strSaveFunc = 'imagejpeg';
				$strLoadFunc = "imagecreatefromjpeg";
			} else {
				$strSaveFunc = 'imagepng';
				$strLoadFunc = "imagecreatefrompng";
			}

			$image->save($strTempThumbnailWithPath,false);


			$src = $strLoadFunc($strTempThumbnailWithPath);
			//We've saved the resize, so let's load it and resave it centered
			$dst_file = $strNewThumbnailWithPath;
			$dst = imagecreatetruecolor($intNewWidth, $intNewHeight);
			$colorFill = imagecolorallocate($dst, 255,255,255);
			imagefill($dst, 0, 0, $colorFill);
			if (_xls_get_conf('IMAGE_FORMAT','jpg') == 'png')
				imagecolortransparent($dst, $colorFill);

			$arrOrigSize = getimagesize($strOriginalFileWithPath);
			$arrSize = Images::CalculateNewSize($arrOrigSize[0],$arrOrigSize[1], $intNewWidth,$intNewHeight);
			$intStartX = $intNewWidth/2 - ($arrSize[0]/2);
			imagecopymerge($dst, $src, $intStartX, 0, 0, 0, $arrSize[0], $arrSize[1], 100);



			$strSaveFunc($dst, $dst_file);
			@unlink($strTempThumbnailWithPath);



			//We save it, then pass back to do a redir immediately
			//Make sure we don't have an existing record for whatever reason
			$objNew = Images::LoadByWidthHeightParent($intNewWidth,$intNewHeight,$this->id);

			if (!($objNew instanceof Images))
				$objNew = new Images();
			$objNew->image_path=$strNewThumbnail;
			$objNew->parent = $this->id;
			$objNew->width = $intNewWidth;
			$objNew->height = $intNewHeight;
			$objNew->index = $this->index;
			$objNew->product_id = $this->product_id;

			try {
				if (!$objNew->save())
					Yii::log("Thumbnail creation error ".print_r($objNew->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
			catch (Exception $objExc) {

				Yii::log("Thumbnail creation exception ".$objExc, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
			return $objNew;
			
		} else {
			Yii::log("Directory permissions error attempting to save ".$strNewThumbnail, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}
	}


	/**
	 * Because of aspect ratio, resizing to new width and height won't match new values.
	 * This function calculates the new size you really get based on original
	 * and what you passed.
	 * @param $intWidth
	 * @param $intHeight
	 * @param $intNewX
	 * @param $intNewY
	 */
	public static function CalculateNewSize($intWidth, $intHeight, $intNewWidth, $intNewHeight)
	{

		$rw = $intWidth / $intNewWidth; // width and height are maximum thumbnail's bounds
		$rh = $intHeight / $intNewHeight;

		if ($rw > $rh)
		{
			$newh = round($intHeight / $rw);
			$neww = $intNewWidth;
		}
		else
		{
			$neww = round($intWidth / $rh);
			$newh = $intNewHeight;
		}
		return array($neww,$newh);

	}

	/**
	 * ORM level methods
	 */
	public function DeleteImage() {
		if ($this->ImageFileExists())
			unlink($this->GetPath());
	}

	public function Delete() {
		if (!$this->id)
			return;

		if ($this->IsPrimary())
			foreach (Images::model()->findAllByAttributes(array('parent' => $this->id)) as $objImage)
				if (!$objImage->IsPrimary())
					$objImage->Delete();

		$this->DeleteImage();
		parent::Delete();
	}

	public static function LoadByRowidSize($id, $intSize) {
		if ($intSize == ImagesType::normal)
			return Images::model()->findByPk($id);

		list($intWidth, $intHeight) = ImagesType::GetSize($intSize);

		return Images::LoadByWidthHeightParent(
			$intWidth, $intHeight, $id);
	}

	// Due to the index, Parent+Width must be unique
	public static function LoadByWidthParent($intWidth, $intParent) {

		return Images::model()->findByAttributes(
			array(
				'width' => $intWidth,
				'parent' => $intParent
			)
		);
	}

	public static function LoadByParent($intParent) {

		return Images::model()->find('id=:id AND parent=:t1', array(':id'=>$intParent,':t1'=>$intParent));


	}



	/**
	 * Load a single Images object,
	 * by Width, Height, Parent Index(es)
	 * @param integer $intWidth
	 * @param integer $intHeight
	 * @param integer $intParent
	 * @return Images
	 */
	public static function LoadByWidthHeightParent($intWidth, $intHeight, $intParent)
	{
		return Images::model()->findByAttributes(
			array(
				'width' => $intWidth,
				'height' => $intHeight,
				'parent' => $intParent
			)
		);
	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	public function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		return parent::beforeValidate();
	}

	public function __set($strName, $mixValue) {
		switch ($strName) {
		case 'ImagePath':
			try {
				$this->DeleteImage();
				return parent::__set($strName, $mixValue);
			}
			catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		default:
			try {
				return parent::__set($strName, $mixValue);
			}
			catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}

}