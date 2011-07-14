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
     * Extends QDataGrid to add header row styles and few others
     */
    class XLSGrid extends QDataGrid{
        
        protected $arrTotalObjects;
        
        
        protected function GetHeaderRowHtml() {
            $objHeaderStyle = $this->objRowStyle->ApplyOverride(
                $this->objHeaderRowStyle);

            $strToReturn = sprintf('<tr %s>', $objHeaderStyle->GetAttributes());
            $intColumnIndex = 0;
            if ($this->objColumnArray) 
            foreach ($this->objColumnArray as $objColumn) {
                if ($objColumn->OrderByClause) {
                    // This Column is Sortable
                    if ($intColumnIndex == $this->intSortColumnIndex)
                        $strName = $this->GetHeaderSortedHtml($objColumn);
                    else
                        $strName = $objColumn->Name;

                    $this->strActionParameter = $intColumnIndex;

                    $strToReturn .= 
                        sprintf('<th %s %s><a href="#" %s%s>%s</a></th>',
                            $this->objHeaderRowStyle->GetAttributes(),
                            $objColumn->HeaderCssClass,
                            $this->GetActionAttributes(),
                            $this->objHeaderLinkStyle->GetAttributes(),
                            $strName
                        );
                } else
                    $strToReturn .= 
                        sprintf('<th %s %s>%s</th>', 
                        $this->objHeaderRowStyle->GetAttributes(), 
                        $objColumn->HeaderCssClass,
                        $objColumn->Name
                    );
                $intColumnIndex++;
            }

            $strToReturn .= '</tr>';

            return $strToReturn;
        }       
        
        public function __get($strName) {
            switch ($strName) {
                case 'TotalObjects':
                    return  $this->arrTotalObjects;

                default:
                    try {
                        return parent::__get($strName);
                    } catch (QCallerException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
            }
        }

        public function add_total_object($label , $obj){
            $this->arrTotalObjects[$label] = $obj;
        }
        
        public function __set($strName, $mixValue) {
            switch ($strName) {
                case 'TotalObjects':
                    try {
                        return ($this->arrTotalObjects = $mixValue);
                    } catch (QInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                default:
                    try {
                        return (parent::__set($strName, $mixValue));
                    } catch (QCallerException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
            }
        }       
        
        
        /**
         * Print total column at the bottom
         */
        protected function GetFooterRowHtml(){
            if(!$this->arrTotalObjects) return;

            $strToReturn = '';
            $colspan = count($this->objColumnArray) -1;
            
            foreach($this->arrTotalObjects as $label => $obj){
                if(!$obj->Visible)
                    continue;
                
                $strToReturn .= sprintf('<tr>');
                $strToReturn .= 
                    sprintf('<td colspan="%s" class="xls_grid_total">%s</td>',
                        $colspan, 
                        $label
                    );
                
                $strToReturn .= sprintf('<td%s>%s</td>', 
                    (($obj->CssClass != '')?
                     (" class=\"" . $obj->CssClass . "\"" ):""),
                    $obj->Render(false)
                );
                $strToReturn .= sprintf('</tr>');
            }
            
            return $strToReturn;
        }
    }
    
    class XLSGridColumn extends QDataGridColumn {

        protected $strHeaderCssClass;

        public function __get($strName) {
            switch ($strName) {
                case 'HeaderCssClass': return ($this->strHeaderCssClass?" class=\"" . $this->strHeaderCssClass  .  "\"":" align=left");

                default:
                    try {
                        return parent::__get($strName);
                    } catch (QCallerException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
            }
        }

        public function __set($strName, $mixValue) {
            switch ($strName) {
                case 'HeaderCssClass':
                    try {
                        return ($this->strHeaderCssClass = QType::Cast($mixValue, QType::String));
                    } catch (QInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                default:
                    try {
                        return (parent::__set($strName, $mixValue));
                    } catch (QCallerException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
            }
        }       
        
        
    }
    
