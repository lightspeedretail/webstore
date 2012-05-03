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
 * Basic template: Wish List (Gift Registry) Wish List viewing other's Wish Lists
 * email search results
 * 
 *
 */

?>

<div class="registry_row">
		<p class="title"><a href="<? echo _xls_site_url("/gift-search-detail/pg/"); ?>?gift_code=<?= $_ITEM->GiftCode ?>" alt="<?php _xt('View') ?>"><?= $_ITEM->RegistryName ?></a></p>
		<div class="right">
			<p class="expiry"><?= $_ITEM->Customer->Mainname ?></p>
			<p class="expiry"><?= $_ITEM->EventDate ?></p>
		</div>
</div>
		
