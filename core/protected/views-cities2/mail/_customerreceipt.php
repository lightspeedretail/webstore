<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>
    <title><?php echo CHtml::encode(_xls_get_conf('STORE_NAME')); ?></title>

    <style type="text/css">
        <!--
        body { font-family: "Lucida Grande", "Lucida Sans", Verdana, sans-serif; font-size: 13px;font-style: normal;line-height: 1.5em;color: #111;}
        table { font-size: 12px; border: 0px;width: 750px; margin: 0 auto;}
        tbody {background-color: #E9EBEA;}
        .graphicheader {height: 100px;text-align: left; width=750px;background-color: #ffffff;}
        #cartitems table { width: 730px; margin-top: 10px;margin-bottom: 20px; }
        #cartitems th {background: none repeat scroll 0 0 #000000;color: #FFFFFF;font-weight: bold;padding-left: 2px;text-align: left;}
        #cartitems .summary {text-align:right;font-weight: bold;}
        #cartitems .rightprice { text-align:right;}
        #cartitems .shipping {vertical-align: top;text-align: left;}
        #footer a {color: #fff;}
        a img {border: none;}
        -->
    </style>
</head>
	<body>
	<table>
	    <tr>
	        <th class="graphicheader">
				<?php echo CHtml::link(CHtml::image(CController::createAbsoluteUrl(_xls_get_conf('HEADER_IMAGE'),array(),'http')), Yii::app()->baseUrl."/"); ?>
	        </th>
	    </tr>
	</table>
	<table>
	    <tbody>
	        <td style="padding:15px;" width="750px">

				<?php echo Yii::t('email',"Dear") ?>  <?= $cart->customer->first_name ?>,<br/><br/>

				<?php echo Yii::t('email',"Thank you for your order with") ?> <?= _xls_get_conf('STORE_NAME')  ?>.<br/><br/>

	            <div id="cartheader">
	                <table>
	                    <tr>
	                        <th><?php echo Yii::t('checkout',"Shipping") ?></th><th><?php echo Yii::t('checkout',"Billing") ?></th></tr>
	                    <tr><td class="shipping">
				            <?= $cart->shipaddress->first_name." ".$cart->shipaddress->last_name?><br>
							<?php if ($cart->shipaddress->customer_id == $cart->customer_id): ?>
					            <?=  $cart->shipaddress->address1." ".$cart->shipaddress->address2?><br>
					            <?=  $cart->shipaddress->city.", ".$cart->shipaddress->state?><br>
					            <?=  $cart->shipaddress->postal."<br>".$cart->shipaddress->country?><br>
							<?php else: ?>
		                    <?=  Yii::t('global','Gift Recipient Address') ?>
							<?php endif; ?>
	                    </td>
	                        <td class="shipping">
					            <?=  $cart->billaddress->first_name." ".$cart->billaddress->last_name?><br>
					            <?=  $cart->customer->mainphone."<br>".$cart->customer->email?><br>
					            <?=  $cart->billaddress->address1." ".$cart->billaddress->address2?><br>
					            <?=  $cart->billaddress->city.", ".$cart->billaddress->state?><br>
					            <?=  $cart->billaddress->postal."<br>".$cart->billaddress->country?><br>
	                        </td>

	                </table>
	            </div>

	            <div id="cartitems">
	                <table>
	                    <tr>
	                        <th><?php echo Yii::t('global',"Item") ?></th>
	                        <th><?php echo Yii::t('global',"Price") ?></th>
	                    </tr>

				        <?php $items = $cart->cartItems;
				        foreach ($items as $item): ?>
	                        <tr>
	                            <td><?=$item->qty?> of <?=$item->description?> (<?=$item->code?>)</td>
	                            <td class="rightprice"><?=_xls_currency($item->sell_total)?></td>
	                        </tr>
					    <?php endforeach; ?>

	                    <tr>
	                        <td></td><td><hr/></td>
	                    </tr>

	                    <tr>
	                        <td class="summary"><?php echo Yii::t('global',"SubTotal") ?></td>
	                        <td class="rightprice"><?= _xls_currency($cart->subtotal)?></td>
	                    </tr>

				        <?php if ($cart->TaxTotal>0): ?>
	                    <tr>
	                        <td class="summary"><?php echo Yii::t('global',"Tax") ?></td>
	                        <td class="rightprice"><?= _xls_currency($cart->tax_total)?></td>
	                    </tr>
				        <?php endif; ?>

	                    <tr>
	                        <td class="summary"><?=$cart->shipping->shipping_data?></td>
	                        <td class="rightprice"><?= _xls_currency($cart->shipping_sell)?></td>
	                    </tr>


	                    <tr>
	                        <td class="summary"><?php echo Yii::t('global',"Total") ?></td>
	                        <td class="rightprice"><?= _xls_currency($cart->total)?></td>
	                    </tr>


				        <?php if (strlen($cart->payment->payment_data)>0): ?>

	                    <tr>
	                        <td colspan="2"><b><?php echo Yii::t('global',"Payment Data") ?>:</b> <?=$cart->payment->payment_data?></td>
	                    </tr>
				        <?php endif; ?>
	                </table>
	            </div>


		        <?php if (strlen($cart->printed_notes)>0): ?>
					<div id="cartnotes">
						<table>
			                <tr>
			                    <th><?php echo Yii::t('global',"Additional Notes") ?></th>
			                </tr>
			                <tr>
			                    <td><?=$cart->printed_notes?></td>
			                </tr>

			            </table>
					</div>
		        <?php endif; ?>

				<?php echo Yii::t('email',"This email is a confirmation for the order. To view details or track your order, click on the visit link:")  ?>
				<?php echo CHtml::link(
					Yii::app()->controller->createAbsoluteUrl('/cart/receipt', array('getuid'=>$cart->linkid)),
					Yii::app()->controller->createAbsoluteUrl('/cart/receipt', array('getuid'=>$cart->linkid))); ?>
	            <br/><br/>

				<?php echo Yii::t('email',"Please refer to your order ID ") ?> <?= $cart->id_str; ?> <?php echo Yii::t('email'," if you want to contact us about this order.") ?><br/><br/>


	<?=  _xls_format_email_subject('EMAIL_SIGNATURE'); ?>




	            <div id="footer" style="height: 36px; background-color: black; color: #fff;">
	                <p style="display: block; float: left; margin: 8px 0 0 15px; color: #fff;"><a href="mailto:<?= _xls_get_conf('EMAIL_FROM'); ?>"><?= _xls_get_conf('EMAIL_FROM'); ?></a></p>
			        <?php if(_xls_get_conf('STORE_PHONE')): ?>
	                <p style="display: block; float: right; margin: 8px 15px 0 0;"><?php echo Yii::t('CheckoutForm',"Phone") ?>: <?= _xls_get_conf('STORE_PHONE') ?></p>
			        <?php endif; ?>
	            </div>

	    </tbody>
  </body>
</html>
