
<div class="span12 clickbar" onclick="$('#WishList').slideToggle('fast');"><?= Yii::t('wishlist','Wish Lists')?></div>

<div class="containers" id="WishList" style="display:hidden;">
	<?php if(!Yii::app()->user->isGuest): ?>
	    <a href="<?php echo Yii::app()->createUrl('wishlist/index'); ?>"><strong><?= Yii::t('wishlist','My Wish Lists')?></strong></a>
	    <br />
	<?php endif; ?>
    <a href="<?php echo Yii::app()->createUrl('wishlist/search'); ?>"><strong><?= Yii::t('wishlist','Search for a Wish List')?></strong></a>

</div>


