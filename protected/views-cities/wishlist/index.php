<div id="wishlistdisplay" class="span12">
    <div class="row-fluid">
        <div class="span8">
            <h1><?= Yii::t('global','My Wish Lists'); ?></h1>
        </div>
	    <?= CHtml::tag('div',array(
		    'class'=>'span4 darkbutton spaceafter',
		    'onClick'=>'js:window.location.href="'.Yii::app()->createUrl('wishlist/create').'"'),
	        CHtml::link(Yii::t('global','New Wish List'), '#'));
        ?>
    </div>

    <div class="row-fluid spaceafter">
	    <?= Yii::t('wishlist','Click on the wish list name to view list contents, or click on edit to make changes to settings.'); ?>
	</div>

    <div class="row-fluid rowborder">
        <div class="span3">
            <span class="cartlabel light"><?= Yii::t('global','Name'); ?></span>
        </div>

	    <div class="span2">
            <span class="cartlabel light"><?= Yii::t('global','Contains'); ?></span>
        </div>

        <div class="span5">
            <span class="cartlabel light"><?= Yii::t('global','Description'); ?></span>
        </div>

	    <div class="span2">
            <span class="cartlabel light"><?= Yii::t('global','Edit'); ?></span>
        </div>

    </div>

	<?php foreach ($objWishlists as $objWishlist): ?>
	    <div class="row-fluid">
	        <div class="span3">
	            <span class="cartlabel">
		            <?php echo CHtml::link($objWishlist->registry_name,
			                Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code)));
					?>
	        </div>

	        <div class="span2">
		        <?= Yii::t('global','{items} item|{items} items',array(count($objWishlist->wishlistItems),
		            '{items}'=>count($objWishlist->wishlistItems))); ?>
	        </div>

		    <div class="span5">
				<?= $objWishlist->registry_description ?>&nbsp;
	        </div>

            <div class="span2 centeritem">
			    <?php echo CHtml::link(Yii::t('global','Edit'),Yii::app()->createUrl('wishlist/edit',array('code'=>$objWishlist->gift_code)),
			    array('id'=>'editItem'.$objWishlist->id, 'class'=>'editwish'));
			    ?>
            </div>
	    </div>
	<?php endforeach; ?>


</div>