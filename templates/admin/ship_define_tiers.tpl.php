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

?><li class="rounded" <?php if($_CONTROL->EditMode): ?>style="height:440px;"<?php endif; ?>> 
						<div class="title rounded"> 
							<div class="name" style="cursor:pointer;" <?php $_CONTROL->pxyAddNewPage->RenderAsEvents(); ?>><?= $_CONTROL->page->Title; ?></div> 
							<div style="float:right">
							
							<?php if(!$_CONTROL->NewMode): ?>
								<?php $_CONTROL->btnEdit->Render('CssClass=button rounded'); ?>
							<?php endif; ?>
								<?php $_CONTROL->btnSave->Render('CssClass=button rounded'); ?><?php $_CONTROL->btnCancel->Render('CssClass=button rounded'); ?></div> 
						</div>
						
						<?php if($_CONTROL->EditMode): ?>

	<div id='editcontainer'>
	<div class="basic_row"><h4>Define up to 10 tier ranges. Use two digit decimals to ensure your values don't leave price gaps in-between rows. Start your first line defined for 0.00 and for the last amount (the high end), you can enter 999999 to catch any high cart values.</h4></div>

		<div class="basic_row leftindent">
			<div class="colnumber tableheader">#</div>
			<div class="collabel tableheader">Start Amount (i.e. 10.00)</div>
			<div class="collabel tableheader">End Amount (i.e. 49.99)</div>
			<div class="collabel tableheader">Cost</div>
			<div class="clear_float"></div>
		</div> 
		
	<?php $x=1; 
	foreach ($_CONTROL->ctlRows as $ctlRow) {
		echo '<div class="basic_row leftindent">';
		echo '<div class="colnumber">'.$x++.".</div>"; 
		foreach ($ctlRow as $ctlRowItem) {
			echo '<div class="colfield">'; 
			$ctlRowItem->Render(); 
			echo '</div>';
		}
		echo '</div>';
		echo '<div class="clear_float"></div>';
		
		} 
	
	?>
	
	</div>							

<?php endif; ?>
</li>
