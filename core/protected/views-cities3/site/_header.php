<script>
	$(function() {
		$("#topbar-cart-text").on('click', function(e) {
			showEditCartModal(<?= CJSON::encode(Yii::app()->createUrl('editcart')) ?>);
			//prevents default link behavior that causes the cart modal to be called twice
			e.preventDefault();
		});
	});
</script>

<div id="topbar" class="row-fluid page-header">
	<div class="span9">
	<!-- template header -->
		<div id="headerimage" class="logo">
			<?php echo CHtml::link(CHtml::image($this->pageHeaderImage, 'web store header image'), $this->createUrl("site/index")); ?>
		</div>

		<ul>
			<!-- Cart -->
			<?php if (Yii::app()->params['DISABLE_CART'] == false): ?>
			<li>
				<a id="topbar-cart-text" href=""><em id="em"></em><?php echo Yii::t('cart', 'Cart'); ?></em></a>
				<small id="topbar-cart-number" >
						<span id="cartItemsTotal">
							<?php
							echo Yii::app()->shoppingcart->totalItemCount;
							?>
						</span>
				</small>
			</li>
			<?php endif; ?>
			<!-- login Register -->
			<?php if(Yii::app()->user->isGuest):
				echo "<li>".CHtml::link(Yii::t('global', 'Account'), array("/site/login"))."</li>";
			elseif(!Yii::app()->user->isGuest):
				echo "<li>".CHtml::link(Yii::t('global', 'Account'), array("/myaccount"))."</li>";
			endif; ?>

			<!-- wish Lists -->
			<?php
				if (_xls_get_conf('ENABLE_WISH_LIST')):
					if (Yii::app()->user->isGuest):
						echo "<li>".CHtml::link(Yii::t('global', 'Wish Lists'), array("wishlist/search"))."</li>";
					else:
						echo "<li>".CHtml::link(Yii::t('global', 'Wish Lists'), array("/wishlist"))."</li>";
					endif;
				endif;
			?>

			<!-- log Out -->
			<?php if(!Yii::app()->user->isGuest):
				echo "<li>".CHtml::link(Yii::t('global', 'Logout'), array("site/logout"))."</li>";
			endif; ?>

		</ul>
</div>
	<div class="span3">
		<?php if(_xls_get_conf('LANG_MENU', 0)): ?>
			<div class="langmenu">
				<?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
				</div>
		<?php endif; ?>
	</div>
</div>
