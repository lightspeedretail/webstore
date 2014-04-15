<div class="span9">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'custompage',
		'enableClientValidation'=>true,
	)); ?>
    <div class="hero-unit">

        <?php if($sync): ?>
	        <h3>Resync Cloud Account STARTED!</h3>
	        <div class="editinstructions"><b>This process has now been started.</b> LightSpeed Cloud is now in the process of resending the product information to Web Store. Depending on the quantity of products, this may take some time but your store will still be usable in the meantime.</div>
	        <?php else: ?>
	        <h3>Resync Cloud Account</h3>
	        <div class="editinstructions">This command will <b>begin a resync process</b> where your LightSpeed Cloud products will be transferred again to Web Store. This should only be performed if the Web Store database has become out of sync due to system outages or under the advise of technical support.</div>
	    <? endif; ?>
    </div>

	<div class="row">
		<div class="span11">
			<div class="row">
				<P></P>
				<p class="pull-right">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
						'htmlOptions'=>array('id'=>'buttonResync','name'=>'buttonResync','value'=>true),
						'buttonType'=>'submit',
						'label'=>'Start ReSync',
						'type'=>'primary',
						'size'=>'large',
					)); ?>
				</p>
			</div>

		</div>
	</div>
</div>
<?php $this->endWidget(); ?>