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
 * Framework template Login popup box (upper right Login link) 
 *
 * 
 *
 */

?>


<div class="box_close"  <?php $_CONTROL->pxyCancel->RenderAsEvents(); ?>><!--  --></div>
<div class="login">
			<h1>Login <a href="customer-register/pg" style="font-size: 14px;"><?php _xt('Create an Account') ?></a></h1>
		
			<p align="center"><?php $_CONTROL->lblErr->Render('CssClass=red') ?></p>
			<p><?php _xt('Email Address') ?></p>
			<?php $_CONTROL->txtEmail->Render('CssClass=login_input') ?><br />
							
			<?php _xt('Password') ?><a href="#" <?php $_CONTROL->pxyForgotPwd->RenderAsEvents(); ?> class="lfp"><?php _xt('Forgot Password?') ?></a>
			<?php $_CONTROL->txtPwd->Render('CssClass=login_input'); ?>
			
			
			
			<?php $_CONTROL->btnLogin->Render('CssClass=right button rounded'); ?><br /><br /><br />
</div>	
