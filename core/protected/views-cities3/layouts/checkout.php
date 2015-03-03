<?php $this->beginContent('/layouts/checkout-main'); ?>
<div id="wrapper">
		<div class="webstore-overlay webstore-modal-overlay webstore-overlay-aside webstore-checkout" id="checkout">
			    <section>
						<header class="overlay">
								<h1>
									<?php
										echo CHtml::link(
											CHtml::image(Yii::app()->params['HEADER_IMAGE']).
											CHtml::tag('span', array(), Yii::app()->params['STORE_NAME']),
											Yii::app()->createUrl("site/index"),
											array('class' => 'logo-placement')
										);
									?>
					            </h1>
					    <?php echo CHtml::link(Yii::t('cart','Continue Shopping'), $this->createUrl("site/index"), array('class' => 'exit')); ?>
				    </header>

				    <div class="section-inner">

					    <?php echo $content; ?>

				    </div>

					<footer>
						<?php
							echo
								CHtml::htmlButton(
									Yii::t('cart', 'Continue Shopping'),
									array(
										'class' => 'button continue',
										'value' => Yii::t('checkout', "See Shipping Options"),
										'onClick'=>"window.location.href="."'".Yii::app()->createUrl('site/index')."'"
									)
								);
						?>
						<p>
							<?php
							if (Yii::app()->params['ENABLE_SSL'] == 1)
							{
								echo
									CHtml::image(
										Yii::app()->params['umber_assets'] . '/images/lock.png',
										'lock image ',
										array(
											'height'=> 14
										)
									).
									CHtml::tag('strong',array(),'Safe &amp; Secure ').Yii::t('cart','Bank-grade SSL encryption protects your purchase.');
							}

							$objPrivacy = CustomPage::LoadByKey('privacy');
							if ($objPrivacy instanceof CustomPage && $objPrivacy->tab_position !== 0)
							{
								echo ' '.
								CHtml::link(
									Yii::t('cart', 'Privacy Policy'),
									$objPrivacy->Link,
									array('target' => '_blank')
								);
							}
							?>
						</p>
				    </footer>
				</section>
		</div>
</div>


<?php $this->endContent(); ?>
