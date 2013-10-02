
<div class="span9">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'custompage',
	'enableClientValidation'=>true,
	)); ?>
    <div class="hero-unit">
        <h3><?php echo $this->editSectionName; ?></h3>
        <div class="editinstructions"><?php echo $this->editSectionInstructions; ?></div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'page_key'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'page_key'); ?></div>
            <div class="span3"><?php echo $form->error($model,'page_key'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'title'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'title'); ?></div>
            <div class="span3"><?php echo $form->error($model,'title'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'tab_position'); ?></div>
            <div class="span5"><?php echo $form->dropDownList($model,'tab_position',array(
	            '0'=>'Tab not displayed',
	            '11'=>'1st Position Top',
	            '12'=>'2nd Position Top',
	            '13'=>'3rd Position Top',
	            '14'=>'4th Position Top',
	            '15'=>'5th Position Top',
	            '21'=>'1st Position Bottom',
	            '22'=>'2nd Position Bottom',
	            '23'=>'3rd Position Bottom',
	            '24'=>'4th Position Bottom',
	            '25'=>'5th Position Bottom'
            )); ?></div>
            <div class="span3"><?php echo $form->error($model,'tab_position'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'product_tag'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'product_tag'); ?></div>
            <div class="span3"><?php echo $form->error($model,'product_tag'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'meta_description'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'meta_description'); ?></div>
            <div class="span3"><?php echo $form->error($model,'meta_description'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><label>To Delete</label></div>
            <div class="span5 rememberMe"><?php echo $form->checkBox($model,'deleteMe'); ?>  <span class="rememberMe"><?php echo Yii::t('admin','Tick to delete this custom page on Save'); ?></span></div>
            <div class="span3"><?php echo $form->error($model,'deleteMe'); ?></div>
        </div>


	</div>

	<div class="span11">
	    <h4>Edit Page Content</h4>
		<div class="tip">Click on the first tool on the toolbar to switch to editing HTML directly. Note this editor can be used for HTML and CSS, but any scripting language like JavaScript will not be functional.</div>
		<?php
		$this->widget('ImperaviRedactorWidget', array(
			'model' => $model,
			'attribute' => 'page',
			'htmlOptions'=>array('style'=>"height: 400px; padding-bottom: 20px;"),
			'options' => array(
				'lang' => 'en',
				'width'=> '500',
				'height'=> '400',
				'autoresize'=>false,
				'convertDivs'=>false,
			),
		));
	?>
	</div>


	<div class="row">
		<div class="span11">
            <div class="row">
	            <P></P>
		        <p class="pull-right">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'submit',
					'label'=>'Save',
					'type'=>'primary',
					'size'=>'large',
				)); ?>
				</p>
	        </div>

		</div>
	</div>
</div>
<?php $this->endWidget(); ?>