<div id="wishlistdisplay" class="span12 wishlistsearch">
    <div class="row-fluid">
        <div class="span6">
            <h1><?= Yii::t('global','Wish List Search'); ?></h1>
        </div>
    </div>

	<?php if (count($objWishlists)>0): ?>
    <div class="row-fluid">
	    <?= Yii::t('wishlist','Click on the wish list name to view.'); ?>
	</div>
	<?php endif; ?>

	<div id="wishlistlist">
		<?php if (count($objWishlists)>0): ?>
			<div class="row-fluid rowborder">
				<div class="span3">
					<span class="cartlabel light"><?= Yii::t('global','Name'); ?></span>
				</div>

				<div class="span2" style="width: 25%;">
					<span class="cartlabel light"><?= Yii::t('global','Contains'); ?></span>
				</div>

				<div class="span6">
					<span class="cartlabel light"><?= Yii::t('global','Description'); ?></span>
				</div>
			</div>
		<?php endif; ?>

		<?php foreach ($objWishlists as $objWishlist): ?>
			<div class="row-fluid">
				<div class="span3 alpha">
	            <span class="cartlabel">
		            <?php echo CHtml::link($objWishlist->registry_name,
			            Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code)));
		            ?>
				</div>

				<div class="span2" style="width: 25%;">
					<?= Yii::t('global','{items} item|{items} items',array(count($objWishlist->wishlistItems),
						'{items}'=>count($objWishlist->wishlistItems))); ?>
				</div>

				<div class="span6">
					<?= $objWishlist->registry_description ?>&nbsp;
				</div>

			</div>
		<?php endforeach; ?>
	</div>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'wishlistsearch',
		'enableClientValidation'=>true,
		'focus'=>array($model,'email'),
	));
	?>

	<div class="row-fluid">
		<div class="span4" style="min-width: 250px;">
			<?php echo Yii::t('global','Search for a wish list by email address'); ?>:
		</div>
		<div class="span7">
			<?php echo $form->textField($model,'email'); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
	</div>

	<div class="span9 submitblock" >
		<?php echo CHtml::submitButton(Yii::t('global','Search'), array('id'=>'btnSubmit'));  ?>
	</div>


	<?php $this->endWidget(); ?>

</div>