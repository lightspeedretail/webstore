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
 * template Login popup box (upper right Login link)
 *
 * 
 *
 */

?>


<div class="box_close"  <?php $_CONTROL->pxyCancel->RenderAsEvents(); ?>><!--  --></div>
<div class="login">
			<h1>Login <a href="customer-register/pg"><?php _xt('Create an Account') ?></a></h1>


			<div class="customer_reg_err_msg"><?php $_CONTROL->lblErr->Render() ?></div>

			<div class="row">
				<div class="five columns alpha omega">
					<span class="label"><?php _xt('Email Address') ?></span>
					<?php $_CONTROL->txtEmail->Render() ?>
				</div>

				<div class="five columns alpha omega">
					<span class="label"><?php _xt('Password') ?></span>
					<?php $_CONTROL->txtPwd->Render(); ?>
				</div>
			</div>

			<a href="#" <?php $_CONTROL->pxyForgotPwd->RenderAsEvents(); ?>><?php _xt('Forgot Password?') ?></a>
			
			<?php $_CONTROL->btnLogin->Render('CssClass=right button rounded'); ?>
</div>	
