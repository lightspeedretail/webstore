
<div id="shoppingcart">
    <div class="shoppingcartarrow"></div>
    <div id="shoppingcarttop" class="rounded-top">
        <span class="title"><a href="<?php echo CHtml::link(Yii::t('cart','Edit Cart'),array('cart/index')) ?>">
	        <span class="carticon">&nbsp;</span></a></span>

        <?php if(!empty(Yii::app()->shoppingcart)):
            foreach (Yii::app()->shoppingcart->cartItems as $item): ?>
                <div id="cartline<?=$item['id']?>" class="minicart_item">
    				<span class="minicart_image">
    					<a href="<?=$item['link']?>">
                            <img src="<?=$item['miniimage']?>" height="<?=$miniheight?>px" />
                        </a>
    				</span>
    				<span class="two columns minicart_desc">
    					<a href="<?=$item['link']?>"><?=$item['description']?>
                            <br>
    						<span class="minicart_qty">
    							<?php echo Yii::t('cart','Qty'); ?>: <span id="qty<?=$item['id']?>"><?=$item['qty']?></span> &nbsp;&nbsp;
                            </span>
                        </a>
    				</span>
    				<span class="one column alpha omega minicart_price">
    					<span id="sell_total<?=$item['id']?>"><?=$item['sell_total']?></span>
    				</span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div id="cartline0" class="minicart_item hidden">
            <span class="minicart_image">
    			<a href="">
                    <img id="product_img0" src="" height="<?=$miniheight?>px" />
                </a>
    		</span>
			<span class="two columns minicart_desc">
				<a id="product_link0" href=""><span id="product_title0"></span>
                    <br>
					<span class="minicart_qty"><?php echo Yii::t('cart','Qty'); ?>: <span id="qty0"></span> &nbsp;&nbsp;
                    </span>
                </a>
			</span>
			<span class="one column alpha omega minicart_price">
				<span id="sell_total0"></span>
			</span>
        </div>

        <?php if(empty(Yii::app()->shoppingcart)): ?>
            <div id="cartempty">
                <div class="emptymessage"><?php echo Yii::t('cart','Your cart is empty'); ?></div>
            </div>
       <?php endif; ?>


    </div>

    <div id="shoppingcartbottom">
        <div class="cart_label two columns alpha omega"><span class="subtotallabel"><?php echo Yii::t('cart','SubTotal'); ?></span></div>
        <div class="cart_price two columns alpha omega"><span id="subtotal"><?= $subtotal?></span>&nbsp;&nbsp;</div>
    </div>
</div>