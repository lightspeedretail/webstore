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
 * template Edit Cart display
 *
 *
 *
 */

$form = $this->beginWidget('CActiveForm', array(
	'id'=>'ShoppingCart',
	'action'=>array('cart/updatecart'),

));


?>
<div id="cartItems" class="spaceafter"><?php $this->renderPartial('/cart/_cartitems'); ?></div>

<div class="clearfix"></div>

<div class="row-fluid">

    <div class="span2">
		<?php echo CHtml::ajaxButton(
		Yii::t('cart', 'Clear Cart'),
		array('cart/clearcart'),
		array('data'=>array(),
			'type'=>'POST',
			'dataType'=>'json',
			'success' => 'js:function(data){
	                    if (data.action=="alert") {
	                      alert(data.errormsg);
						} else if (data.action=="success") {
							 location.reload();
						}}'
		),array('confirm'=>Yii::t('cart',"Are you sure you want to erase your cart items?"))); ?>
    </div>

    <div class="span2">
		<?php echo CHtml::htmlButton(
		Yii::t('cart', 'Email Cart'),
	    array(
		    'onClick'=>'js:jQuery($("#CartShare")).dialog("open");return false;')
        ); ?>
    </div>

	<?= CHtml::tag('div',array(
		'id'=>'shoppingcartcontinue',
		'class'=>'span4 checkoutlink',
		'onClick'=>'js:window.location.href="'. $this->returnUrl.'"'),
	Yii::t('cart','Continue Shopping'));
	?>

    <div class="span1">
		&nbsp;
    </div>

    <div class="span2">
		<?php echo CHtml::ajaxButton(
		Yii::t('cart', 'Update Cart'),
		array('cart/updatecart'),
		array('data'=>'js:$("#ShoppingCart").serialize()',
			'type'=>'POST',
			'dataType'=>'json',
			'success' => 'js:function(data){
	                    if (data.action=="alert") {
	                      alert(data.errormsg);
						} else if (data.action=="success") {
							 location.reload();
						}}'
		)); ?>
    </div>

</div>

<?php $this->endWidget();


/* This is our sharing box, which remains hidden until used */
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'CartShare',
	'options'=>array(
		'title'=>Yii::t('wishlist','Share my Cart'),
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'380',
		'height'=>(Yii::app()->user->isGuest ? '580' : '430'),
		'scrolling'=>'yes',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>false,
	),
));
$this->renderPartial('/cart/_sharecart', array('model'=>$CartShare));
$this->endWidget('zii.widgets.jui.CJuiDialog');




