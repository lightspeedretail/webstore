<?php if(_xls_get_conf('ENABLE_SRO')):   ?>
	<article class="sro-list">
		<h4><?= Yii::t('global', 'My Repairs') ?></h4>
		<?php if(count($model->sros) > 0): ?>
			<?php foreach($model->sros as $sro): ?>
				<div class="span3">
					<?=
					CHtml::link(
						$sro->ls_id,
						Yii::app()->createUrl(
							'sro/view',
							array(
								'code' => $sro->GenerateLink()
							)
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<h5><?= Yii::t('global', 'You have not placed any repair orders with us'); ?></h5>
		<?php endif; ?>
	</article>
<?php endif;  ?>