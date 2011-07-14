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
 * Basic template: Wish List (Gift Registry) My Wish Lists listings
 * 
 * 
 *
 */

?>

<div class="registry_row">
		<p class="title" style="cursor: pointer;" <?php $this->pxyGRView->RenderAsEvents($_ITEM->Rowid); ?> ><?= $_ITEM->RegistryName ?></p>
		<div class="right">
			<p class="expiry"><?= $_ITEM->EventDate ?></p>
			<p class="edit"><a href="#"<?php $this->pxyGREdit->RenderAsEvents($_ITEM->Rowid); ?>><img src="<?= templateNamed('css/images/btn_edit.png') ?>" alt="<?php _xt('View') ?>"/></a></p>
		</div>
</div>
		
