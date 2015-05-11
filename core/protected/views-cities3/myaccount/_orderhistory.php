<article class="order-history">
	<h4><?= Yii::t('profile', 'Order History'); ?></h4>
	<table class="lines">
		<tbody>
		<?php if(count($model->carts(array('scopes' => 'complete'))) > 0): ?>
			<?php foreach($model->carts(array('scopes' => 'complete')) as $objCart): ?>
				<tr onclick='window.document.location="<?= Yii::app()->createUrl('/cart/receipt', array('getuid' => $objCart->linkid));?>"'</tr>
					<td class="num">
						<?= $objCart->id_str ?>
					</td>
					<td class="date">
						<?= date('F jS, Y', strtotime($objCart->datetime_cre)); ?>
					</td>
					<td class="n-items">
						<?php $totalItemCount = $objCart->getTotalItemCount();
						echo
						Yii::t(
							'profile',
							'{items} item|{items} items',
							array($totalItemCount,
								'{items}' => $totalItemCount
							)
						);
						?>
					</td>
					<td class="subtotal">
						<?= _xls_currency($objCart->total); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php
			else:
		?>
			<h5><?= Yii::t('profile', 'You have no orders in your history'); ?></h5>
		<?php
			endif;
		?>
		</tbody>
	</table>
</article>