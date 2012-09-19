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

$items = $cart->GetCartItemArray(); 


echo '<div id="cartitems"><table>';
echo '<tr>';
echo '<th>Shipping</th><th>Billing (if different)</th></tr>';
echo '<tr><td class="shipping">';
echo $cart->ShipFirstname." ".$cart->ShipLastname.'<br>';
echo $cart->ShipAddress1." ".$cart->ShipAddress2.'<br>';
echo $cart->ShipCity.", ".$cart->ShipState.'<br>';
echo $cart->ShipZip."<br>".$cart->ShipCountry.'<br>';
echo '</td>';
echo '<td class="shipping">';
echo $cart->Firstname." ".$cart->Lastname.'<br>';
echo $cart->Phone."<br>".$cart->Email.'<br>';
echo str_replace("\n","<br>",$cart->AddressBill).'<br>';
echo '</td>';

echo '</table></div>';

echo '<div id="cartitems"><table>';

echo '<tr>';
echo '<th>Item</th>';
echo '<th>Price</th>';
echo '</tr>';

foreach ($items as $item) {
	echo '<tr>';
	echo '<td>' . $item->Qty.' of '.$item->Description.' ('.$item->Code.')' . '</td>';
	echo '<td class="rightprice">' . _xls_currency($item->SellTotal) . '</td>';
	echo '</tr>';
}

	echo '<tr>';
	echo '<td></td><td><hr/></td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td class="summary">SubTotal</td>';
	echo '<td class="rightprice">' . _xls_currency($cart->Subtotal) . '</td>';
	echo '</tr>';

	if ($cart->TaxTotal>0)
	{
		echo '<tr>';
		echo '<td class="summary">Tax</td>';
		echo '<td class="rightprice">' . _xls_currency($cart->TaxTotal) . '</td>';
		echo '</tr>';
	}

	echo '<tr>';
	echo '<td class="summary">'.$cart->ShippingData.'</td>';
	echo '<td class="rightprice">' . _xls_currency($cart->ShippingSell) . '</td>';
	echo '</tr>';

	
	echo '<tr>';
	echo '<td class="summary">Total</td>';
	echo '<td class="rightprice">' . _xls_currency($cart->Total) . '</td>';
	echo '</tr>';


	if (strlen($cart->PaymentData)>0) {
	
	echo '<tr>';
	echo '<td colspan="2"><b>Payment Data:</b> '. $cart->PaymentData . '</td>';
	echo '</tr>';
	
	
	
	}
echo '</table></div>';


if (strlen($cart->PrintedNotes)>0) {
echo '<div id="cartitems"><table>';
echo '<tr>';
echo '<th>Additional Notes</th></tr>';
	echo '<tr>';
	echo '<td>'. $cart->PrintedNotes . '</td>';
	echo '</tr>';

echo '</table>';
}

?>



