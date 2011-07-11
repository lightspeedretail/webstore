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
     * XLSContentBox
     * 
     * Creates the frame used for returning certain search results
     * List of orders placed, list of SROs 
     *             
     */

   class XLSContentBox extends QPanel{
        
        protected $strCssClass = "border rounded";
        
        protected function GetControlHtml() {
            $this->strText = '';
            $this->strText .= "\n" . 
                '<div class="border_header"><p class="left">' . 
                $this->strName . '</p></div>';

            $objs = $this->GetChildControls();
            
            foreach($objs as $obj) {
                if (($obj instanceof QTextBoxBase) || 
                    ($obj instanceof QCheckBox) || 
                    ($obj instanceof QListBox) || 
                    (($obj instanceof QLabel) && 
                        ($obj->Name != '')) || 
                    ($obj instanceof QCalendar) || 
                    ($obj instanceof QFCKeditor)) 
                {
                    if($obj->Visible)
                        $this->strText .= "\n" . 
"<div class=\"left " . (($obj->DisplayStyle == QDisplayStyle::Inline)?
    "margin":(($obj->DisplayStyle == QDisplayStyle::None)?"":"clear")) . "\">
    <dl>
        <dt><label for=\"" . $obj->Name . "\">" .
            _sp($obj->Name) . (($obj->Required)?" *":""). "</label></dt>
        <dd>" . $obj->RenderWithError(false) . "</dd>
    </dl>
</div>";
                }else{
                    $this->strText .= "\n" . 
"<div class=\"left clear\">
    <dl>
        <dd>" . $obj->Render(false) . "</dd>
    </dl>
</div>";
                }
            }
            
            return parent::GetControlHtml();
        }
    }

?>
