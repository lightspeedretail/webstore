<?php
/*
  Lightspeed Web Store
 
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

));?>
<div class='container-fluid'>

  <?php
  /*
   * This file is used in a renderPartial() to display the cart within another view
   * Because our cart is pulled from the component, we can render from anywhere
   *
   * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
   */
      if (!isset($model)) $model = Yii::app()->shoppingcart;
  ?>

  <?php $this->renderPartial('/cart/_cartitems'); ?>

    <div class="row-fluid cart-adjust">
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
          ),
          array('class'=>'update-cart-btn')); ?>
        </div>
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
          ),
          array('confirm'=>Yii::t('cart',"Are you sure you want to erase your cart items?"),
	          'class'=>'clear-cart-btn')); ?>
        </div>

	    <div class="span2">
	    <?php echo CHtml::htmlButton(
		    Yii::t('cart', 'Share Cart'),
		    array(
			    'onClick'=>'js:jQuery($("#CartShare")).dialog("open");return false;')
	    ); ?>
	    </div>

        <div class="span5 row-buttons">
        <?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout'), array('class' => 'checkout big-button')) ?>
        <?= CHtml::tag('div',array(
          'id'=>'shoppingcartcontinue',
          'class'=>'span4 checkoutlink',
          'onclick'=>'window.location.href=\''. $this->returnUrl.'\''),
          Yii::t('cart','Continue Shopping'));
        ?>
        </div>
    </div>

</div><!-- .container-fluid -->
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




