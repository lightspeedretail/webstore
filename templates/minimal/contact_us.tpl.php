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
 * template Contact Us email form
 * Also displays Contact Us text from Web Admin panel
 * 
 *
 */

?>

<div class="rounded" style="margin-left: 20px; border:1px sold #eee;">
<?php echo $this->page;  ?>
</div><br />

	<fieldset class="contact">
		
			<p align=center>
				<?php $this->lblError->Render(); ?>
			</p>
			<p>
			  <label for="name"><?php _xt('Name') ?>:</label><br />
			  <?php $this->txtName->RenderWithError(); ?>
			</p>
			<p>
			  <label for="email"><?php _xt('Email') ?>:</label><br />
			  <?php $this->txtEmail->RenderWithError(); ?>
			</p>
			<p>
			  <label for="phone"><?php _xt('Phone') ?>:</label><br />
			  <?php $this->txtPhone->RenderWithError(); ?>
			</p>

			<p>
			  <label for="subject"><?php _xt('Subject') ?>:</label><br />
			  <?php $this->txtSubject->RenderWithError(); ?>
			</p>
			<label for="message"><?php _xt('Message') ?>:</label><br />
			<?php $this->txtMsg->RenderWithError('Width=315' , 'Height=150'); ?>
			
		<?	if (_xls_show_captcha('contactus')) { ?>
			<div class="block margin">
				<dl>
					<dt><label for="Name"><?php $this->lblVerifyImage->Render(); ?></label></dt>
				</dl>
			</div>
	
			<div class="block margin">
				<dl class="left">
					<dd><?php $this->txtCRVerify->RenderWithError(); ?></dd>
				</dl>
			</div>
		<? } ?>
						
		<p style="margin-top:40px;"><?php $this->btnSubmit->Render('CssClass=button rounded'); ?></p>
			
		</fieldset>
