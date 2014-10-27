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

$this->layout='//layouts/column1';

//our current cart. If we've been passed a Cart object, use that
if (!isset($model)) $model = Yii::app()->shoppingcart;

$form = $this->beginWidget('CActiveForm', array(
	'id'=>'ShoppingCart',
	'action'=>array('cart/updatecart'),

));
?>

<div class="row">
    <div id="cartItems" class="col-sm-8">
        <?php $this->renderPartial('/cart/_cartitems', array('model'=>$model)); ?>
    </div>
</div>
<div class="row">
    <?php echo $this->renderPartial('/cart/_cartbuttons', array('model'=>$model)); ?>
</div>


<?php $this->endWidget(); ?>

<?php
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




