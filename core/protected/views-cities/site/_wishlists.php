<div class="span12 clickbar" onclick="$('#WishList').slideToggle('fast');"><?= Yii::t('global','Wish Lists'); ?></div>

<div class="containers" id="WishList" style="display:hidden;">
	<?php
	if(!Yii::app()->user->isGuest):
		foreach (Wishlist::LoadUserLists() as $list):?>
            <a href="<?php echo Yii::app()->createUrl('wishlist/view', array('code'=>$list->gift_code)); ?>">
	            <strong><?= $list->registry_name ?></strong></a><br />
		<?php endforeach; ?>
		</br>
        <a href="<?php echo Yii::app()->createUrl('/wishlist'); ?>"><strong><?= Yii::t('global','View all my wish lists'); ?></strong></a> <br />
        <a href="<?php echo Yii::app()->createUrl('wishlist/create'); ?>"><strong><?= Yii::t('global','Create a Wish List'); ?></strong></a> <br />
		<?php endif; ?>

    <a href="<?php echo Yii::app()->createUrl('wishlist/search'); ?>"><strong><?= Yii::t('global','Search for a wish list'); ?></strong></a>

</div>


