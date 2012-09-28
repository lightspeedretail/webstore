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
 * Web Admin panel template called by xlsws_admin_modules_config class
 * Used for item's additional configuration options
 * 
 *
 */

?>					<li class="rounded"> 
						<div class="title rounded"> 
							<div class="updown">
							<?php $_CONTROL->btnUp->Render('CssClass=btns'); ?>
							<?php $_CONTROL->btnDown->Render('CssClass=btns'); ?>
							</div>
							<!--  
							<a href="#" class="tooltip" title="<?= $_CONTROL->strFileLocation; ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" alt="Key: STORE_NAME" class="info" /></a>
							-->
							<div class="name"><?= $_CONTROL->Name; ?></div> 
							<div style="float:right"><?php $_CONTROL->btnOnOff->Render(); ?> <?php $_CONTROL->btnConfig->Render(); ?><?php $_CONTROL->btnSave->Render('CssClass=button rounded'); ?><?php $_CONTROL->btnCancel->Render('CssClass=button rounded'); ?></div> 
						</div>
						
						<?php if($_CONTROL->EditMode): ?>
								<div class="module_config">
									<?php  foreach($_CONTROL->fields as $key => $field): ?>
								
									<p>
										<span class="label"><?= $field->Name; ?>:</span> 
										<span class="field">
											<?php $field->RenderWithError(); ?>
											<? if (strlen($field->ToolTip)>1) { ?>
											<a href="#" onclick="return false;" class="tooltip" title="<?= $field->ToolTip; ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" alt="Info" class="info_right" /></a><? } ?>
										</span> 
									</p>
								
									<?php endforeach; ?>
									
									
								<? if (strlen($_CONTROL->HelpfulHint)>0) echo "<br><b>Note:</b> ".$_CONTROL->HelpfulHint; ?>
								</div>
						
						<?php endif; ?>
					</li>
