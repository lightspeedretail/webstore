<?php
$form = $this->beginWidget(
	'CActiveForm',
	array(
		'enableClientValidation' => false,
		'htmlOptions' => array('class' => "section-content", 'id' => "shipping", 'novalidate' => '1',
		)
	)
);
?>
<nav class="steps">
	<ol>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', "Shipping")?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', "Payment")?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', "Confirmation")?></li>
	</ol>
</nav>
<h1>
	<?php echo $header; ?>
</h1>
<div class="modal-conditional-block active">
	<div class="address-form outer-address-form">
		<?php $this->renderPartial($partial, array('model' => $model, 'form' => $form, 'error' => $error)); ?>
		<footer class="submit submit-small">
			<?=
				CHtml::submitButton(
					Yii::t(
						'forms',
						'Submit'
					),
					array(
						'type' => 'submit',
						'class' => 'button',
						'value' => Yii::t(
							'checkout',
							'Save and Continue'
						),
					)
				) .
			'<p>' .
				CHtml::link(
					Yii::t(
						'checkout',
						'Cancel &amp; Pick from Existing Addresses'
					),
					$this->createUrl($cancel),
					array(
						'class' => 'alternate'
					)
				) .
			'</p>';
			?>
		</footer>
	</div>
</div>

<?php $this->endWidget();?>

<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>


