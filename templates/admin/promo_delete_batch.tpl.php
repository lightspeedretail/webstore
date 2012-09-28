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

?><li class="rounded" <?php if($_CONTROL->EditMode): ?>style="height:150px;"<?php endif; ?>> 
						<div class="title rounded"> 
							<div class="name" style="cursor:pointer;" <?php $_CONTROL->pxyAddNewPage->RenderAsEvents(); ?>><?= $_CONTROL->page->Title; ?></div> 
							<div style="float:right">
							
							<?php if(!$_CONTROL->NewMode): ?>
								<?php $_CONTROL->btnEdit->Render(); ?>
							<?php endif; ?>
								<?php $_CONTROL->btnCancel->Render('CssClass=button rounded'); ?></div> 
						</div>
						
						<?php if($_CONTROL->EditMode): ?>
<div class="module_task short">

<table width="100%">
<tr height="50px">
	<td class="label left">Delete all codes that are used up (where Qty Remaining = 0)</td>
	<td valign="top"><?php $_CONTROL->btnGo1->Render('Text=Delete Used'); ?></td>
</tr>
<tr height="50px">
	<td class="label left">Delete all codes that are Expired (where Valid Until date has passed)</td>
	<td valign="top"><?php $_CONTROL->btnGo2->Render('Text=Delete Expired'); ?></td>
</tr>
<tr height="50px">
	<td class="label left">Delete all codes that are Single Use (whether valid or not)</td>
	<td valign="top"><?php $_CONTROL->btnGo3->Render('Text=Delete Single Use'); ?></td>
</tr>
<tr height="50px">
	<td class="label left">Delete all codes (completely erase all defined Promo Codes)</td>
	<td valign="top"><?php $_CONTROL->btnGo4->Render('Text=Delete Everything'); ?></td>
</tr>


</table>								
</div>
<?php endif; ?>
</li>
