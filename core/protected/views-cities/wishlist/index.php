<div id="wishlistdisplay" class="span12">
    <div class="row-fluid">
        <div class="span4 wishlistheader">
            <h1><?= Yii::t('global','My Wish Lists'); ?></h1>
        </div>
	    <?= CHtml::tag('div',array(
			    'class'=>'span4 wishlistnew lightbutton spaceafter',
			    'onclick'=>'window.location.href=\''.Yii::app()->createUrl('wishlist/search').'\''
		    ),

		    CHtml::link(Yii::t('global','Wish List Search'), '#'));
	    ?>
	    <?= CHtml::tag('div',array(
		    'class'=>'span4 wishlistnew darkbutton spaceafter',
			'onclick'=>'window.location.href=\''.Yii::app()->createUrl('wishlist/create').'\''
		    ),
	        CHtml::link(Yii::t('global','New Wish List'), '#'));
        ?>

    </div>

    <div class="row-fluid spaceafter">
	    <?= Yii::t('wishlist','Click on the wish list name to view list contents, or click on edit to make changes to settings.'); ?>
	</div>

	<div id="wishlisttable">
    <div class="row-fluid rowborder">
        <div class="span3 cartlabel name light">
            <?= Yii::t('global','Name'); ?>
        </div>

	    <div class="span2 cartlabel items light">
            <?= Yii::t('global','Contains'); ?>
        </div>

        <div class="span5 cartlabel desc-registry light">
            <?= Yii::t('global','Description'); ?>
        </div>

	    <div class="span2 cartlabel item-edit centeritem light">
            <?= Yii::t('global','Edit'); ?>
        </div>

    </div>

	<?php foreach ($objWishlists as $objWishlist): ?>
	    <div class="row-fluid">
	        <div class="span3 cartlabel name">
		            <?php echo CHtml::link($objWishlist->registry_name,
			                Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code)));
					?>
	        </div>

	        <div class="span2 cartlabel items">
		        <?= Yii::t('global','{items} item|{items} items',array(count($objWishlist->wishlistItems),
		            '{items}'=>count($objWishlist->wishlistItems))); ?>
	        </div>

		    <div class="span5 cartlabel desc-registry">
				<?= $objWishlist->registry_description ?>&nbsp;
	        </div>

            <div class="span2 cartlabel item-edit centeritem">
			    <?php echo CHtml::link(Yii::t('global','Edit'),Yii::app()->createUrl('wishlist/edit',array('code'=>$objWishlist->gift_code)),
			    array('id'=>'editItem'.$objWishlist->id, 'class'=>'editwish'));
			    ?>
            </div>
	    </div>
	<?php endforeach; ?>
	</div>


</div>