<div id="wishlistdisplay" class="span12">
	<div class="row-fluid">
		<div class="span6">
			<h1><?= Yii::t('global','Wish List Search'); ?></h1>
		</div>
	</div>

	<?php if (count($objWishlists)>0): ?>
	<div class="row-fluid">
		<?= Yii::t('wishlist','Click on the wish list name to view.'); ?>
	</div>

	<div class="row-fluid rowborder">
		<div class="span3">
			<span class="cartlabel light"><?= Yii::t('global','Name'); ?></span>
		</div>

		<div class="span2 heading">
			<span class="cartlabel light"><?= Yii::t('global','Contains'); ?></span>
		</div>

		<div class="span4 heading">
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

			<div class="span2">
				<?= Yii::t('global','{items} item|{items} items',array(count($objWishlist->wishlistItems),
					'{items}'=>count($objWishlist->wishlistItems))); ?>
			</div>

			<div class="span4">
				<?= $objWishlist->registry_description ?>&nbsp;
			</div>

		</div>
	<?php endforeach; ?>

		<?php
			$form = $this->beginWidget('CActiveForm', array(
			'id' => 'wishlistsearch',
			'enableClientValidation' => true
			));
		?>
		<div class="row-fluid">
			<div class="span9">
				<?= CHtml::label(Yii::t('global', 'Search for a wish list by email address'), 'wishlistsearch') ?>
				<?php echo $form->textField($model, 'email', array('id' => 'wishlistsearch')); ?>
				<?php echo $form->error($model, 'email'); ?>
			</div>
		</div>
		<div class="row-fluid shortrow buttons">
			<div class="inline">
				<?php echo CHtml::submitButton(Yii::t('global','Search'), array('id'=>'btnSubmit'));  ?>
			</div>
			<div class="forgotpassword inline">
				<?php if(Yii::app()->user->isGuest):
					echo CHtml::link(Yii::t('global', 'View my Wish Lists'), Yii::app()->createUrl('site/login'));
				else:
					echo CHtml::link(Yii::t('global', 'View my Wish Lists'), Yii::app()->createUrl('/wishlist'));
				endif; ?>
			</div>

		</div>

		<?php $this->endWidget(); ?>



</div>
