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

?><li class="rounded" <?php if($_CONTROL->EditMode): ?>style="height:650px;"<?php endif; ?>> 
						<div class="title rounded"> 
							<div class="name" style="cursor:pointer;" <?php $_CONTROL->pxyAddNewPage->RenderAsEvents(); ?>><?= $_CONTROL->page->Title; ?></div> 
							<div style="float:right">
							
							<?php if(!$_CONTROL->NewMode): ?>
								<?php $_CONTROL->btnDelete->Render('CssClass=button rounded'); ?>
								<?php $_CONTROL->btnDeleteConfirm->Render('CssClass=button rounded'); ?>
								<?php $_CONTROL->btnEdit->Render('CssClass=button rounded'); ?>
							<?php endif; ?>
								<?php $_CONTROL->btnSave->Render('CssClass=button rounded'); ?><?php $_CONTROL->btnCancel->Render('CssClass=button rounded'); ?></div> 
						</div>
						
						<?php if($_CONTROL->EditMode): ?>
								<div class="module_config">
									<p>
										<span class="label"><?php _xt('Page Key') ?>:</span> 
										<span class="field">
											<?php $_CONTROL->txtPageKey->RenderWithError(); ?>
											<a href="#" class="tooltip" title="<?php _xt('Page key is used for accessing the page via url. You must define a page key.') ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" class="info_right" /></a>
										</span> 
									</p>
									<p>
										<span class="label"><?php _xt('Page Title/Tab Label') ?>:</span> 
										<?php $_CONTROL->txtPageTitle->RenderWithError(); ?>
										<a href="#" class="tooltip" title="<?php _xt('Page title used for browser page title and the tab label.') ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" class="info_right" /></a></span>
									</p>
									<p>
										<span class="label"><?php _xt('Tab Position') ?>:</span> 
										<?php $_CONTROL->txtTabPosition->RenderWithError(); ?>
										<a href="#" class="tooltip" title="<?php _xt('Tab position on templates.') ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" class="info_right" /></a></span>
									</p>
									<p>
										<span class="label"><?php _xt('Text') ?>:</span> 
										<div style="margin-left: 200px;">
										<?php $_CONTROL->txtPageText->RenderWithError(); ?>
										<a href="#" class="textfield" class="tooltip" title="<?php _xt('The text (or HTML) for the page.') ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" class="info_right" /></a>
										</div>
										</span> 
									</p>
									
									
									<p>
										<span class="label"><?php _xt('Slideshow Web Keyword') ?>:</span> 
										<span class="field">
											<?php $_CONTROL->txtProductTag->RenderWithError(); ?>
											<a href="#" class="tooltip" title="<?php _xt('Create a keyword and enter it here. Enter the same keyword in any products you wish (in one of the Web Keyword blanks) to display in this slider.') ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" class="info_right" /></a>
										</span> 
									</p>
									
									<p>
										<span class="label"><?php _xt('Meta Keywords') ?>:</span> 
										<span class="field">
											<?php $_CONTROL->txtPageKeywords->RenderWithError(); ?>
											<a href="#" class="tooltip" title="<?php _xt('Meta keywords for search engine optimization.') ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" class="info_right" /></a>
										</span> 
									</p>
									
									<p>
										<span class="label"><?php _xt('Meta Description') ?>:</span> 
										<span class="field">
											<?php $_CONTROL->txtPageDescription->RenderWithError(); ?>
											<a href="#" class="tooltip" title="<?php _xt('Meta description for search engine optimization.') ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>" class="info_right" /></a>
										</span> 
									</p>
									<p><span class="label">&nbsp;</span> <span class="field"></span></p>
								</div>
						<?php endif; ?>
</li>
