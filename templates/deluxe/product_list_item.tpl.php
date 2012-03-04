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
 * Deluxe template: Individual cell (square) on product grid 
 *
 * 
 *
 */

?>
<div class="product_cell">
					
						<h2><a href="<?php _xt($_ITEM->Link); ?>"><?= _xls_truncate(_sp($_ITEM->Name) , 50); ?></a></h2>					
						<?php $_FORM->render_prod_drag_image($_ITEM) ?>	
						<?php
						if(_xls_get_conf('ENABLE_SLASHED_PRICES' , 0)==2 && 
							$_ITEM->SellWeb != 0 && 
							$_ITEM->SellWeb < $_ITEM->Sell)
							echo '<div class="price_reg">'._sp("Regular Price").' : <strike>'.
								_xls_currency($_ITEM->Sell).'</strike></div>';
							else echo '<div class="price_reg">&nbsp;</div>';
							?>
						<div class="product_cell_price rounded"><a href="<?php _xt($_ITEM->Link); ?>"><?= _xls_currency($_ITEM->Price); ?></a></div>
						<p> <!--<a href="<?php _xt($_ITEM->Link); ?>"><?php _xt($_ITEM->Code); ?></a --></p>

			</div>

