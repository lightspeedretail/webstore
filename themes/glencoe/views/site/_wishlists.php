<li class="list-group-item-heading" data-toggle="collapse" data-target="#wl">
	<?php echo CHtml::link(Yii::t('global','Wish Lists'),'#'),array('id'=>'wish-lists'); ?>
	<ul class="nav nav-list collapse" id="wl">
		<?php
			if(!Yii::app()->user->isGuest):
				foreach (Wishlist::LoadUserLists() as $list):?>
					<li class="list-group-item cat1">
						<?php echo CHtml::link($list->registry_name,Yii::app()->createUrl('wishlist/view', array('code'=>$list->gift_code))); ?>
					</li>
		<?php endforeach; ?>
		<li class="list-group-item-heading cat1">
			<?php echo CHtml::link(Yii::t('global','View all my Wish Lists'),Yii::app()->createUrl('/wishlist'),array('id'=>'wish-lists-view')) ?>
		</li>
		<li class="list-group-item-heading cat1">
			<?php echo CHtml::link(Yii::t('global','Create a Wish List'),Yii::app()->createUrl('wishlist/create'),array('id'=>'wish-lists-view-create')) ?>
		</li>
		<?php endif; ?>
		<li class="list-group-item-heading cat1">
			<?php echo CHtml::link(Yii::t('global','Search for a Wish List'),Yii::app()->createUrl('wishlist/search'),array('id'=>'wish-lists-view-search')) ?>
		</li>
	</ul>
</li>
