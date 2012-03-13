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
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Web Admin panel template called by xlsws_admin_cpage_panel class
 * Used for editing additional custom pages
 * 
 *
 */

?><script>
    var arrOldValues;

    function SelectAllList(CONTROL) {
        for (var i = 0; i < CONTROL.length; i++) {
            CONTROL.options[i].selected = true;
        }
    }

    function DeselectAllList(CONTROL) {
        for (var i = 0; i < CONTROL.length; i++) {
            CONTROL.options[i].selected = false;
        }
    }


    function FillListValues(CONTROL) {
        var arrNewValues;
        var intNewPos;
        var strTemp = GetSelectValues(CONTROL);
        arrNewValues = strTemp.split(",");
        for (var i = 0; i < arrNewValues.length - 1; i++) {
            if (arrNewValues[i] == 1) {
                intNewPos = i;
            }
        }

        for (var i = 0; i < arrOldValues.length - 1; i++) {
            if (arrOldValues[i] == 1 && i != intNewPos) {
                CONTROL.options[i].selected = true;
            } else if (arrOldValues[i] == 0 && i != intNewPos) {
                CONTROL.options[i].selected = false;
            }

            if (arrOldValues[intNewPos] == 1) {
                CONTROL.options[intNewPos].selected = false;
            } else {
                CONTROL.options[intNewPos].selected = true;
            }
        }
    }


    function GetSelectValues(CONTROL) {
        var strTemp = "";
        for (var i = 0; i < CONTROL.length; i++) {
            if (CONTROL.options[i].selected == true) {
                strTemp += "1,";
            } else {
                strTemp += "0,";
            }
        }
        return strTemp;
    }

    function GetCurrentListValues(CONTROL) {
        var strValues = "";
        strValues = GetSelectValues(CONTROL);
        arrOldValues = strValues.split(",")
    }
</script>
<li class="rounded" <?php if($_CONTROL->EditMode): ?>style="height:350px;"<?php endif; ?>> 
						<div class="title rounded"> 
							<div class="name" style="cursor:pointer;" <?php $_CONTROL->pxyAddNewPage->RenderAsEvents(); ?>><?= $_CONTROL->page->Title; ?></div> 
							<div style="float:right">
							
							<?php if(!$_CONTROL->NewMode): ?>
								<?php $_CONTROL->btnEdit->Render(); ?>
							<?php endif; ?>
								<?php $_CONTROL->btnSave->Render(); ?><?php $_CONTROL->btnCancel->Render(); ?></div> 
						</div>
						
						<?php if($_CONTROL->EditMode): ?>
<div class="module_task">
<table>
<td class="label">Set restrictions for</td>
	<td><?php $_CONTROL->ctlPromoCode->RenderWithError(); ?></td>
	<td class="label">to apply when</td>
	<td><?php $_CONTROL->ctlExcept->RenderWithError(); ?></td>
</table>
<P>
	

<table>
	<td class="label left">Categories:<br><?php $_CONTROL->ctlCategories->RenderWithError(); ?></td>
	<td class="label left">Families:<br><?php $_CONTROL->ctlFamilies->RenderWithError(); ?></td>
	<td class="label left">Classes:<br><?php $_CONTROL->ctlClasses->RenderWithError(); ?></td>
	<td class="label left">Keywords:<br><?php $_CONTROL->ctlKeywords->RenderWithError(); ?></td>
	<td class="label left">Product Codes:<br><?php $_CONTROL->ctlProductCodes->RenderWithError(); ?></td>
</table>

	<div class="tip">Tip: Click in the scrollbar area to avoid accidentally clicking items.</div>
								
</div>
<?php endif; ?>
</li>
