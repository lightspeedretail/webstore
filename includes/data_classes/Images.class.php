<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

require(__DATAGEN_CLASSES__ . '/ImagesGen.class.php');

/**
 * The Images class defined here contains any customized code for the
 * Images table in the Object Relational Model.
 */
class Images extends ImagesGen {
	// String representation of object
	public function __toString() {
		return sprintf('Images Object %s at %sx%s',
			$this->intRowid, $this->intWidth, $this->intHeight);
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

	/**
	 * Static helper functions
	 */

	/**
	 * Return the size of an Image Type
	 * @param string $strType :: Image constant defined in TypeDefaultSizes
	 * @return array (width,height)
	 */
	public static function GetSize($strType) {
		$intType = ImagesType::ToToken($strType);
		return ImagesType::GetSize($intType);
	}

	// $strName == intRowid
	public static function GetImageName($strName,
		$intWidth = 0, $intHeight = 0, $intIndex = 0, $strClass = null,
		$blnIsThumb = false, $strSection = 'product') {

		$strName = pathinfo($strName, PATHINFO_FILENAME);

		if (!empty($strClass))
			$strName .= '-' . $strClass;

		if (!empty($intIndex))
			$strName .= '-' . $intIndex;

		if (!empty($intWidth) && !empty($intHeight))
			$strName .= '-' . $intWidth . 'px-' . $intHeight . "px";

		$fileExt =  strtolower(_xls_get_conf('IMAGE_FORMAT','jpg'));
		if ($intWidth==0 && $intHeight==0) $fileExt="png"; //The file from LS is always png

		return $strSection . "/" . $strName[0] . "/" . $strName . '.' . $fileExt;
	}

	/**
	 * Checks to see if the filename we want to use is being used for another product (in the case of duplicate Descriptions)
	 * @param $strFile
	 * @param $intThisProductId
	 * @return bool
	 */
	public static function ExistsForOtherProduct($strFile, $intImageId){

		$objImages = Images::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Images()->ImagePath, $strFile)
			)
		);

		$blnReturn = false;
		foreach ($objImages as $objImage)
		if ($objImage->Rowid != $intImageId)
			$blnReturn= true;
		return $blnReturn;
	}
	public static function GetImagePath($strFile) {
		return __DOCROOT__ . __PHOTOS__ . "/${strFile}";
	}

	public static function GetImageFallbackPath() {
		return __DOCROOT__ . __IMAGE_ASSETS__ . '/no_product.png';
	}

	public static function GetImageUri($strFile) {
		return __PHOTOS__ . "/${strFile}";
	}

	public static function GetImageLink($intRowid,
		$intType = ImagesType::normal) {

		$objImage = Images::LoadByRowidSize($intRowid, $intType);
		if ($objImage && $objImage->ImageFileExists())
			return Images::GetImageUri($objImage->ImagePath);

		$strType = ImagesType::ToString($intType);
		return _xls_site_url("ctn/photo?$strType=$intRowid");
	}

	// LEGACY
	public static function GetUrl($strName, $strType) {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		$intType = ImagesType::ToToken($strType);
		return Images::GetImageLink($strName, $intType);
	}

	// LEGACY
	public static function GetDim($strType , &$new_width , &$new_height) {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		list($intWidth, $intHeight) = Images::GetSize($strType);
		$new_width = $intWidth;
		$new_height = $intHeight;
	}

	public static function Resize($rawImage, $intNewWidth, $intNewHeight) {
		$intWidth = imagesx($rawImage);
		$intHeight = imagesy($rawImage);

		// Calculations for the new thumbnail size
		if ($intNewHeight > $intNewWidth)
			$strNewScale = 'y';
		else
			$strNewScale = 'x';

		// Calculations for the old thumbnail size
		if ($intHeight > $intWidth)
			$strScale = 'y';
		else
			$strScale = 'x';

		if ($strScale == 'y')
			$intRatio = $intWidth / $intHeight;
		else
			$intRatio = $intHeight / $intWidth;

		// Ratios
		$intRatioY = $intNewHeight / $intHeight;
		$intRatioX = $intNewWidth / $intWidth;

		// Recalculate sizes
		if (($strNewScale == 'y') && ($strScale == 'y')) {
			$intNewWidth = intval($intRatioY * $intWidth);
		}
		else if (($strNewScale == 'x') && ($strScale == 'x')) {
			$intNewHeight = intval($intRatioX * $intHeight);
		}
		else if (($strNewScale == 'y') && ($strScale == 'x')) {
			$intNewHeight = intval($intRatioX * $intHeight);
		}
		else if (($strNewScale == 'x') && ($strScale == 'y')) {
			$intNewWidth = intval($intRatioY * $intWidth);
		}

		$rawNewImage = ImageCreateTrueColor($intNewWidth , $intNewHeight);

		if (!$rawNewImage)
			return $rawImage;

		if(!imagecopyresampled(
			$rawNewImage, $rawImage, 0, 0, 0, 0,
			$intNewWidth, $intNewHeight,
			$intWidth, $intHeight))
			return $rawImage;

		return $rawNewImage;
	}

	/**
	 * Class methods
	 */

	public function IsPrimary() {
		if ($this->intRowid && ($this->intRowid == $this->intParent))
			return true;
		return false;
	}

	public function GetLink() {
		if ($this->ImageFileExists())
			return Images::GetImageUri($this->ImagePath);
		return _xls_site_url("ctn/photo?image=$this->intRowid");
	}

	public function GetPath() {
		if ($this->ImagePath)
			return Images::GetImagePath($this->ImagePath);
		else return Images::GetImagePath('.NoImageFound.');
	}

	public function ImageExists() {
		if ($this->ImageFileExists() || $this->strImageData)
			return true;
		return false;
	}

	public function ImageFileExists() {
		if ($this->ImagePath &&
			file_exists(Images::GetImagePath($this->ImagePath)))
			return true;
		return false;
	}

	public function GetImageData() {
		if ($this->ImageFileExists())
			return file_get_contents($this->GetPath());
		elseif ($this->strImageData)
			return $this->strImageData;
		else
			return;
	}

	public function SetImage($blbImage, $strName = false) {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		if (!$strName)
			$strName = $this->intRowid;

		$strName = Images::GetImageName($strName);
		return $this->SaveImageData($strName, $blbImage);
	}

	public function SaveImageFolder($strFolder) {
		if (!$strFolder)
			return true;

		if (!file_exists($strFolder)) {
			if (!mkdir($strFolder, 0755, true)) {
				QApplication::Log(E_ERROR, 'Images',
					'Error creating : ' . $strFolder);
				return false;
			}
		}

		if (!is_writable($strFolder)) {
			QApplication::Log(E_ERROR, 'Images',
				'Path is not writeable : ' . $strFolder);
			return false;
		}

		return true;
	}


	public function SaveImageData($strName, $blbImage) {
		if ($strName && (_xls_get_conf('IMAGE_STORE' , 'FS') == 'FS')) {
			$this->DeleteImage();

			$strPath = Images::GetImagePath($strName);
			$arrPath = pathinfo($strPath);

			$strFolder = $arrPath['dirname'];
			$strSaveFunc = 'imagepng';

			if ($arrPath['extension'] == 'jpg')
				$strSaveFunc = 'imagejpeg';

			if ($arrPath['extension'] == 'gif')
				$strSaveFunc = 'imagegif';

			if ($this->SaveImageFolder($strFolder) &&
				$strSaveFunc($blbImage, $strPath))
			{
				$this->strImagePath = $strName;
				$this->strImageData = null;
			}
			else {
				$this->strImageData = $blbImage;
				QApplication::Log(E_USER_ERROR, 'image',
					"Failed to save file $strName");
			}
		} else {
			$this->strImageData = $blbImage;
		}

		imagedestroy($img);
	}

	public function Show() {
		if (!$this->ImageExists())
			if (!$this->IsPrimary()) {
				$parent = Images::Load($this->intParent);
				$parent->ShowThumb($this->Width, $this->Height);
			}
			else $this->ShowFallback();

		if ($this->ImageFileExists()) {
			_xls_301(Images::GetImageUri($this->ImagePath));
			exit();
		}
		else {
			header('Content-Type: image/png');
			$img = imagecreatefromstring($this->ImageData);
			echo imagepng($img, NULL, 100);
			exit();
		}
	}

	public function ShowThumb($intWidth, $intHeight) {
		// If either dimension matches we are not looking for a thumbnail
		if ($this->Width == $intWidth && $this->Height == $intHeight)
			$this->Show();

		$thumb = Images::LoadByWidthHeightParent(
			$intWidth, $intHeight, $this->intRowid);

		if (!$thumb || !$thumb->ImageExists())
			if ($this->ImageExists())
				$thumb = $this->CreateThumb($intWidth, $intHeight);
			else
				return $this->ShowFallback($intWidth, $intHeight);

		$thumb->Show();
	}

	public function ShowFallback($intWidth = null, $intHeight = null) {
		if (is_null($intWidth) || is_null($intHeight)) {
			$intWidth = $this->Width;
			$intHeight = $this->Height;
		}
		$rawImage = imagecreatefrompng(Images::GetImageFallbackPath());
		$rawImage = Images::Resize($rawImage, $intWidth, $intHeight);
		header('Content-Type: image/png');
		imagepng($rawImage);


		exit();
	}

	public function CreateThumb($intNewWidth, $intNewHeight) {
		// Delete previous thumbnail
		if ($this->intRowid) {
			$objImage = Images::LoadByWidthParent(
				$intNewWidth, $this->intRowid);
			if ($objImage)
				$objImage->Delete();

		}

		if ($this->ImageFileExists()) {
			$rawImage = imagecreatefrompng(Images::GetImagePath($this->ImagePath));
			if (!$rawImage){
				QApplication::Log(E_ERROR, 'CreateThumb', "Failure on Create PNG on ".$this->ImagePath);
				//PNG failed, test for other types of files
				$intImgType = exif_imagetype(Images::GetImagePath($this->ImagePath));
				if ($intImgType==IMAGETYPE_JPEG)
					$rawImage = imagecreatefromjpeg(Images::GetImagePath($this->ImagePath));
				if (!$rawImage) {
					QApplication::Log(E_ERROR, 'CreateThumb', "Failure on imagetype ".(!empty($intImgType) ? $intImageType : "UNKNOWN")." on ".$this->ImagePath);
					$rawImage = imagecreatefromstring(file_get_contents(Images::GetImageFallbackPath()));
				}
			}
		}
		else
			$rawImage = imagecreatefromstring($this->ImageData);

		if (!$this->Rowid) {
			// if it is the no product image, just output
			$rawImage = imagecreatefromstring(file_get_contents(
				Images::GetImageFallbackPath()));
		}
		$rawNewImage = Images::Resize($rawImage, $intNewWidth, $intNewHeight);

		$strExistingName=$this->strImagePath;
		$strImageName = Images::GetImageName(
			$strExistingName, $intNewWidth, $intNewHeight);

		//We save it, then pass back to do a redir immediately
		$objNew = new Images();
		$objNew->ImagePath=$strImageName;
		$objNew->Created = QDateTime::Now(true);
		$objNew->Parent = $this->Rowid;
		$objNew->intWidth = $intNewWidth;
		$objNew->intHeight = $intNewHeight;

		$objNew->Save(true);

		$objNew->SaveImageData(
			$strImageName, $rawNewImage
		);

		imagedestroy($rawNewImage);
		imagedestroy($rawImage);

		return $objNew;
	}

	/**
	 * ORM level methods
	 */
	public function DeleteImage() {
		if ($this->ImageFileExists())
			unlink($this->GetPath());
	}

	public function Delete() {
		if (!$this->Rowid)
			return;

		if ($this->IsPrimary())
			foreach (Images::LoadByParent($this->Rowid) as $objImage)
				if (!$objImage->IsPrimary())
					$objImage->Delete();

		$this->DeleteImage();
		parent::Delete();
	}

	public static function LoadByRowidSize($intRowid, $intSize) {
		if ($intSize == ImagesType::normal)
			return Images::LoadByRowid($intRowid);

		list($intWidth, $intHeight) = ImagesType::GetSize($intSize);

		return Images::LoadByWidthHeightParent(
			$intWidth, $intHeight, $intRowid);
	}

	// Due to the index, Parent+Width must be unique
	public static function LoadByWidthParent($intWidth, $intRowid) {
		return Images::QuerySingle(
			QQ::AndCondition(
				QQ::Equal(QQN::Images()->Width, $intWidth),
				QQ::Equal(QQN::Images()->Parent, $intRowid)
			)
		);
	}

	public static function LoadByParent($intRowid) {
		return Images::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Images()->Parent, $intRowid)
			)
		);
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
