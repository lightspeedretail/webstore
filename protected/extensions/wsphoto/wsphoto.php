<?php

//Default photo processor
class wsphoto extends CApplicationComponent {


	public $category = "CEventPhoto";
	public $name = "Web Store Internal";

	//Event map
	//onUploadPhoto()


	/**
	 * Attached event for anytime a product photo is uploaded
	 * @param $event
	 * @return bool
	 */
	public function onUploadPhoto($event)
	{

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
		else
			$objImage->DeleteImage();

		//Assign width and height of original
		$objImage->width = imagesx($blbImage);
		$objImage->height = imagesy($blbImage);

		//Assign filename this image, actually write the binary file
		$strImageName = Images::AssignImageName($objProduct,$intSequence);
		$objImage->SaveImageData($strImageName, $blbImage);

		$objImage->product_id=$objProduct->id;
		$objImage->index=$intSequence;


		//Save image record
		Yii::trace("saving $strImageName",'application.'.__CLASS__.".".__FUNCTION__);
		if (!$objImage->save()) {
			Yii::log("Error saving image " . print_r($objImage->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		$objImage->parent = $objImage->id; //Assign parent to self
		$objImage->save();

		//Update product record with imageid if this is a primary
		if ($intSequence==0)
		{
			$objProduct->image_id = $objImage->id;
			if (!$objProduct->save()) {
				Yii::log("Error updating product " . print_r($objProduct->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		}

		$this->createThumbnails($objProduct,$objImage);

		return true;


	}


	/**
	 * Create all our thumbnail sizes are part of our upload process
	 * @param $objImage
	 */
	protected function createThumbnails($objProduct,$objImage)
	{



		foreach(ImagesType::$NameArray as $intType=>$value)
		{

			if ($intType>0) //exclude original size
			{
				list($intWidth, $intHeight) = ImagesType::GetSize($intType);
				$this->createThumb($objImage,$intWidth,$intHeight);
			}

		}

	}


	/**
	 * Create thumbnail for image in specified size
	 * @param $objImage
	 * @param $intNewWidth
	 * @param $intNewHeight
	 */
	protected function createThumb($objImage,$intNewWidth,$intNewHeight)
	{

		//Get our original file from LightSpeed
		$strOriginalFile=$objImage->image_path;
		$strTempThumbnail = Images::GetImageName($strOriginalFile, $intNewWidth, $intNewHeight,'temp');
		$strNewThumbnail = Images::GetImageName($strOriginalFile, $intNewWidth, $intNewHeight);
		$strOriginalFileWithPath=Images::GetImagePath($strOriginalFile);
		$strTempThumbnailWithPath=Images::GetImagePath($strTempThumbnail);
		$strNewThumbnailWithPath=Images::GetImagePath($strNewThumbnail);


		$image = Yii::app()->image->load($strOriginalFileWithPath);
		$image->resize($intNewWidth,$intNewHeight)
			->quality(_xls_get_conf('IMAGE_QUALITY','75'))
			->sharpen(_xls_get_conf('IMAGE_SHARPEN','20'));



		if (Images::IsWritablePath($strNewThumbnail)) //Double-check folder permissions
		{
			if (_xls_get_conf('IMAGE_FORMAT','jpg') == 'jpg')
			{   $strSaveFunc = 'imagejpeg';
				$strLoadFunc = "imagecreatefromjpeg";
			} else {
				$strSaveFunc = 'imagepng';
				$strLoadFunc = "imagecreatefrompng";
			}

			$hexbg = _xls_get_conf('IMAGE_BACKGROUND');
			if(!empty($hexbg))
			{
				//Place image on colored background to better position images within theme
				$image->save($strTempThumbnailWithPath,false);

				$src = $strLoadFunc($strTempThumbnailWithPath);
				//We've saved the resize, so let's load it and resave it centered
				$dst_file = $strNewThumbnailWithPath;
				$dst = imagecreatetruecolor($intNewWidth, $intNewHeight);

				$rgb = hex2rgb($hexbg);
				$colorFill = imagecolorallocate($dst, $rgb[0],$rgb[1],$rgb[2]);
				imagefill($dst, 0, 0, $colorFill);
				if (_xls_get_conf('IMAGE_FORMAT','jpg') == 'png')
					imagecolortransparent($dst, $colorFill);
				$arrOrigSize = getimagesize($strOriginalFileWithPath);
				$arrSize = Images::CalculateNewSize($arrOrigSize[0],$arrOrigSize[1], $intNewWidth,$intNewHeight);
				$intStartX = $intNewWidth/2 - ($arrSize[0]/2);

				imagecopymerge($dst, $src, $intStartX, 0, 0, 0, $arrSize[0], $arrSize[1], 100);

				$strSaveFunc($dst, $dst_file);
				@unlink($strTempThumbnailWithPath);

			}
			else
				$image->save($strNewThumbnailWithPath); //just save normally with no special effects


			//See if we have a thumbnail record in our Images table, create or update
			$objThumbImage = Images::model()->findByAttributes(
				array(
					'width' => $intNewWidth,
					'height' => $intNewHeight,
					'index' => $objImage->index,
					'parent' => $objImage->id,
					'product_id' => $objImage->product_id
				)
			);

			if (!($objThumbImage instanceof Images))
			{
				$objThumbImage = new Images();
				Images::model()->deleteAllByAttributes(array('width'=>$intNewHeight,'height'=>$intNewHeight,'parent'=>$objImage->id)); //sanity check to prevent SQL UNIQUE errors
			}

			$objThumbImage->image_path = $strNewThumbnail;
			$objThumbImage->width = $intNewWidth;
			$objThumbImage->height = $intNewHeight;
			$objThumbImage->parent = $objImage->id;
			$objThumbImage->index = $objImage->index;
			$objThumbImage->product_id = $objImage->product_id;
			$objThumbImage->save();

		}
		else
			Yii::log("Directory permissions error writing " . $strNewThumbnail, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		
	}


}


?>