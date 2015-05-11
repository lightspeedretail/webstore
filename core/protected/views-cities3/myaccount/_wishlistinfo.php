<?php if(_xls_get_conf('ENABLE_WISH_LIST')):?>
	<div class="wishlist-info">
		<h4><?= Yii::t('profile', 'Wishlists'); ?></h4>
		<ul class="wishlists">
			<?php if(count($model->wishlists) > 0): ?>
				<?php foreach($model->wishlists as $objWishlist): ?>
					<li class="wishlist-block">
						<p class="webstore-label private" onclick="window.document.location='<?= Yii::app()->createUrl('/wishlist/view', array('code' => $objWishlist->gift_code)) ?>';">
							<span class="title">
								<?= $objWishlist->registry_name;?>
							</span>
							<span class="superscript-label">
								<?php
									switch ($objWishlist->visibility){
										case 0:
											echo Yii::t('profile', 'Private');
											break;
										case 1:
											echo Yii::t('profile', 'Personal');
											break;
										case 2:
											echo Yii::t('profile', 'Public');
											break;
									}
								?>
							</span>
							<br>
							<?=
								Yii::t(
									'global',
									'{items} item|{items} items',
									array(
										count(
											$objWishlist->wishlistItems
										),
									'{items}' => count(
										$objWishlist->wishlistItems
									)
									)
								);
							?>
							<br>
							<?php
								if($objWishlist->event_date)
								{
									echo '<span class="date">' .
										Yii::t('profile', 'Event Date') .
										': ' .
										$objWishlist->event_date .
										'</span>';
								}
							?>
						</p>
					</li>
				<?php endforeach; ?>
			<?php else: ?>
				<li class="wishlist-block">
					<p onclick="window.document.location='<?= Yii::app()->createUrl('/wishlist/create'); ?>';">
						<span class="title"><?= Yii::t('profile', 'You have no saved wishlists'); ?></span><br>
						<?= Yii::t('profile', 'Create a new wishlist for yourself and share with others'); ?>
					</p>
				</li>
			<?php endif; ?>
			<li class="add">
				<?=
					CHtml::link(
						Yii::t('profile', 'Add New Wishlist'),
						Yii::app()->createUrl(
							'/wishlist/create'
						),
						array('class' => 'small button')
					);
				?>
			</li>
		</ul>
	</div>
<?php endif;  ?>

