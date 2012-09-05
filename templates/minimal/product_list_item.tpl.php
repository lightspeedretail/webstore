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
 * template Individual cell (square) on product grid
 *
 * 
 *
 */

	//Determine if we need to use an extra CSS keyword for our skeleton formatting
	if ((2+$_CONTROL->CurrentItemIndex) % 4 == 0) $xtra=" alpha"; //Beginning of full row
    if ((1+$_CONTROL->CurrentItemIndex) % 4 == 0 || $_CONTROL->CurrentItemIndex == count($_CONTROL->DataSource) - 1)
	    $xtra = " omega"; //end of full row

?>
	<div class="four columns <?=$xtra?> product_cell">
		<a href="<?php echo $_ITEM->Link; ?>"><img src="<?php echo $_ITEM->SmallImage; ?>"></a>
<!--						--><?php
//						if(_xls_get_conf('ENABLE_SLASHED_PRICES' , 0)==2 &&
//							$_ITEM->SellWeb != 0 &&
//							$_ITEM->SellWeb < $_ITEM->Sell)
//							echo '<div class="price_reg">'._sp("Regular Price").' : <strike>'.
//								_xls_currency($_ITEM->Sell).'</strike></div>';
//							else echo '<div class="price_reg">&nbsp;</div>';
//							?>
		<div class="product_cell_price">
			<a href="<?php echo $_ITEM->Link; ?>"><h2><?= _xls_truncate(_sp($_ITEM->Name) , 50); ?></h2>
			<?= _xls_currency($_ITEM->Price); ?></a>
		</div>


	</div>

