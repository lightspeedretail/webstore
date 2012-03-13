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

?><li class="rounded" <?php if($_CONTROL->EditMode): ?>style="height:250px;"<?php endif; ?>> 
						<div class="title rounded"> 
							<div class="name" style="cursor:pointer;" <?php $_CONTROL->pxyAddNewPage->RenderAsEvents(); ?>><?= $_CONTROL->page->Title; ?></div> 
							<div style="float:right">
							
							<?php if(!$_CONTROL->NewMode): ?>
								<?php $_CONTROL->btnEdit->Render('CssClass=button rounded'); ?>
							<?php endif; ?>
								<?php $_CONTROL->btnCancel->Render('CssClass=button rounded'); ?></div> 
						</div>
						
						<?php if($_CONTROL->EditMode): ?>
<div class="module_task short">

This feature is for creating one-time use promo codes in bulk for loyalty programs. Paste in your list of codes into the entry blank below. Select an existing promo code to use as a template. The following fields will be copied from this code: Amount, Percent or Money, optional Start and Stop date, Product restrictions and Good Above. Codes can be used once.

<table>
<td class="label left">Enter code(s):<br>
<?php $_CONTROL->txtPageText->RenderWithError(); ?></td>
<td class="label left">Copy settings from code:<br>
<?php $_CONTROL->ctlPromoCodeCopy->RenderWithError(); ?></td>
<td class="label left">&nbsp;<br>
<?php $_CONTROL->btnGo1->Render('Text=Create Codes'); ?></td>
</table>

								
</div>
<?php endif; ?>
</li>
