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

    define('__PREPEND_QUICKINIT__', true);

    ob_start();
    require('includes/prepend.inc.php');    
    ob_end_clean();

    class XLSWService extends QSoapService {
        
        
        const FAIL_AUTH = "FAIL_AUTH";
        const NOT_FOUND = "NOT_FOUND";
        const OK = "OK";
        const UNKNOWN_ERROR = "UNKNOWN_ERROR";
        
                
        
        /**
         * Return's the webstore version
         * 
         * @param string $passkey
         * @return string
         */
        public function ws_version($passkey){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                            
            
            return _xls_version();
            
        }
        
        
        
        /**
         * Update a ORM's field value
         *
         * @param string $passkey
         * @param string $strOrm
         * @param int $intRowid
         * @param string $strColname
         * @param string $strValue
         * @return string
         */
        public function edit_orm_field($passkey , $strOrm, $intRowid , $strColname , $strValue ) {

            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                            
            if(!class_exists($strOrm)){
                _xls_log("SOAP ERROR: ORM not found $strOrm");
                return self::UNKNOWN_ERROR;
            }
                
            $orm = new $strOrm;
            
            $record = $orm->Load($intRowid);
            
            if(!$record){
                return self::UNKNOWN_ERROR;
            }
            
            try{
                $record->$strColname = $strValue;
                $record->Save();
            }catch(Exception $e){
                _xls_log("SOAP ERROR: ORM unable to save value $strValue for record $intRowid");
                return self::UNKNOWN_ERROR;
            }
            
            return self::OK;
            
        }       
        
                
        
        
        /**
         * Get specified columns from a table in JSON format
         *
         * @param string $passkey
         * @param string $dbtable
         * @param string $columns
         * @param string $where
         * @return string
         */
        private function get_records($passkey , $dbtable, $columns , $where ) {

            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                            
            $output = self::load_records($dbtable , $columns , array($where));
            
            //return "Giving table $table";
            return $this->xls_output($output);
            
        }
        
        /**
         * Run a command and return it's output
         * 
         * @param string $passkey
         * @param string $command
         * @return string
         */
        public function run_command($passkey , $command){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                            
            ob_start();
            
            eval($command);
            
            $output = ob_get_contents();
            
            ob_end_clean();
            
            return $output;
            
        }
        
        
        
        /**
         * Get timestamp for the given Datetime (in webstore's timezone) in format of YYYY-MM-DD hh:mm:ss
         * 
         * @param string $passkey
         * @param string $strDatetime
         * @return int
         */
        public function get_timestamp($passkey , $strDatetime){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                                        
            if($strDatetime)
                return strtotime($strDatetime);
            else
                return time();
            
        }       
        
        
        
        
        
        /**
         * Confirm password is valid
         *
         * @param string $passkey
         * @return string
         */
        public function confirm_passkey($passkey){
            sleep(2); // two second delay to prevent/discourage brute force attack..
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                
            return self::OK;
        }
        
        
        
        
        
        
        
        /**
         * Check whether auth passkey is valid or not
         * 
         * @param string $passkey
         * @return int
         */
        protected function check_passkey($passkey){            
            $conf = Configuration::LoadByKey('LSKEY');
            
            
            if(!$conf){
                
                _xls_log("SOAP ERROR : Auth key LSKEY not found in configuration!");
                
                return 0;
            }
            
            
            // Check IP address
            $ips = _xls_get_conf('LSAUTH_IPS');
            if ((trim($ips) != '')){
                
                $found = false;
                
                foreach (explode(',', $ips) as $ip)
                    if ($_SERVER['REMOTE_ADDR'] == trim($ip))
                        $found = true;
            

                if($found == false){
                    _xls_log("SOAP ERROR :  Unauthorised SOAP Access from " . $_SERVER['REMOTE_ADDR'] . " - IP address is not in authorised list.");
                    
                    return 0;
                    
                }
                        
            }
            
            
            if($conf->Value == strtolower(md5($passkey)))
                return 1;
            else{
                
                _xls_log("SOAP ERROR :  Unauthorised SOAP Access from " . $_SERVER['REMOTE_ADDR'] . " - Password did not match.");
                
                return 0;
                
            }
            
        }       
        
        /**
         * update passkey
         * 
         * @param string $passkey
         * @param string $newpasskey
         * @return string
         */
        public function update_passkey($passkey , $newpasskey){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $conf = Configuration::LoadByKey('LSKEY');
            
            if(!$conf){
                
                _xls_log("SOAP ERROR : Auth key LSKEY not found for updating password in configuration!");
                
                return self::UNKNOWN_ERROR;
            }
            
            $conf->Value = strtolower(md5($newpasskey));
            
            $conf->Save();

            return self::OK;
            
        }       
        
        
        
        /**
         * Get configuration
         * 
         * @param string $passkey
         * @param string $confkey
         * @return string
         */
        public function get_config($passkey , $confkey){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $conf = Configuration::LoadByKey($confkey);

            if(!$conf)
                return self::NOT_FOUND;
            
            
            return $conf->Value;
            
        }               
        
        
        /**
         * Update configuration
         * 
         * @param string $passkey
         * @param string $confkey
         * @param string $value
         * @return string
         */
        public function update_config($passkey , $confkey , $value){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $conf = Configuration::LoadByKey($confkey);
            
            if(!$conf)
                return self::NOT_FOUND;
            
            $conf->Value = $value;
            
            $conf->Save();
            
            return self::OK;
            
        }           
        
        
        
        /**
         * Get a product by code
         * 
         * @param string $passkey
         * @param int $intRowid
         * @return string
         */
        public function get_product_by_code($passkey , $intRowid){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $product = Product::Load($intRowid);
            
            if(!$product){
                return new Product();
            }
                    
            return $this->qobject_to_string($product);
            
        }

        public function save_product_image($passkey,
            $intRowid, $rawImage, $product = null) {

            $strPasskey = $passkey;
            $blbRawImage = $rawImage;
            $objProduct = $product;

            if(!$this->check_passkey($strPasskey))
                return self::FAIL_AUTH;

            if (!$blbRawImage) {
                QApplication::Log(E_ERROR, 'uploader',
                    'Did not receive image data for ' . $intRowid, __FUNCTION__);                return self::UNKNOWN_ERROR;
            }    

            if (is_null($objProduct))
                $objProduct = Product::Load($intRowid);

            if (!$objProduct) {
                QApplication::Log(E_ERROR, 'uploader',
                    "Product Id does not exist $intRowid", __FUNCTION__);
                return self::UNKNOWN_ERROR;
            }    

            $blbImage = imagecreatefromstring($blbRawImage);
            $objImage = false;

            if ($objProduct->ImageId)
                $objImage = Images::LoadByRowid($objProduct->ImageId);

            if (!$objImage)
                $objImage = new Images();

            $objImage->Width = imagesx($blbImage);
            $objImage->Height = imagesy($blbImage);

            if (!$objImage->Created)
                $objImage->Created = new QDateTime(QDateTime::Now);

            $objImage->SaveImageData(
                Images::GetImageName($intRowid), $blbRawImage
            );
            $objImage->Save();

            if ((!$objProduct->ImageId) ||
                ($objProduct->ImageId != $objImage->Rowid)) {
                $objProduct->ImageId = $objImage->Rowid;
                $objProduct->Save();
            }

            if (!$objImage->Parent) {
                $objImage->Parent = $objImage->Rowid;
                $objImage->Save();
            }

            return self::OK;
        }

        /**
         * Save a product in the database (Create if need be)
         *
         * @param string $passkey
         * @param int $intRowid
         * @param string $strCode
         * @param string $strName
         * @param string $blbImage
         * @param string $strClassName
         * @param int $blnCurrent
         * @param string $strDescription
         * @param string $strDescriptionShort
         * @param string $strFamily
         * @param int $blnGiftCard
         * @param int $blnInventoried
         * @param double $fltInventory
         * @param double $fltInventoryTotal
         * @param int $blnMasterModel
         * @param int $intMasterId
         * @param string $strProductColor
         * @param string $strProductSize
         * @param double $fltProductHeight
         * @param double $fltProductLength
         * @param double $fltProductWidth
         * @param double $fltProductWeight
         * @param int $intTaxStatusId
         * @param double $fltSell
         * @param double $fltSellTaxInclusive
         * @param double $fltSellWeb
         * @param string $strUpc
         * @param int $blnOnWeb
         * @param string $strWebKeyword1
         * @param string $strWebKeyword2
         * @param string $strWebKeyword3
         * @param int $blnFeatured
         * @param string $strCategoryPath
         * @return string
         */
        public function save_product(
                  $passkey 
                , $intRowid
                , $strCode 
                , $strName 
                , $blbImage 
                , $strClassName 
                , $blnCurrent 
                , $strDescription 
                , $strDescriptionShort 
                , $strFamily 
                , $blnGiftCard
                , $blnInventoried
                , $fltInventory
                , $fltInventoryTotal
                , $blnMasterModel
                , $intMasterId
                , $strProductColor
                , $strProductSize
                , $fltProductHeight
                , $fltProductLength
                , $fltProductWidth
                , $fltProductWeight
                , $intTaxStatusId
                , $fltSell
                , $fltSellTaxInclusive
                , $fltSellWeb
                , $strUpc
                , $blnOnWeb
                , $strWebKeyword1
                , $strWebKeyword2
                , $strWebKeyword3
                , $blnFeatured
                , $strCategoryPath
                ){

            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $blnForceInsert = false;
            $blnForceUpdate = false;

            // We must preservice the Rowid of Products within the Web Store
            // database and must therefore see if it already exists
            $product = Product::LoadByRowid($intRowid);

            if ($product) {
                $blnForceInsert = false;
                $bnForceUpdate = true;
            }
            else { 
                $blnForceInsert = true;
                $bnForceUpdate = false;
            }

            $objDatabase = Product::GetDatabase();
            $strSqlCode = $objDatabase->SqlVariable($strCode);

            $strQuery = <<<EOS
    SELECT rowid
    FROM xlsws_product
    WHERE code = {$strSqlCode};
EOS;

            $intDuplicateId = _dbx_first_cell($strQuery);
            if ($intDuplicateId)
                if ($intDuplicateId != $intRowid) { 
                    QApplication::Log(E_ERROR, 'uploader', 
                        'Duplicate product code found : ' . $strCode, 
                        __FUNCTION__);
                    return false;
                }

            if (!$product) { 
                $product = new Product();
                $product->Rowid = $intRowid;
            }

            $product->Code = $strCode;
            $product->Name = $strName;
            $product->ClassName = $strClassName;
            $product->Current = $blnCurrent;
            $product->Description = $strDescription;
            $product->DescriptionShort = $strDescriptionShort;
            $product->Family = $strFamily;
            $product->GiftCard = $blnGiftCard;
            $product->Inventoried = $blnInventoried;
            $product->Inventory = $fltInventory;
            $product->InventoryTotal = $fltInventoryTotal;
            $product->MasterModel = $blnMasterModel;
            $product->FkProductMasterId = $intMasterId;
            $product->ProductColor = $strProductColor;
            $product->ProductSize = $strProductSize;
            $product->ProductHeight = $fltProductHeight;
            $product->ProductLength = $fltProductLength;
            $product->ProductWidth = $fltProductWidth;
            $product->ProductWeight = $fltProductWeight;
            $product->FkTaxStatusId = $intTaxStatusId;

            $product->Sell = $fltSell;
            $product->SellTaxInclusive = $fltSellTaxInclusive;
            if($fltSellWeb != 0)
                $product->SellWeb = $fltSellWeb;
            else
                $product->SellWeb = $fltSell;

            $product->Upc = $strUpc;
            $product->Web = $blnOnWeb;
            $product->WebKeyword1 = $strWebKeyword1;
            $product->WebKeyword2 = $strWebKeyword2;
            $product->WebKeyword3 = $strWebKeyword3;
            $product->Featured = $blnFeatured;

            // Now save the product
            try {
                $product->Save($blnForceInsert, $blnForceUpdate, true);
            }
            catch(Exception $e) {
                QApplication::Log(E_ERROR, 'uploader', 
                    "Product update failed for $strCode . Error: " . $e);
                return self::UNKNOWN_ERROR . $e;
            }

            // Save the product image
            $blbImage = trim($blbImage);
            if($blbImage && ($blbImage = base64_decode($blbImage))) {
                $this->save_product_image(
                  $passkey 
                , $intRowid
                , $blbImage 
                , $product);
            }

            // Save category
            $strCategoryPath = trim($strCategoryPath);
            // WS2.0.2 0 ignore default
            if($strCategoryPath && ($strCategoryPath != "Default")) {
                $categs = explode("\t", $strCategoryPath);

                // find the category to put in
                $categid = 0;
                foreach($categs as $categ){
                    $category = $this->categ_get_id($categ , $categid);
                    $categid = $category->Rowid;
                    $hasproduct = $category->HasProduct($intRowid);

                    if (!$hasproduct) { 
                        $sql = "DELETE from `xlsws_product_category_assn`" .
                           " WHERE `product_id` = '$intRowid'";
                        _dbx($sql);

                        // hard coding the insert
                        $sql = '
                        REPLACE INTO `xlsws_product_category_assn` (
                            `product_id`,
                            `category_id`
                        ) VALUES (
                            ' . ($intRowid) . ',
                            ' . ($category->Rowid) . '
                        )
                        ';
                        _dbx($sql);
                        $category->UpdateChildCount();
                    }
                }
            }
            return self::OK;
        }
        
        
        
        
        /**
         * Add an additonal image to a product id
         *
         * @param string $passkey
         * @param string $intRowid
         * @param string $blbImage
         * @return string
         */
        public function add_additional_product_image($passkey , $intRowid , $blbImage){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;


            $product = Product::Load($intRowid);
            
            if(!$product){
                QApplication::Log(E_WARNING, 'uploader', 
                    "Product ID does not exist $intRowid", __FUNCTION__);
                return self::UNKNOWN_ERROR;
            }
                
            $count = $product->CountImagesesAsImage();
            
            $blbImage = trim($blbImage);
            
            if($blbImage)
                $blbImage = base64_decode($blbImage);
            
            return $this->add_additional_product_image_at_index($passkey , $intRowid , $blbImage, $count);
            
        }
        
        
        
        /**
         * Add an additonal image to a product id
         *
         * @param string $passkey
         * @param string $intRowid
         * @param string $rawImage
         * @param integer $image_index
         * @return string
         */
        public function add_additional_product_image_at_index($passkey,
            $intRowid, $rawImage, $image_index) {

            $strPasskey = $passkey;
            $blbRawImage = $rawImage;
            $intIndex = $image_index;
            $objProduct = null;

            if(!$this->check_passkey($strPasskey))
                return self::FAIL_AUTH;

            if (!$blbRawImage) {
                QApplication::Log(E_ERROR, 'uploader',
                    'Did not receive image data for ' . $intRowid, __FUNCTION__);
                return self::UNKNOWN_ERROR;
            }

            if (is_null($objProduct))
                $objProduct = Product::Load($intRowid);

            if (!$objProduct) {
                QApplication::Log(E_ERROR, 'uploader',
                    'Product Id does not exist ' . $intRowid, __FUNCTION__);
                return self::UNKNOWN_ERROR;
            }

            $blbImage = imagecreatefromstring($blbRawImage);
            $objImage = new Images();
            $objImage->Width = imagesx($blbImage);
            $objImage->Height = imagesy($blbImage);
            $objImage->Created = new QDateTime(QDateTime::Now);
            $objImage->SaveImageData(
                Images::GetImageName($intRowid, 0, 0, $intIndex, 'add'),
                $blbRawImage
            );
            $objImage->Save(true);

            $objImage->Parent = $objImage->Rowid;
            $objImage->Save();

            try {
                $objProduct->AssociateImagesAsImage($objImage);
            }
            catch (Exception $objExc) {
                QApplication::Log(E_ERROR, 'uploader',
                    'Could not associate image for ' . $objProduct->Code . ' : ' .
                    $objExc, __FUNCTION__);
                return self::UNKNOWN_ERROR;
            }

            return self::OK;
        }

        /**
         * Remove product
         *
         * @param string $passkey
         * @param string $intRowid
         * @return string
         */
        public function remove_product($passkey , $intRowid){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;


            $product = Product::Load($intRowid);
            
            if(!$product){
                _xls_log("SOAP ERROR : Product id does not exist $intRowid .");
                return self::UNKNOWN_ERROR;
            }
                
            try{
                $this->remove_product_images($passkey , $intRowid);
                $this->remove_product_qty_pricing($passkey , $intRowid);
                $this->remove_related_products($passkey , $intRowid);
                
                $gifts = GiftRegistryItems::LoadArrayByProductId($intRowid);
                
                foreach($gifts as $gift)
                    $gift->Delete();
                    
                    
                $citems = CartItem::LoadArrayByProductId($intRowid);
                
                foreach($citems as $item){
                    if($item->Cart  &&  in_array($item->Cart->Type , array(CartType::cart , CartType::giftregistry , CartType::quote , CartType::saved)) )
                        $item->Delete();
                }
                
                
                
                $product->Delete();
            }catch(Exception $e){
                _xls_log("SOAP ERROR : Error deleting Product $strCode ." . $e);
                return self::UNKNOWN_ERROR;
            }
            
            
            return self::OK;
        }       
        
        
        
        /**
         * Removes additional product images for a product
         *
         * @param string $passkey
         * @param string $intRowid
         * @return string
         */
        public function remove_product_images($passkey , $intRowid){
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $objProduct = Product::Load($intRowid); 
            if (!$objProduct) {
                QApplication::Log(E_WARNING, 'uploader', 
                    'Product id does not exist ' . $intRowid, __FUNCTION__);
                return self::UNKNOWN_ERROR;
            }
                
            try {
                $objProduct->DeleteImages();
            }
            catch(Exception $e) {
                QApplication::Log(E_ERROR, 'uploader', 
                    'Error deleting product images for ' . $intRowid . 
                    ' with : ' . $e, __FUNCTION__);
                return self::UNKNOWN_ERROR;
            }
            
            $objProduct->ImageId = null;
            $objProduct->Save();
            
            return self::OK;
        }
        
        
        
        
        
        /**
         * Add a related product
         *
         * @param string $passkey
         * @param int $intProductId
         * @param int $intRelatedId
         * @param int $intAutoadd
         * @param float $fltQty
         * @return string
         */
        public function add_related_product(
                $passkey
            ,   $intProductId
            ,   $intRelatedId
            ,   $intAutoadd
            ,   $fltQty
        ){


            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

                
            $related = ProductRelated::LoadByProductIdRelatedId($intProductId , $intRelatedId);
            
            $new = false;
            
            if(!$related){
                $related = new ProductRelated();
                $new = true;
            }
            
            
            $related->ProductId = $intProductId;
            $related->RelatedId = $intRelatedId;
            $related->Autoadd = $intAutoadd;
            $related->Qty = $fltQty;
                                
            try{
                $related->Save($new);
            }catch(Exception $e){
                _xls_log("SOAP ERROR : Error adding related product ($intProductId , $intRelatedId) " . $e);
                return self::UNKNOWN_ERROR;
            }
            return self::OK;
                    
        }
        
        
        
        
        
        /**
         * Removes the given related product combination
         *
         * @param string $passkey
         * @param int $intProductId
         * @param int $intRelatedId
         * @return string
         */
        public function remove_related_product(
                $passkey
            ,   $intProductId
            ,   $intRelatedId
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

                
            $related = ProductRelated::LoadByProductIdRelatedId($intProductId , $intRelatedId);
            
            
            if($related){
                try{
                    $related->Delete();
                }catch(Exception $e){
                    _xls_log("SOAP ERROR : Error deleting related product ($intProductId , $intRelatedId) " . $e);
                }
            }

            return self::OK;
            
        }
        
        
        
        /**
         * Removes all related products
         *
         * @param string $passkey
         * @param int $intProductId
         * @return string
         */
        public function remove_related_products(
                $passkey
            ,   $intProductId
        ){

            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

                
            $relations = ProductRelated::LoadArrayByProductId($intProductId);
            
            
            if($relations){
                foreach($relations as $related){
                    try{
                        $related->Delete();
                    }catch(Exception $e){
                        _xls_log("SOAP ERROR : Error deleting related product ($intProductId) " . $e);
                    }
                }
            }

            return self::OK;
            
        }
                
        
        
        
        
    /**
         * Add a qty-based product pricing
         *
         * @param string $passkey
         * @param int $intProductId
         * @param int $intPricingLevel
         * @param float $fltQty
         * @param double $fltPrice
         * @return string
         */
        public function add_product_qty_pricing(
                $passkey
            ,   $intProductId
            ,   $intPricingLevel
            ,   $fltQty
            ,   $fltPrice
        ){

            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;


            $qtyP = new ProductQtyPricing();
            
            
            $qtyP->ProductId = $intProductId;
            $qtyP->PricingLevel = $intPricingLevel;
            $qtyP->Qty = $fltQty;
            $qtyP->Price = $fltPrice;
                    
            try{
                $qtyP->Save(false);
            }catch(Exception $e){
                _xls_log("SOAP ERROR : Error adding qty based pricing ($intProductId ) " . $e);
                return self::UNKNOWN_ERROR;
            }
            return self::OK;
                    
        }       
        
        
        
        /**
         * Removes the given related product combination
         *
         * @param string $passkey
         * @param int $intProductId
         * @return string
         */
        public function remove_product_qty_pricing(
                $passkey
            ,   $intProductId
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $qtyPs = ProductQtyPricing::LoadArrayByProductId($intProductId);
            
            
            foreach($qtyPs as $qtyP){
                try{
                    $qtyP->Delete();
                }catch(Exception $e){
                    _xls_log("SOAP ERROR : Error deleting related product ($intProductId , $intRelatedId) " . $e);
                }
            }

            return self::OK;
            
        }       
        
        
        
        /**
         * Save the header image
         *
         * @param string $passkey
         * @param string $blbImage
         * @return string
         */
        public function save_header_image($passkey
            ,   $blbImage){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            
            $conf = Configuration::LoadByKey('HEADER_IMAGE');
            
            if(!$conf){
                _xls_log("SOAP error : HEADER_IMAGE key not found for configuration.");
                return self::UNKNOWN_ERROR;
            }
            
            
            
            $blbImage = trim($blbImage);
            
            if($blbImage  &&  ($blbImage = base64_decode($blbImage))){
                
                $filename = "photos/header.jpg";
                
                if(!file_put_contents($filename , $blbImage)){
                    _xls_log("SOAP ERROR : Unable to save header image in $filename.");
                    return self::UNKNOWN_ERROR;
                }
                    
                $conf->Value = "/" . $filename;
                
                try{
                    $conf->Save(false , true);
                    return self::OK;
                }catch(Exception $c){
                    _xls_log("SOAP ERROR : Unable to save header image record.");
                    return self::UNKNOWN_ERROR;
                }
            }
            
            
                
            return self::UNKNOWN_ERROR;
            
        }
        
        
        
        
        
        
        
        /**
         * Associate a product to a category
         *
         * @param string $passkey
         * @param int $intRowid
         * @param string $strCategoryPath
         * @return string
         */
        public function save_product_categ_assn(
                  $passkey 
                , $intRowid
                , $strCategoryPath
                ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            if(!($product = Product::Load($intRowid))){
                _xls_log("SOAP Error : Unknown product ID $intRowid for save_product_categ_assn");
//              return self::UNKNOWN_ERROR;
            }
            
            
            // Save category
            $strCategoryPath = trim($strCategoryPath);

            
            //_xls_log("Saving category path for $strCategoryPath in product $strCode");
            if($strCategoryPath){

                
                $categs = explode("\t", $strCategoryPath);
                    
                // find the category to put in
                $categid = 0;

                
                foreach($categs as $categ){
                    $category = $this->categ_get_id($categ , $categid);
                    $categid = $category->Rowid;
                    //_xls_log("Found $categid for $categ in product $strCode");
                    
                }

                // hard coding the delete
                $sql = "DELETE from `xlsws_product_category_assn` WHERE `product_id` = '$intRowid'";
                _dbx($sql);
                
                // hard coding the insert
                $sql = '
                REPLACE INTO `xlsws_product_category_assn` (
                    `product_id`,
                    `category_id`
                ) VALUES (
                    ' . ($intRowid) . ',
                    ' . ($category->Rowid) . '
                )
                ';
                _dbx($sql);
                
                $category->UpdateChildCount();
                    
            }
            
            
            
            return self::OK;
            
        }
        
        
        
        /**
         * Delete a category with given id
         *
         * @param string $passkey
         * @param int $intRowId
         * @return string
         */
        public function delete_category($passkey , $intRowId ){
            
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                
            if($category = Category::Load($intRowId))
                $category->Delete();
                
            return self::OK;
        
        }
        
        
                
        
        
        
        /**
         * Save/Add a category with ID.
         * Rowid and ParentId are RowID of the current category and parentIDs
         * Category is the category name
         * blbImage is base64encoded jpeg
         * meta keywords and descriptions are for meta tags displayed for SEO improvement
         * Custom page is a page-key defined in Custom Pages in admin panel
         * Position defines the sorting position of category. Lower number comes first
         *
         * @param string $passkey
         * @param int $intRowId
         * @param int $intParentId
         * @param string $strCategory
         * @param string $strMetaKeywords
         * @param string $strMetaDescription
         * @param string $strCustomPage
         * @param int $intPosition
         * @param string $blbImage
         * @return string
         */
        public function save_category_with_id(
            $passkey, 
            $intRowId, 
            $intParentId,
            $strCategory,
            $strMetaKeywords,
            $strMetaDescription,
            $strCustomPage,
            $intPosition,
            $blbImage
        ) {

            if (!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            // Prepare values
            $strCategory = trim($strCategory);
            $strCustomPage = trim($strCustomPage);

            if (!$strCategory) {
                QApplication::Log(E_USER_ERROR, 'uploader', 
                    'Could not save empty category');
                return self::UNKNOWN_ERROR;
            }

            $objCategory = false; 

            // If provided a rowid, attempt to load it
            if ($intRowId)
                $objCategory = Category::Load($intRowId);
            else if (!$objCategory and $intParentId)
                $objCategory = 
                    Category::LoadByNameParent($strCategory, $intParentId);

            // Failing that, create a new Category
            if (!$objCategory) { 
                $objCategory = new Category();
                $objCategory->Created = new QDateTime(QDateTime::Now);
            }

            $objCategory->Name = $strCategory;
            $objCategory->Parent = $intParentId;
            $objCategory->Position = $intPosition;
            if ($strCustomPage)
                $objCategory->CustomPage = $strCustomPage;
            if ($strMetaKeywords)
                $objCategory->MetaKeywords = $strMetaKeywords;
            if ($strMetaDescription)
                $objCategory->MetaDescription = $strMetaDescription;

            $blbImage = trim($blbImage);
            if ($blbImage && ($blbImage = base64_decode($blbImage))) {
                $im = imagecreatefromstring($blbImage);

                if ($objCategory->ImageId){ // There is a image already
                    $image = Images::LoadByRowid($category->ImageId);
                    $image->SetImage($blbImage , $intRowId . "_categ");
                    $image->Width = imagesx ( $im );
                    $image->Height = imagesy ( $im );
                    $image->Save();
                }
                else {
                    $image = new Images();
                    $image->ImageData = $blbImage;
                    $image->Width = imagesx ( $im );
                    $image->Height = imagesy ( $im );
                    $image->Created = new  QDateTime(QDateTime::Now);
                    
                    $image->Save(true);
                    $objCategory->ImageId = $image->Rowid;
                }
                
                $image->Parent = $image->Rowid;
                $image->Save();
                
                // Free memory
                unset($image);
                unset($im);
            }

            if ($intRowId && $objCategory->Rowid != $intRowId) { 
                $objCategory->Save(true);
                self::changeRowId($objCategory , QQN::Category() , $intRowId);
                $objCategory = Category::Load($intRowId);
            }

            $objCategory->UpdateChildCount();
            
            return self::OK;
        }       
        
        
        
        
        
        
        
        
        
        
        
        /**
         * Save/Add a category.
         * CategoryPath to contain category names seperated by path as they are supposed to be traversed.
         * blbImage is base64encoded jpeg
         * meta keywords and descriptions are for meta tags displayed for SEO improvement
         * Custom page is a page-key defined in Custom Pages in admin panel
         * Position defines the sorting position of category. Lower number comes first
         *
         * @param string $passkey
         * @param string $strCategoryPath
         * @param string $strMetaKeywords
         * @param string $strMetaDescription
         * @param string $strCustomPage
         * @param int $intPosition
         * @param string $blbImage
         * @return string
         */
        public function save_category(
                $passkey
            ,   $strCategoryPath
            ,   $strMetaKeywords
            ,   $strMetaDescription
            ,   $strCustomPage
            ,   $intPosition
            ,   $blbImage
                ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

                    
            // Save category
            $strCategoryPath = trim($strCategoryPath);

            
            //_xls_log("Saving category path for $strCategoryPath in product $strCode");
            if($strCategoryPath){

                $categs = explode("\t", $strCategoryPath);
                    
                $categid = 0;
                foreach($categs as $categ){
                    $category = $this->categ_get_id($categ , $categid , $intPosition);
                    $categid = $category->Rowid;
                }
                    
            }
            
            if(!$category){
                _xls_log("SOAP ERROR : save_category called with empty category path");
                return  self::UNKNOWN_ERROR;
            }
                    
            
            $blbImage = trim($blbImage);
            
            if($blbImage  &&  ($blbImage = base64_decode($blbImage))){
                
                $im = imagecreatefromstring($blbImage);
                
                if($category->ImageId){ // There is a image already
                    
                    $image = Images::LoadByRowid($category->ImageId);
                    
                    $image->SetImage($blbImage , $intRowid . "_categ");
                    $image->Width = imagesx ( $im );
                    $image->Height = imagesy ( $im );
                    $image->Save();
                    
                }else{
                    $image = new Images();
                    $image->ImageData = $blbImage;
                    $image->Width = imagesx ( $im );
                    $image->Height = imagesy ( $im );
                    $image->Created = new  QDateTime(QDateTime::Now);
                    
                    $image->Save(true);
                    $category->ImageId = $image->Rowid;
                }

                
                $image->Parent = $image->Rowid;
                $image->Save();
                                
                // Free memory
                unset($image);
                unset($im);
            }                   
                    
            $category->CustomPage = trim($strCustomPage);
            $category->MetaKeywords = $strMetaKeywords;
            $category->MetaDescription = $strMetaDescription;
            $category->Save();
            $category->UpdateChildCount();
            
            return self::OK;
            

            
        }
        
        
        
        
        
        
        
        /**
         * Adds tax to the system
         *
         * @param string $passkey
         * @param int $intNo
         * @param string $strTax
         * @param float $fltMax
         * @param int $blnCompounded
         * @return string
         */
        public function add_tax(
            $passkey
        ,   $intNo
        ,   $strTax
        ,   $fltMax
        ,   $blnCompounded
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            if($intNo > _xls_tax_count()){
                _xls_log(sprintf("SOAP ERROR : System can only handle %s number of taxes. Specified %s" , _xls_tax_count() , $intNo));
                return self::UNKNOWN_ERROR;
            }
            
            $new = false;
            // Loads tax
            $tax = Tax::Load($intNo);
            
            if(!$tax){
                $tax = new Tax();
                $new = true;
            }
            
            
            $tax->Tax = $strTax;
            $tax->Max = $fltMax;
            $tax->Compounded = $blnCompounded;
            
                
            try{
                $tax->Save($new);
                self::changeRowId($tax , QQN::Tax() , $intNo);
                
            }catch(Exception $e){
                _xls_log("SOAP ERROR : Error adding tax $strTax " . $e);
                return self::UNKNOWN_ERROR;
            }
            
            return self::OK;
            
            
        }
        
        
        /**
         * Deletes a given tax
         *
         * @param string $passkey
         * @param int $intNo
         * @return string
         */
        public function remove_tax(
            $passkey
        ,   $intNo
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $tax = Tax::Load($intNo);

            if($tax){
                try{
                    $tax->Delete();
                }catch (Exception $e){
                    _xls_log("SOAP ERROR : Error Deleting tax $intNo " . $e);
                }
            }
            return self::OK;
            
        }
        
        
        
        
        
        /**
         * Add a tax code into the WS
         *
         * @param string $passkey
         * @param int $intRowid
         * @param string $strCode
         * @param int $intListOrder
         * @param double $fltTax1Rate
         * @param double $fltTax2Rate
         * @param double $fltTax3Rate
         * @param double $fltTax4Rate
         * @param double $fltTax5Rate
         * @return string
         */
        public function add_tax_code(
            $passkey
        ,   $intRowid
        ,   $strCode
        ,   $intListOrder
        ,   $fltTax1Rate
        ,   $fltTax2Rate
        ,   $fltTax3Rate
        ,   $fltTax4Rate
        ,   $fltTax5Rate
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            if ($strCode == "") //ignore blank tax codes
                return self::OK;
            $new = false;
            // Loads tax
            $tax = TaxCode::Load($intRowid);
            
            if(!$tax){
                $tax = new TaxCode();
                $new = true;
            }
            
            
            $tax->Code = $strCode;
            $tax->ListOrder = $intListOrder;
            $tax->Tax1Rate = $fltTax1Rate;
            $tax->Tax2Rate = $fltTax2Rate;
            $tax->Tax3Rate = $fltTax3Rate;
            $tax->Tax4Rate = $fltTax4Rate;
            $tax->Tax5Rate = $fltTax5Rate;
            
            
                
            try{
                $tax->Save($new);
                self::changeRowId($tax , QQN::TaxCode() , $intRowid);
                
            }catch(Exception $e){
                _xls_log("SOAP ERROR : Error adding tax code $strCode " . $e);
                return self::UNKNOWN_ERROR;
            }
            
            return self::OK;
            
        }
        
        
        /**
         * Deletes a given taxcode
         *
         * @param string $passkey
         * @param int $intRowId
         * @return string
         */
        public function remove_tax_code(
            $passkey
        ,   $intRowId
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $tax = TaxCode::Load($intRowId);

            if($tax){
                try{
                    $tax->Delete();
                }catch (Exception $e){
                    _xls_log("SOAP ERROR : Error Deleting tax code $intRowId " . $e);
                }
            }
            return self::OK;
            
        }
        
        
        
                
        
        
        /**
         * Adds tax status
         *
         * @param string $passkey
         * @param int $intRowid
         * @param string $strStatus
         * @param int $blnTax1Exempt
         * @param int $blnTax2Exempt
         * @param int $blnTax3Exempt
         * @param int $blnTax4Exempt
         * @param int $blnTax5Exempt
         * @return string
         */
        function add_tax_status(
            $passkey
        ,   $intRowid
        ,   $strStatus
        ,   $blnTax1Exempt
        ,   $blnTax2Exempt
        ,   $blnTax3Exempt
        ,   $blnTax4Exempt
        ,   $blnTax5Exempt
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            if ($strStatus == "") //ignore blank tax statuses
                return self::OK;
            $new = false;
            // Loads tax
            $tax = TaxStatus::Load($intRowid);
            
            if(!$tax){
                $tax = new TaxStatus();
                $new = true;
            }
            
            
            $tax->Status = $strStatus;
            $tax->Tax1Status = $blnTax1Exempt;
            $tax->Tax2Status = $blnTax2Exempt;
            $tax->Tax3Status = $blnTax3Exempt;
            $tax->Tax4Status = $blnTax4Exempt;
            $tax->Tax5Status = $blnTax5Exempt;
            
            
                
            try{
                $tax->Save($new);
                self::changeRowId($tax , QQN::TaxStatus(), $intRowid);
                
            }catch(Exception $e){
                _xls_log("SOAP ERROR : Error adding tax status $strStatus " . $e);
                return self::UNKNOWN_ERROR;
            }
            
            return self::OK;
            
        }
        
        
        /**
         * Deletes a given taxcode
         *
         * @param string $passkey
         * @param int $intRowId
         * @return string
         */
        public function remove_tax_status(
            $passkey
        ,   $intRowId
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $tax = TaxStatus::Load($intRowId);

            if($tax){
                try{
                    $tax->Delete();
                }catch (Exception $e){
                    _xls_log("SOAP ERROR : Error Deleting tax status $intRowId " . $e);
                }
            }
            return self::OK;
            
        }
        
        
        
        
        
        
        
        
        
        /**
         * Return all the customers in the database created/modified after a specific date or time
         *
         * @param string $passkey
         * @param int $intDttLastModified
         * @return string
         */
        public function get_customers($passkey , $intDttLastModified){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $dttLastModified =  QDateTime::FromTimestamp($intDttLastModified);
                
                
            $customers = Customer::QueryArray(
                            QQ::AndCondition(
                                QQ::GreaterOrEqual(QQN::Customer()->Modified, $dttLastModified)
                            ));
            
            return $this->qobjects_to_string($customers);
            
        }
        
        
        /**
         * Return a customer by given email address
         *
         * @param string $passkey
         * @param string $strEmail
         * @return string
         */
        public function get_customer_by_email($passkey , $strEmail){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;         
            
            $customer = Customer::LoadByEmail($strEmail);
            
            return $this->qobject_to_string($customer);
            
            
        }
        
        
        
        /**
         * Return a customer by given WS id
         *
         * @param string $passkey
         * @param int $intId
         * @return string
         */
        public function get_customer_by_wsid($passkey , $intId){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;         
            
            $customer = Customer::Load($intId);
            
            return $this->qobject_to_string($customer);
            
        }
                
        
        
        
        /**
         * Save a customer for given email address
         *
         * @param string $passkey
         * @param string $stremail
         * @param string $straddress1_1
         * @param string $straddress1_2
         * @param string $straddress2_1
         * @param string $straddress2_2
         * @param string $strcity1
         * @param string $strcity2
         * @param string $strcompany
         * @param string $strcountry1
         * @param string $strcountry2
         * @param string $strcurrency
         * @param string $strfirstname
         * @param string $strgroups
         * @param string $strhomepage
         * @param string $strid_customer
         * @param string $strlanguage
         * @param string $strlastname
         * @param string $strmainname
         * @param string $strmainphone
         * @param string $strmainephonetype
         * @param string $strphone1
         * @param string $strphonetype1
         * @param string $strphone2
         * @param string $strphonetype2
         * @param string $strphone3
         * @param string $strphonetype3
         * @param string $strphone4
         * @param string $strphonetype4
         * @param string $strstate1
         * @param string $strstate2
         * @param string $strtype
         * @param string $strzip1
         * @param string $strzip2
         * @param string $strzip2
         * @param int $intPricingLevel
         * @param int $blnnewsletter_subscribe
         * @param string $strPassword
         * @param int $blnAllowLogin
         * @return string
         */
        public function save_customer($passkey
              , $stremail
              , $straddress1_1
              , $straddress1_2
              , $straddress2_1
              , $straddress2_2
              , $strcity1
              , $strcity2
              , $strcompany
              , $strcountry1
              , $strcountry2
              , $strcurrency
              , $strfirstname
              , $strgroups
              , $strhomepage
              , $strid_customer
              , $strlanguage
              , $strlastname
              , $strmainname
              , $strmainphone
              , $strmainephonetype
              , $strphone1
              , $strphonetype1
              , $strphone2
              , $strphonetype2
              , $strphone3
              , $strphonetype3
              , $strphone4
              , $strphonetype4
              , $strstate1
              , $strstate2
              , $strtype
              , $strzip1
              , $strzip2
              , $intPricingLevel
              , $blnnewsletter_subscribe
              , $strPassword
              , $blnAllowLogin)
        {
                
            
                if(!$this->check_passkey($passkey))
                    return self::FAIL_AUTH;
            
                $newCust = false;
                
                $stremail = strtolower(trim($stremail));
                
                
                $customer = Customer::LoadByEmail($stremail);
                
                if(!$customer){
                    $newCust = true;
                    $customer = new Customer();
                    $customer->Created = new QDateTime(QDateTime::Now);
                    $customer->Email = $stremail;
                }                   
                    
                $customer->Address11 = $straddress1_1;
                $customer->Address12 = $straddress1_2;
                $customer->Address21 = $straddress2_1;
                $customer->Address22 = $straddress2_2;
                $customer->City1 = $strcity1;
                $customer->City2 = $strcity2;
                $customer->Company = $strcompany;
                $customer->Country1 = $strcountry1;
                $customer->Country2 = $strcountry2;
                $customer->Currency = $strcurrency;
                $customer->Firstname = $strfirstname;
                $customer->Groups = $strgroups;
                $customer->Homepage = $strhomepage;
                $customer->IdCustomer = $strid_customer;
                $customer->Language = $strlanguage;
                $customer->Lastname = $strlastname;
                $customer->Mainname = $strmainname;
                $customer->Mainphone = $strmainphone;
                $customer->Mainephonetype = $strmainephonetype;
                $customer->Phone1 = $strphone1;
                $customer->Phonetype1 = $strphonetype1;
                $customer->Phone2 = $strphone2;
                $customer->Phonetype2 = $strphonetype2;
                $customer->Phone3 = $strphone3;
                $customer->Phonetype3 = $strphonetype3;
                $customer->Phone4 = $strphone4;
                $customer->Phonetype4 = $strphonetype4;
                $customer->State1 = $strstate1;
                $customer->State2 = $strstate2;
                $customer->Type = $strtype;
                $customer->Zip1 = $strzip1;
                $customer->Zip2 = $strzip2;
                $customer->PricingLevel = $intPricingLevel;
                $customer->NewsletterSubscribe = $blnnewsletter_subscribe;
                $customer->AllowLogin = $blnAllowLogin;
                
                if(trim($strPassword) == ''  && $newCust){
                    $customer->Password = Customer::random_password(_xls_get_conf('MIN_PASSWORD_LEN' , 5) , 4);
                }elseif(trim($strPassword) != '')
                    $customer->Password = md5($strPassword);
                
                try{
                    $customer->Save($newCust);
                    return self::OK;
                }catch(Exception $e){
                    _xls_log("SOAP ERROR : Failed to update customer $stremail . Error " . $e);
                    return self::UNKNOWN_ERROR;
                }
                
                
              
        }
        
        
        
        
        
        /**
         * Get backup sql data from web
         * 
         * @param string $passkey
         * @param string $table
         * @return string
         */
        public function db_sql_backup($passkey , $table){
            
            
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            // TODO ASHIK
                
            return self::OK;
            
        }       
            
        
        
        
        
        
        
        /**
         * Output array or Object in LS Preferred format
         */
        protected function xls_output($var){
            return json_encode($var);
        }
        /**
         * Input array or Object from LS Preferred format
         */
        protected function xls_input($var){
            return json_decode($var);
        }       
        
        /**
         * Return an array of objects
         *
         * @param mixed $arrObj
         * @return string
         */
        protected function qobjects_to_string($arrObj){
            
            if(!$arrObj)
                return '';
                
            if(!is_array($arrObj) and is_object($arrObj))
                return $this->qobject_to_string($arrObj);
                
            if(!is_array($arrObj))
                return $arrObj;
                
            if(count($arrObj) == 0)
                return '';
                
            $ret = array();
            
            foreach($arrObj as $obj)
                $ret[] = $this->qobject_to_string($obj);
                
            return implode("\n" , $ret);
            
        }
        
        
        
        /**
         * Convert a QCodo object to string
         *
         * @param QBaseClass $obj
         * @return string
         */
        protected function qobject_to_string($obj){
            
            
            $ret = '';
            
            if(!$obj)
                return $ret;
            
            $xml = $obj->GetSoapComplexTypeXml();
            
            $doc = new XLS_XMLDocument();
            $xp = new XLS_XMLParser();
            $xp->setDocument($doc);
            $xp->parse($xml);
            $doc = $xp->getDocument();   
            
            $root = $doc->getRoot();
            
            $seq = $root->getElementByName('sequence');
                        
            $elems = $seq->getElementsByName('element');            
            
            
            foreach($elems as $elem){
                $name = $elem->getAttribute('name');
                if(substr($name , 0 , 2) == '__')
                    continue;
                    
                
                    
                $ret .=  $name . ":"  ;
                
                $content = $obj->$name;
                if($content instanceof QDateTime)
                    $ret .= base64_encode($content->Timestamp) . "\n";
                elseif($content instanceof QBaseClass){
                    //_xls_log($content);
                    $ret .= base64_encode($content->Rowid) . "\n";
                }else
                    $ret .= base64_encode($content) . "\n";
                    
            }

            return $ret;
            
            
        }
        
        
        
        /**
         * Get a category id
         */

        protected function categ_get_id($categname , $parentid = 0 , $intPosition = 0){

            $categname = trim($categname);

            if(empty($categname))
                return $parentid;

            $categs = Category::LoadArrayByName($categname);
            
            $exist = false;

            foreach($categs as $categ){
                
                if($categ->Parent == $parentid ){
                    $exist = $categ;                    
                    break;
                }
            }
            

            if($exist)
                return $exist;

            $categ = new Category();
            $categ->Name = $categname;
            $categ->Parent = $parentid;
            $categ->Created = new QDateTime(QDateTime::Now);
            $categ->Position = $intPosition;
            $categ->Save(true);
            $categ->UpdateChildCount();
            
            return $categ;
        }
        
        
        
        /**
         * Adds a quote
         *
         * @param string $passkey
         * @param string $strId
         * @param int $intCreationDate
         * @param string $strPrintedNotes
         * @param string $strZipcode
         * @param string $strEmail
         * @param string $strPhone
         * @param string $strUser
         * @param int $intTaxCode
         * @return string
         */
        public function add_quote($passkey 
                , $strId 
                , $intCreationDate
                , $strPrintedNotes 
                , $strZipcode
                , $strEmail
                , $strPhone
                , $strUser
                , $intTaxCode
                ){
            
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            // Delete the quote if it exists already
            $q = Cart::LoadByIdStr($strId);
            $linkid = $q->Linkid;
                
            if($q)
                $q->FullDelete();
                
            $cart = Cart::GetCart();
            
            $cart->IdStr = $strId;
            $cart->PrintedNotes = $strPrintedNotes;
            $cart->Zipcode = $strZipcode;
            $cart->Email = $strEmail;
            $cart->Phone = _xls_number_only($strPhone);
            $cart->User = $strUser;
            $cart->Linkid = $linkid;
                        
            $cart->DatetimeCre = QDateTime::FromTimestamp($intCreationDate);
            
            $cart->DatetimeDue = QDateTime::FromTimestamp($intCreationDate);
            $cart->DatetimeDue->AddDays(intval(_xls_get_conf('QUOTE_EXPIRY' , 30)));
            
            $cart->Type = CartType::quote;
            $cart->FkTaxCodeId = $intTaxCode;
            $cart->Save();

            
            
            return self::OK;
            
            
        }
        
        
        
        
        
        
        /**
         * Add a quote item
         *
         * @param string $passkey
         * @param string $strId
         * @param int $intProductId
         * @param float $fltQty
         * @param string $strDescription
         * @param double $fltSell
         * @param double $fltDiscount
         * @return string
         */
        public function add_quote_item($passkey 
                , $strId 
                , $intProductId 
                , $fltQty
                , $strDescription
                , $fltSell
                , $fltDiscount
                ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $cart = Cart::LoadByIdStr($strId);
            
            if(!$cart)
                return self::UNKNOWN_ERROR;
                
            $product = Product::Load($intProductId);
            if(!$product) { 
                _xls_log("SOAP ERROR : Product not found for Adding to Cart -> $intProductId ");
                return self::UNKNOWN_ERROR;
            }

            $cart->AddSoapProduct($product,
                $fltQty, $strDescription,
                $fltSell, $fltDiscount, CartType::quote);

            return self::OK;
        }
        
        

        /**
         * Get the quote link
         *
         * @param string $passkey
         * @param string $strId
         * @return string
         */
        public function get_quote_link($passkey 
                , $strId
                ){
                    
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;     

            $cart = Cart::LoadByIdStr($strId);
            
            if(!$cart)
                return self::UNKNOWN_ERROR;
                

            return self::OK . " " . $cart->Order;
                
        }
        
        
        
        

        /**
         * Deletes a quote given by ID 
         *
         * @param string $passkey
         * @param string $strId
         * @return string
         */
        public function delete_quote($passkey , $strId ){
            
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $carts = Cart::QueryArray(
                            QQ::AndCondition(
                                QQ::Equal(QQN::Cart()->IdStr, $strId)
                            ));
            
            foreach($carts as $cart){
                
                //load the lines
                $items = $cart->GetCartItemArray();
                foreach($items as $item)
                    $item->Delete();
                    
                $cart->Delete();
                
            }
            
            
            return self::OK;
            
        }   
        
                
        
        
        
        
        
    
        /**
         * Add a SRO
         *
         * @param string $passkey
         * @param string $strId
         * @param string $strCustomerName
         * @param string $strCustomerEmailPhone
         * @param string $strZipcode
         * @param string $strProblemDescription
         * @param string $strPrintedNotes
         * @param string $strWorkPerformed
         * @param string $strAdditionalItems
         * @param string $strWarranty
         * @param string $strWarrantyInfo
         * @param string $strStatus
         * @return string
         */
        public function add_sro($passkey 
                , $strId 
                , $strCustomerName
                , $strCustomerEmailPhone
                , $strZipcode
                , $strProblemDescription
                , $strPrintedNotes 
                , $strWorkPerformed
                , $strAdditionalItems
                , $strWarranty
                , $strWarrantyInfo 
                , $strStatus
        ){
            
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
                
            $sro = Sro::LoadByLsId($strId);
            
            if($sro){
                // Delete the SRO for a fresh insert..
                // delete the lines
                $this->delete_sro($passkey , $strId);
            }
            
            
            
            $sro = new Sro();
            $sro->DatetimeCre = new QDateTime(QDateTime::Now);
            
            $sro->LsId = $strId;
            $sro->CustomerName = $strCustomerName;
            $sro->CustomerEmailPhone = $strCustomerEmailPhone;
            $sro->Zipcode = $strZipcode;
            $sro->ProblemDescription = $strProblemDescription;
            $sro->PrintedNotes = $strPrintedNotes;
            $sro->WorkPerformed = $strWorkPerformed;
            $sro->AdditionalItems = $strAdditionalItems;
            $sro->Warranty = $strWarranty;
            $sro->WarrantyInfo = $strWarrantyInfo;
            $sro->Status = $strStatus;
            
            $sro->Save();
            
            return self::OK;
            
        }
        
        
        
        
        
        /**
         * Add a SRO item
         *
         * @param string $passkey
         * @param string $strId
         * @param int $intProductId
         * @param float $fltQty
         * @param string $strDescription
         * @param double $fltSell
         * @param double $fltDiscount
         * @return string
         */
        public function add_sro_item($passkey 
                , $strId 
                , $intProductId 
                , $fltQty
                , $strDescription
                , $fltSell
                , $fltDiscount
                ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $sro = Sro::LoadByLsId($strId);
                
            if(!$sro)
                return self::UNKNOWN_ERROR;
            
            $cart = Cart::LoadByRowid($sro->CartId);
            
            if(!$cart)
                $cart = Cart::GetCart();
            $cart->Type = CartType::sro;
                
            $sro->CartId = $cart->Rowid;
            $sro->Save();

            $product = Product::Load($intProductId);

            if(!$product){
                _xls_log("SOAP ERROR : Product ($intProductId)  not found while being added to sro $strId. Using another product");
                //return self::UNKNOWN_ERROR;
                $products = Product::LoadAll(QQ::Clause(QQ::LimitInfo(1)));
                $product = current($products); 
            }

            $cart->AddSoapProduct($product,
                $fltQty, $strDescription,
                $fltSell, $fltDiscount, CartType::sro);

            return self::OK;                
        }


        
        
        
        
        /**
         * Add SRO Repair Item
         *
         * @param string $passkey
         * @param string $strId
         * @param string $strFamily
         * @param string $strDescription
         * @param string $strPurchaseDate
         * @param string $strSerialNumber
         * @return string
         */
        public function add_sro_repair($passkey 
                , $strId 
                , $strFamily
                , $strDescription
                , $strPurchaseDate
                , $strSerialNumber
                ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $sro = Sro::LoadByLsId($strId);
                
            if(!$sro)
                return self::UNKNOWN_ERROR;
            
            // ignore if all are empty
            if((trim($strFamily) == '')   &&  (trim($strDescription) == ''))
                return self::OK;
            
            $repair = new SroRepair();
            
            
            $repair->SroId = $sro->Rowid;
            $repair->Family = $strFamily;
            $repair->Description = $strDescription;
            $dtPurchaseDate = ($strPurchaseDate);
            $repair->PurchaseDate = $dtPurchaseDate;
            $repair->SerialNumber = $strSerialNumber;
            $repair->DatetimeCre = new QDateTime(QDateTime::Now);
            
            $repair->Save(true);
            
            $sro->AssociateSroRepair($repair);
            
            return self::OK;
                
        }       
        
        
        

        
        /**
         * Deletes a sro given by ID 
         *
         * @param string $passkey
         * @param string $strId
         * @return string
         */
        public function delete_sro($passkey , $strId ){
            
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $sro = Sro::LoadByLsId($strId);
            
            if($sro){
                
                //load the lines
                $repairs = $sro->GetSroRepairArray();
                foreach($repairs as $repair)
                    $repair->Delete();
                    
                $cart = Cart::LoadByRowid($sro->CartId);
                
                if($cart){
                    
                    $items = $cart->GetCartItemArray();
                    
                    foreach($items as $item)
                        $item->Delete();
                    
                    $cart->Delete();
                }
                
                $sro->Delete();
                
            }
            
            
            return self::OK;
            
        }   
                
        
        
        
        /**
         * Update all webstore orders before a timestamp ***DEPRECIATED - DO NOT USE, USED ONLY AS A WRAPPER FOR LIGHTSPEED DOWNLOAD REQUESTS, DO NOT DELETE****
         *
         * @param string $passkey
         * @param int $intDttSubmitted
         * @param int $intDownloaded
         * @return string
         */
        public function update_order_downloaded_status_by_ts($passkey , $intDttSubmitted , $intDownloaded){
            return self::OK;
            
        }
                        
        
        
        
        /**
         * Update an individual order as downlaoded
         * @param string $passkey
         * @param string $strId
         * @param string $intDownloaded
         * @return string
         */
        public function update_order_downloaded_status_by_id(   $passkey 
                , $strId 
                , $intDownloaded
        ){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;


            $cart = Cart::Load($strId);
            
            if(!$cart){
                $cart = Cart::LoadByIdStr($strId);
                if(!$cart){
                    _xls_log("SOAP ERROR : update_order_downloaded_status_by_id did not find order $strId");
                    return self::UNKNOWN_ERROR;
                }
            }

            $cart->Downloaded = $intDownloaded;

            try{
                $cart->Save();
            }catch(Exception $e){

                _xls_log("SOAP ERROR : update_order_downloaded_status_by_id " . $e);
                return self::UNKNOWN_ERROR;
            }
            
            return self::OK;
            
        }
        
        
        
        
        
        
        /**
         * Add an order for display
         *
         * @param string $passkey
         * @param string $strId
         * @param int $intDttDate
         * @param int $intDttDue
         * @param string $strPrintedNotes
         * @param string $strStatus
         * @param string $strEmail
         * @param string $strPhone
         * @param string $strZipcode
         * @param int $intTaxcode
         * @param float $fltShippingSell
         * @param float $fltShippingCost
         * @return string
         */
        public function add_order($passkey 
                , $strId 
                , $intDttDate
                , $intDttDue
                , $strPrintedNotes 
                , $strStatus 
                , $strEmail
                , $strPhone
                , $strZipcode
                , $intTaxcode
                , $fltShippingSell
                , $fltShippingCost
                ){
            
                    
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;


            $cart = Cart::LoadByIdStr($strId);
                
            if(!$cart){
                $cart = Cart::GetCart();
            }else{          // if cart already exists then delete the items
                $items = $cart->GetCartItemArray();
                foreach($items as $item)
                    $item->Delete();
            }


            $cart->Type = CartType::order;
            
            $cart->IdStr = $strId;
            $cart->PrintedNotes = $strPrintedNotes;
            $cart->DatetimePosted = QDateTime::FromTimestamp($intDttDate);
            $cart->DatetimeDue = QDateTime::FromTimestamp($intDttDue);
            $cart->FkTaxCodeId = $intTaxcode?$intTaxcode:0;
                        
            $cart->Status = $strStatus;
            $cart->Email = $strEmail;
            $cart->Phone = _xls_number_only($strPhone);
            $cart->ShippingSell = $fltShippingSell;
            $cart->ShippingCost = $fltShippingCost;
            
            $cart->Status = $strStatus;
            $cart->Zipcode = $strZipcode;
            try{
                $cart->Save();
            }catch(Exception $e){

                _xls_log("SOAP ERROR : add_order " . $e);
                return self::UNKNOWN_ERROR;
            }
            return self::OK;
        }
        
        
        
        /**
         * Deletes a order given by ID 
         *
         * @param string $passkey
         * @param string $strId
         * @return string
         */
        public function delete_order($passkey , $strId ){
            
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
            
            $carts = Cart::QueryArray(
                            QQ::AndCondition(
                                QQ::Equal(QQN::Cart()->IdStr, $strId)
                            ));
            
            foreach($carts as $cart){
                
                $cart->FullDelete();
            }
            
            
            return self::OK;
            
        }   
        
        
        
        
        
        /**
         * Add an order item
         *
         * @param string $passkey
         * @param string $strOrder
         * @param int $intProductId
         * @param float $fltQty
         * @param string $strDescription
         * @param float $fltSell
         * @param float $fltDiscount
         * @return string
         */
        public function add_order_item($passkey 
                , $strOrder 
                , $intProductId 
                , $fltQty
                , $strDescription
                , $fltSell
                , $fltDiscount
                ){

            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $objCart = Cart::LoadByIdStr($strOrder);
            if(!$objCart)
                return self::UNKNOWN_ERROR;

            $product = Product::Load($intProductId);
            if(!$product)
                return self::UNKNOWN_ERROR;

            $objCart->AddSoapProduct($product,
                $fltQty, $strDescription,
                $fltSell, $fltDiscount, CartType::order);

            return self::OK;
        }
        
        /**
         * Add a family
         *
         * @param string $passkey
         * @param string $strFamily
         * @return string
         */
        public function add_family(
            $passkey
        ,   $strFamily  
        ){
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            if(trim($strFamily) == '') //ignore blank tax codes
                return self::OK;
                
            $new = false;
            $family = Family::LoadByFamily($strFamily);
            
            if(!$family){
                $family = new Family();
                $new = true;
            }
            
            $family->Family = $strFamily;
            
            try{
                $family->Save($new);
            
                
            }catch(Exception $e){

                _xls_log("SOAP ERROR : add family $strFamily " . $e);
                return self::UNKNOWN_ERROR;
            }
            return self::OK;
            
        }
        
        
        /**
         * Remove a family
         *
         * @param string $passkey
         * @param string $strFamily
         * @return string
         */
         public function remove_family(
            $passkey
        ,   $strFamily  
        ){
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $family = Family::LoadByFamily($strFamily);
            
            if($family){
            try{
                $family->Delete();
            
                
            }catch(Exception $e){

                _xls_log("SOAP ERROR : delete family $strFamily " . $e);
                return self::UNKNOWN_ERROR;
            }
            }
            return self::OK;
        }
        
        

        
        
        /**
         * Get new orders
         *
         * @param string $passkey
         * @return string
         */
        public function get_new_web_orders($passkey){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

                
            $carts = Cart::QueryArray(
                            QQ::AndCondition(
                                QQ::Equal(QQN::Cart()->Type , CartType::order)
                            ,   QQ::Equal(QQN::Cart()->Downloaded, 0)
                            )); 
                                    
            return $this->qobjects_to_string($carts);
            
        }       
        
        
        
        
        /**
         * Get web orders since given date and time
         *
         * @param string $passkey
         * @param int $intDttSubmitted
         * @return string
         */
        public function get_web_orders($passkey , $intDttSubmitted){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

            $dttSubmitted = QDateTime::FromTimestamp($intDttSubmitted);
                
                
            $carts = Cart::QueryArray(
                            QQ::AndCondition(
                                QQ::Equal(QQN::Cart()->Type , CartType::order)
                            ,   QQ::GreaterOrEqual(QQN::Cart()->Submitted, $dttSubmitted)
                            ));
            
            return $this->qobjects_to_string($carts);
            
        }
                
        
        /**
         * Get a weborder by given Webstore's internal Rowid
         *
         * @param string $passkey
         * @param int $intId
         * @return string
         */
        function get_web_order_by_wsid($passkey , $intId){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;         
            
            $cart = Cart::Load($intId);
            
            return $this->qobject_to_string($cart);         
            
            
        }
        
        
        
        
        
        /**
         * Return items available for a cart.
         *
         * @param string $passkey
         * @param int $intId
         * @return string
         */
        public function get_web_order_items($passkey , $intId){
            
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;

                
            $cart = Cart::Load($intId);
            $items = CartItem::LoadArrayByCartId( $intId );
            $field = "SellBase";
            if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
            	$field = "Sell";
            foreach ($items as $item)
            {
                if ($item->SellDiscount > 0)
                    $item->$field = $item->SellDiscount;
            }
            
            return $this->qobjects_to_string($items);
        }
        
        
        
        
        /**
         * Flush categories (But not the associations to products!)
         * @param string $passkey
         * @return string
         */
        public function flush_category($passkey){
            $obj = new Category();
            
            if (_xls_get_conf('CACHE_CATEGORY','0') == '0'){
            try{
                $obj->Truncate();
            }catch(Exception $e){
                _xls_log("SOAP ERROR : In flushing  Category from flush_category : " . $e);
                return self::UNKNOWN_ERROR;
            }
            }
            
            return self::OK;
        }
        
        
        
        /**
         * Flushes a DB Table
         *
         * @param string $passkey
         * @param string $strObj
         * @return string
         */
        public function db_flush(
            $passkey
            , $strObj
        ){
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                
            if(!class_exists($strObj)){
                _xls_log("SOAP ERROR : There is no object type of $strObj" );
                return self::NOT_FOUND;
            }
            
            if(in_array($strObj , array('Cart' , 'Configuration' , 'ConfigurationType' , 'CartType' , 'ViewLogType'))){
                _xls_log("SOAP ERROR : Objects of type $strObj are not allowed for flushing" );
                return self::UNKNOWN_ERROR;
            }
            
            /**
            LightSpeed will send commands to flush the following tables
            Product
			Category
			Tax
			TaxCode
			TaxStatus
			Family
			ProductRelated
			ProductQtyPricing
			Images
			*/
            $obj = new $strObj();
            
            try{
                _xls_log("SOAP FLUSH : $strObj ");
                
                //For certain tables, we flush related data as well
                switch ($strObj)
                {
                	case "Product":
                   		_dbx("TRUNCATE `xlsws_product_image_assn`");
                    	_dbx("TRUNCATE `xlsws_product_category_assn`");
						break;
                
                	case "Category":
                    	_dbx("TRUNCATE `xlsws_product_category_assn`");
                    	break;
                    	             
                	case "Images":
                		//If we are using file system storage, delete the images from /photos. Otherwise, it's all in the database.
                		if(_xls_get_conf('IMAGE_STORE' , 'FS') == 'FS'){
                			$strQuery = "SELECT image_path FROM xlsws_images WHERE image_path IS NOT NULL";
							$objQuery = _dbx($strQuery, 'Query');
					       	while ($image = $objQuery->FetchArray())
					       			@unlink(Images::GetImagePath($image['image_path']));
                		}
                		_dbx("DELETE FROM `xlsws_product_image_assn`");
                		break;             		
                		
               	}
               	//Then truncate the table
                $obj->Truncate();
                
            }catch(Exception $e){
                _xls_log("SOAP ERROR : In flushing  $strObj " . $e);
                return self::UNKNOWN_ERROR;
            }
            
            return self::OK;
            
            
        }
        
        
        
        
        
        
        /**
         * Document Flush
         *
         * @param string $passkey
         * @return string
         */
        public function document_flush(
            $passkey
        ){
            if(!$this->check_passkey($passkey))
                return self::FAIL_AUTH;
                

            try{
                _dbx("TRUNCATE `xlsws_sro`");
                _dbx("TRUNCATE `xlsws_sro_repair`");

                $carts = Cart::LoadArrayByType(CartType::quote);
                foreach($carts as $cart)
                    $cart->FullDelete();
                    
                $carts = Cart::LoadArrayByType(CartType::sro);
                foreach($carts as $cart)
                    $cart->FullDelete();
                
                
                $carts = Cart::LoadArrayByType(CartType::invoice);
                foreach($carts as $cart)
                    $cart->FullDelete();


                // orders that have not been submitted, delete them
                $carts = Cart::QueryArray(
                            QQ::AndCondition(
                                QQ::Equal(QQN::Cart()->Type, CartType::order)
                            ,   QQ::IsNull(QQN::Cart()->Submitted)
                            ));
                foreach($carts as $cart)
                    $cart->FullDelete();

                
            }catch(Exception $e){
                _xls_log("SOAP ERROR : In flushing  $strObj " . $e);
                return self::UNKNOWN_ERROR;
            }
            
            return self::OK;
            
            
        }       
        
        
        
        
        
        
        
        
    private static function changeRowId($obj , $qqn , $rowid){

        $st = "UPDATE " . substr($qqn->GetAsManualSqlColumn() , strpos($qqn->GetAsManualSqlColumn() , ".")+1 ) . " SET Rowid = '" . $rowid . "' WHERE Rowid =  '" . $obj->Rowid  . "'";
        //_xls_log($st);
        _dbx( $st  , "NonQuery");       
        
    }
     

    /**
    * The QCodo database object
    * @access public
    * @var string
    */
    private static $qDatabase = null;
    

    
    /**
    * The QCodo database connection ID
    * @access public
    * @var string
    */
    private static $qDatabaseID = 1;
    

    /**
     * Display and log error message
     * @param string $query
     * @param integer $errno
     * @param string $query
     */
    private static function error($query, $errno, $error) 
    { 
        error_log("EBMS DB ERROR : $errono - $error : $query");
    }
    





    /**
     * Run a query. Returns the result resource
     * @param string $query
     * @return resource
     */  
    private static function query($strQuery) 
    {
        self::$qDatabase = QApplication::$Database[self::$qDatabaseID];

        
        
        // Perform the Query
        if(substr(strtolower(trim($strQuery)),0,6) == "select"){
            $result =  self::$qDatabase->Query($strQuery);
            return $result;
        }else
            self::$qDatabase->NonQuery($strQuery);
    }
    


    /**
     * Performs a insert on a table
     * @param string $table database table
     * @param array $data the key/value pair of the field and data to be saved
     * @return resource
     */
    private static function insert($table, $data) 
    {
        
        self::perform($table,$data,'insert');
        return self::insert_id();
        
        
    }
    
    


    /**
     * Performs a insert/update style function on a table
     * @param string $table database table
     * @param array $data the key/value pair of the field and data to be saved
     * @param string $action database action to be perform insert|replace|update|insert delayed
     * @param string $parameters any specific parameter for the db action. Usually the where clause for update. Default blank
     * @return resource
     */
    private static function perform($table, $data, $action, $parameters = '') 
    {
        reset($data);
        if (  ($action == 'insert') ||  ($action == 'insert delayed') || ($action == 'replace')) {
            $query = $action . ' into ' . $table . ' (';
            while (list($columns, ) = each($data)) {
                $query .= '`' . $columns . '` , ';
            }
            $query = substr($query, 0, -2) . ') values (';
            reset($data);
            while (list(, $value) = each($data)) {
                switch ((string)$value) {
                    case 'now()':
                        $query .= 'now(), ';
                        break;
                    case 'null':
                        $query .= 'null, ';
                        break;
                    default:
                        $query .= '\'' . self::prepare_input($value) . '\', ';
                        break;
                }
            }
            $query = substr($query, 0, -2) . ')';
        } elseif ($action == 'update') {
            $query = 'update ' . $table . ' set ';
            while (list($columns, $value) = each($data)) {
                switch ((string)$value) {
                    case 'now()':
                        $query .= '`' . $columns . '` = now(), ';
                        break;
                    case 'null':
                        $query .= '`' . $columns .= '` = null, ';
                        break;
                    default:
                        $query .= '`' .  $columns . '` = \'' . self::prepare_input($value) . '\', ';
                        break;
                }
            }
            $query = substr($query, 0, -2) . ' where ' . $parameters;
        }
        
        self::query($query);
    }
    
    
    /**
     * Performs delete operation
     * @param string $table database table
     * @param string $column for which column you are doing a delete? the where clause matching column
     * @param string $value the where clause matching value
     */
    private static function delete($table_name , $column , $value)
    {
        self::query("DELETE from $table_name WHERE $column = '$value' ");
        return;
    }
    
    
    
    
    /**
     *
     * Return a blank record
     * @param string $table
     *
     */
    private static function blank_record($table)
    {
        $sql = "SHOW COLUMNS FROM $table";
        
        $cols = array();
        
        $ret = self::query($sql);
        if(!$ret) return array();
    
        while($line = self::fetch_array($ret)){
            $cols[$line['Field']] = '';
        }
        
        return $cols;
    }
    
    
    
    /**
     *
     * fetch array
     *
     */
    private static function fetch_array($db_query) 
    {
        return $db_query->FetchArray();
    }
    

    /**
     * Get the database id for the last inserted record.
     * @return string
     */ 
    private static function insert_id() 
    {
        return self::$qDatabase->InsertId();
    }
    
    
    /**
     *
     * reverse out a string
     * @param $string string
     * @return string
     */
    private static function output($string) 
    {
        return stripslashes($string);
    }
    
    
    /**
     * Convert general data into database compatible data
     * @param string $string to prepare for input
     * @return string
     */
    private static function prepare_input($string) 
    {
        if (is_string($string)) {
            return trim(stripslashes($string));
        } elseif (is_array($string)) {
            reset($string);
            while (list($key, $value) = each($string)) {
                $string[$key] = self::prepare_input($value);
            }
            return $string;
        } else {
            return $string;
        }
    }
    
    
    /**
     * Load database records based on specified search conditions - sort as well. Returns a array of the data
     * @param string $table table name
     * @param string $return_columns the columns to be returned
     * @param array $where where clauses that will be anded together
     * @param array $sorts sort statements
     * @param integer $start starting row
     * @param integer $max maximum number of results
     * @return array
     */
    private static function load_records( $table , $return_column ="*" , $where = array() , $sorts = array() , $start = 0 , $max = 0 )
    {
        $query = "SELECT $return_column FROM $table ";
    
        if(is_array($where) && (count($where) > 0))
            $query .= " WHERE " . implode(" and " , $where);
    
    
        if(is_array($sorts) && (count($sorts) > 0)){
            $s = "  ";
            
            foreach($sorts as $col=>$order)
                $s .= $col . " " . (($order == SORT_ASC)?"asc ,":"desc ,");
            
            $query .= " ORDER BY " . substr($s , 0 , -1);
        
        }
        if(($start > 0 ) && ($max > 0))
            $query .= " LIMIT $start , $max";
        elseif($start > 0 )
            $query .= " LIMIT $start ";
        elseif($max > 0 )
            $query .= " LIMIT $max ";
    
        $query_result = self::query($query);
    
        $result = array();
    
        while($temp = self::fetch_array($query_result)){
            $result[] = $temp;
        }
    
        return $result;     
    
    }
    
    
    
    /**
     * Load database record based on the record id key given
     * @param string $table table name
     * @param string $return_columns the columns to be returned
     * @param string $id_column 
     * @param string $id
     * @return array
     */
     private static function load_record( $table , $return_column ="*"  , $id , $id_column = "id")
     {
         
         $records = self::load_record($table , $return_column , array("$id_column = '$id'"));
         
         if(count($records) > 0)
            return current($records);
        
         return false;
         
     }
    
    
    
    
    /**
     * Give a total count of records based on search conditions
     * @param string $table
     * @param array $where search conditions
     * @return integer
     */
     private static function do_count( $table , $where = array())
     {
        $query = "SELECT count(*) c FROM $table ";
    
        if(is_array($where) && (count($where) > 0))
            $query .= " WHERE " . implode(" and " , $where);
        
        $query_result = self::query($query);
        $query_row = self::fetch_array($query_result);
        return $query_row['c'];
    }
    
    
    
    
    
    
    /**
     * Select a specific column and return the another column in key/data pair
     * @param string $table
     * @param string $keycolumn the key column which will be the index in the array.
     * @param string $valuecolumn the value column which will be the data in the array.
     * @param array $where where clauses that will be anded together
     * @param array $sort sort statements
     * @return array
     */
    private static function column_select($table , $keycolumn , $valuecolumn , $where = array() , $sort = array() , $start = 0 , $max = 0)
    {
        $query = "SELECT DISTINCT $keycolumn as keyc , CONCAT($valuecolumn,'') as valc FROM $table ";
        
        if(is_array($where) && (count($where) > 0))
            $query = $query . " WHERE " . implode(' and ' , $where);
        
        if(is_array($sort) && (count($sort) > 0))
            $query = $query . " ORDER BY " . implode(' , ' , $sort);
        else
            $query = $query . " ORDER BY 2 ";
        
        if(($start > 0 ) && ($max > 0))
            $query .= " LIMIT $start , $max";
        elseif($start > 0 )
            $query .= " LIMIT $start ";
        elseif($max > 0 )
            $query .= " LIMIT $max ";
        
        
        $query_result = self::query($query);
        
        $result = array();
        
        while($temp = self::fetch_array($query_result)){
            //print_var($temp);
            $result[$temp['keyc']] = $temp['valc'];
        }
    
    
        return $result;     
    }
    
    

    /**
     * Return how many unique records are there in the table.
     * @param string $table
     * @param string $keycolumn the key column which will be the index in the array.
     * @param string $valuecolumn the value column which will be the data in the array.
     * @param array $where where clauses that will be anded together
     * @param array $sort sort statements
     * @return array
     */
    private static function unique_count($table , $column , $value , $id = '' , $idcolumn = 'SerialNo')
    {
        $query = self::query("SELECT count(*) c FROM $table WHERE $column = '$value' and $idcolumn <> '$id'");
        $query_row = self::fetch_array($query);
        if($query_row['c'] == 0)
            return true;
        else
            return false;
    
    }
    
    
    /**
     * Return the first cell of the first row
     * @param string $table
     * @param string $column the column or expression to be evaluated
     * @param array $where where clauses that will be anded together
     * @param array $sort sort statements
     * @return string
     */
     private static function first_cell($table , $column , $where = array() , $sort = array()) 
     {
        
         $result = self::load_records($table , $column , $where , $sort , 0 , 1 );
         
        return is_array($result)?current(current($result)):false;
    }
    
    
    
    /**
     * Return the first row
     * @param string $table
     * @param array $where where clauses that will be anded together
     * @param array $sort sort statements
     * @return array
     */
     private static function first_row($table , $where = array() , $sort = array()) 
     {
        
        $result = self::load_records($table , "*" , $where , $sort , 0 , 1 );
         
        return (count($result)>0)?current($result):false;
    }
    
    
    
    
    
    /**
     * Return the first columns of a table
     * @param string $table 
     * @param string $column the collumns
     * @param array $where where clauses that will be anded together
     * @param array $sort sort statements
     * @return array
     */
     private static function first_column($table , $column , $where = array() , $sort = array())
     {
    
        $result = self::load_records($table , $column , $where , $sort , 0 , 1 );
    
        $ret = array();
        foreach($result as $row){
            $ret[] = current($row);
        }
    
        return (count($ret)>0)?$ret:false;
    }
    
    
    
    
    
    
    
    /**
     * Return parsed where clause statement for searching in string
     * @param string $column_name the column name
     * @param string $val the value
     * @param boolean $is_string Is this a string?
     * @return string
     */
     private static function search_param($column_name , $val , $is_string = TRUE)
     {
    
        
        if (EBMS_UTIL::not_null($val)) {
            $val = trim($val);
        
            if(substr($val , 0 , 1) == "=")
                return " $column_name = '" . addslashes(substr($val , 1 ))  . "' " ;
            
            if(substr($val , 0 , 2) == "==")
                return " $column_name = '" . addslashes(substr($val , 2 ))  . "' " ;
                
            if(substr($val , 0 , 2) == ">=")
                return " $column_name >= '" . addslashes(substr($val , 2 ))  . "' " ;
                
            if(substr($val , 0 , 2) == "<=")
                return " $column_name <= '" . addslashes(substr($val , 2 ))  . "' " ;
            
            if(substr($val , 0 , 1) == ">")
                return " $column_name > '" . addslashes(substr($val , 1 ))  . "' " ;
            
            if(substr($val , 0 , 1) == "<")
                return " $column_name < '" . addslashes(substr($val , 1 ))  . "' " ;
            
            if($string)
                return " $column_name LIKE '%" . addslashes($val)  . "%' " ;
        
        }else
            return "1=1";
        
        
    }   
    
    /**
     * Return the date and time format for this DB system
     * @return string
     * @link http://www.php.net/date
     */
     private static function datetime_format()
     {
         
        return self::datetime_format;
     }


     
    /**
     * Return the date format for this DB system
     * @return string
     * @link http://www.php.net/date
     */
     private static function date_format()
     {
         
        return self::datetime_format;
         
     }



    /**
     * Log a message in DB
     * @param any $msg The message to be logged
     */
    private static function sys_log($msg)
    {
        self::$qDatabase->LogQuery($msg);
        
    }
    
    
    
    
    
    /**
     * Returns the sql statement to calculate time difference between to columns
     * @param string $t2 the second time column (e.g. end time) 
     * @param string $t1 the first time column (e.g. start time)
     * @return string
     */
    private static function timediff($t2 , $t1)
    {
        return " (IF( $t2 >= $t1 , HOUR(TIMEDIFF($t2 , $t1))*60 + MINUTE(TIMEDIFF($t2 , $t1)) , (24*60) - HOUR(TIMEDIFF($t2 , $t1))*60 - MINUTE(TIMEDIFF($t2 , $t1)) ) )";
    
    }
            
    
    }

?>
