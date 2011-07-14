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
 * Framework template Wish List (Gift Registry) Wish List viewing other's Wish List contents
 * with Buy Now button
 * 
 *
 */


$this->dlgGiftQty->Render();
if(!$this->logTrack): ?>


	<div class="registry rounded">
	<p style="padding: 50px;"><?php _xt("Password") ?> &nbsp; <?php $this->txtGListPassword->RenderWithError() ?> &nbsp; <?php $this->btnGetIn->Render('CssClass=button rounded psubmit') ?></p>
	</div>


<?php else: ?>



	<div class="registry rounded">
		<div class="registry_header">
			<p class="left"><?= $this->objGiftDetail->RegistryName; ?></p>
		</div>
	
		<div class="gregistry_desc">
			<?php echo stripslashes($this->objGiftDetail->HtmlContent); ?>
		</div>
	</div>


	<div class="registry rounded">
		<div class="registry_header">
			<p class="left"><?php _xt('Gift Products') ?></p>
			<div class="right">
				<p style="margin: 0 65px 0 0;"><?php _xt('Price') ?></p>
				<p style="margin: 0 45px 0 0;"><?php _xt('Requested') ?></p>
				<p style="margin: 0 45px 0 0;"><?php _xt('Remaining') ?></p>
				<p style="margin: 0 15px 0 0;"><?php _xt('Purchase') ?></p>
			
			</div>
		</div>
		
		<?php $this->dtrGiftList->Render() ?>
		
	</div>
	

<?php  endif;  ?>	
	
	
	
	
	
			

			

