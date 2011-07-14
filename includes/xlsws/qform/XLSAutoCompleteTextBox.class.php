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
  
   /*
     *  XLSAutocompleteTextBox
     * 
     * Auto Complete box used for site Search box to show results as you type
     * 
     *             
     */
  
    class XLSAutoCompleteTextBox extends QTextBox{
    
        protected function GetControlHtml() {
            $strStyle = $this->GetStyleAttributes();

            if ($strStyle)
                $strStyle = sprintf('style="%s"', $strStyle);

            $strToReturn = sprintf('<input type="search" name="%s" ' .
                'id="%s" placeholder="Search..." value="%s" %s/>',
                $this->strControlId,
                $this->strControlId,
                QApplication::HtmlEntities($this->strText),
                $strStyle);

            return $strToReturn;
        }
    }   
    
?>
