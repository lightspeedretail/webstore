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
 * template Wish List (Gift Registry) My Wish Lists listings
 *
 *
 *
 */

?>

	<div class="row">
		<div class="four columns alpha">
			<a href="<?php echo _xls_site_url('/gift-registry/pg?registry_id='.$_ITEM->Rowid); ?>"><?= $_ITEM->RegistryName ?></a>
		</div>

		<div class="three columns">
			<?= $_ITEM->EventDate ?>
		</div>

		<div class="three columns omega">
			<a href="<?php echo _xls_site_url('/gift-registry/pg?registry_id='.$_ITEM->Rowid); ?>"><?php _xt('Edit') ?></a>
		</div>

	</div>
		
