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

        /**
         * Return valid filename not already in use based on passed path/filename
         * and parameters
         * @return string 
         */
        public static function GetImageName($strName,
            $intWidth = 0, $intHeight = 0, $intIndex = 0, $strClass = null, 
            $blnIsThumb = false) {

            $strName = pathinfo($strName, PATHINFO_FILENAME);

            if (!empty($intIndex))
                $strName .= '_' . $intIndex;

            if (!empty($strClass))
                $strName .= '_' . $strClass;

            if (!empty($intWidth) && !empty($intHeight))
                $strName .= '_' . $intWidth . '_' . $intHeight;

            return $strName . '.jpg';
        }

        public static function GetImagePath($strFile, $strSubFolder=null) {
            if ($strSubFolder != null)
                {
                    if (!file_exists(__DOCROOT__ . __PHOTOS__ . "/".$strSubFolder))
                        @mkdir(__DOCROOT__ . __PHOTOS__ . "/".$strSubFolder);
                    return __DOCROOT__ . __PHOTOS__ . "/".$strSubFolder."${strFile}";
                }
            else
                return __DOCROOT__ . __PHOTOS__ . "/${strFile}";           
        }

        public static function GetImageFallbackPath() {
            return __DOCROOT__ . __IMAGE_ASSETS__ . '/no_product.png';
        }

        public static function GetImageUri($strFile) {
            return __PHOTOS__ . "/${strFile}";
        }

        public static function GetImageLink($intRowid, 
            $intType = ImagesTypes::normal) {

            $objImage = Images::LoadByRowidSize($intRowid, $intType);
            if ($objImage && $objImage->ImageFileExists())
                return Images::GetImageUri($objImage->ImagePath);

            $strType = ImagesType::ToString($intType);
            return "index.php?$strType=$intRowid";
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
            return "index.php?image=$this->intRowid";
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

        public function SetImage($blbImage, $strName = false){
            QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
            if (!$strName)
                $strName = $this->intRowid;

            $strName = Images::GetImageName($strName);
            return $this->SaveImageData($strName, $blbImage);
        }

        public function SaveImageData($strName, $blbImage, $strSubFolders=null) {
            if ($strName && (_xls_get_conf('IMAGE_STORE' , 'FS') == 'FS')) {
                $strPath = Images::GetImagePath($strName, $strSubFolders);

                if (file_put_contents($strPath, $blbImage)) { 
                    $this->strImagePath = ($strSubFolders != null ? $strSubFolders : "").$strName;
                    $this->strImageData = null;
                }
                else {
                    $this->strImageData = $blbImage;
                    QApplication::Log(E_USER_ERROR, 'image', 
                        "Failed to save file $strName");
                }
            }
            else { 
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
                _rd(Images::GetImageUri($this->ImagePath));
                exit();
            }
            else { 
                header('Content-Type: image/jpeg');
                $img = imagecreatefromstring($this->ImageData);
                echo imagejpeg($img, NULL, 100);
                exit();
            }
        }

        public function ShowThumb($intWidth, $intHeight) {
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

            QApplication::Log(E_WARNING, 'image',
                "No image data for {$this->intRowid}");

            $rawImage = file_get_contents(
                Images::GetImageFallbackPath());
            $rawImage = imagecreatefromstring($rawImage);

            if (array($intWidth, $Height) != 
                ImagesType::GetSize(ImagesType::normal))
                    $rawImage = Images::Resize(
                        $rawImage, $intWidth, $intHeight);

            header('Content-Type: image/jpeg');
            imagejpeg($rawImage, NULL, 100);
            exit();
        }

        public function CreateThumb($intNewWidth, $intNewHeight) {
            // Delete previous thumbbnail 
            if ($this->intRowid) { 
                $objImage = Images::LoadByWidthParent(
                    $intNewWidth, $this->intRowid);
                if ($objImage)
                    $objImage->Delete();
            }

            if ($this->ImageFileExists())
                $rawImage = imagecreatefromstring(
                    file_get_contents(Images::GetImagePath($this->ImagePath))); 
            else
                $rawImage = imagecreatefromstring($this->ImageData);

            $rawNewImage = Images::Resize(
                $rawImage, $intNewWidth, $intNewHeight);

            if (!$this->Rowid) {
                // if it is the no product image, just output
                header('Content-Type: image/jpeg');
                imagejpeg($rawNewImage, NULL, 100);
                return null;
            }

            if (strpos($this->ImagePath,"/") !== false){
                //Image is in subfolder structure
                $strFilename=explode("/",$this->ImagePath);
                $strImageName = Images::GetImageName(
                    $this->ImagePath, $intNewWidth, $intNewHeight);
            }
            else
                $strImageName = Images::GetImageName(
                   $this->intRowid, $intNewWidth, $intNewHeight); //Backwards compatibility
            
            
            $objNew = new Images();

            ob_start();
            imagejpeg($rawNewImage, NULL, 100);
            $objNew->SaveImageData($strImageName, ob_get_contents(),isset($strFilename[0]) ? $strFilename[0]."/" : null);
            ob_end_clean();

            $objNew->Created = QDateTime::Now(true);
            $objNew->Parent = $this->Rowid;
            $objNew->intWidth = $intNewWidth;
            $objNew->intHeight = $intNewHeight;

            $objNew->Save(true);

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

?>
