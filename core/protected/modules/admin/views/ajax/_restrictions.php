<?php
/* This is the contents of the modal login dialog box. It's a Render Partial since we don't need the full HTML wrappers */
?><div class="restrictions">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'restrictions',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>false,
));   ?>
    <div class="row toplabel">Set restrictions for <strong><?php echo $model->promocode; ?></strong> to apply when
	    <?php echo $form->dropDownList($model,'exception',$model->getExceptionList(),array('class'=>'dropdown')); ?>
	</div>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
        <div class="span3">
			<?php echo $form->labelEx($model,'categories'); ?>
	        <?php echo $form->listBox($model,'categories',
		        CHtml::listData(Category::model()->findAllByAttributes(array('parent'=>null),array('order'=>'label')), 'label', 'label'),
		        array('multiple'=>'multiple',
			        'class'=>'tall wider',
	                'onChange'=>'js:FillListValues(this)',
	                'onMouseDown'=>'js:GetCurrentListValues(this)',
			       ));
	        ?>
	        <div class="clearfix"></div><?php echo CHtml::link(Yii::t('admin','Clear All'),'#',array(
		        'class'=>'listboxReset',
		        'onclick'=>'js:jQuery($("#'.CHtml::activeId($model,'categories').'").find("option").prop("selected", false))',
	        ));
	        ?>
	        <?php echo $form->error($model,'categories'); ?>
        </div>

        <div class="span3">
			<?php echo $form->labelEx($model,'families'); ?>
	        <?php echo $form->listBox($model,'families',
		        CHtml::listData(Family::model()->findAll(array('order'=>'family')), 'family', 'family'),
		        array('multiple'=>'multiple',
			        'class'=>'tall wider',
			        'onChange'=>'js:FillListValues(this)',
			        'onMouseDown'=>'js:GetCurrentListValues(this)',
		        ));
	        ?>
	        <div class="clearfix"></div><?php echo CHtml::link(Yii::t('admin','Clear All'),'#',array(
		        'class'=>'listboxReset',
		        'onclick'=>'js:jQuery($("#'.CHtml::activeId($model,'families').'").find("option").prop("selected", false))',
	        ));
	        ?>
			<?php echo $form->error($model,'families'); ?>
        </div>
        <div class="span2">
			<?php echo $form->labelEx($model,'classes'); ?>
	        <?php echo $form->listBox($model,'classes',
			    CHtml::listData(Classes::model()->findAll(array('order'=>'class_name')), 'class_name', 'class_name'),
		        array('multiple'=>'multiple',
			        'class'=>'tall',
			        'onChange'=>'js:FillListValues(this)',
			        'onMouseDown'=>'js:GetCurrentListValues(this)',
		        ));
	        ?>
	        <div class="clearfix"></div><?php echo CHtml::link(Yii::t('admin','Clear All'),'#',array(
		            'class'=>'listboxReset',
					'onclick'=>'js:jQuery($("#'.CHtml::activeId($model,'classes').'").find("option").prop("selected", false))',
		        ));
	        ?>
			<?php echo $form->error($model,'classes'); ?>
        </div>
        <div class="span2">
		    <?php echo $form->labelEx($model,'keywords'); ?>
		    <?php echo $form->listBox($model,'keywords',
		        CHtml::listData(Tags::model()->findAll(array('select'=>'t.tag','order'=>'tag','distinct'=>true)), 'tag', 'tag'),
		        array('multiple'=>'multiple',
			        'class'=>'tall',
			        'onChange'=>'js:FillListValues(this)',
			        'onMouseDown'=>'js:GetCurrentListValues(this)',
		        ));
		    ?>
	        <div class="clearfix"></div><?php echo CHtml::link(Yii::t('admin','Clear All'),'#',array(
		        'class'=>'listboxReset',
		        'onclick'=>'js:jQuery($("#'.CHtml::activeId($model,'keywords').'").find("option").prop("selected", false))',
	        ));
		    ?>
		    <?php echo $form->error($model,'keywords'); ?>
        </div>
<!--        <div class="span2">-->
<!--		    --><?php //echo $form->labelEx($model,'codes'); ?>
<!--		    --><?php //echo $form->listBox($model,'codes',
//		        CHtml::listData(Product::model()->findAllByAttributes(array('web'=>1),array('order'=>'code','limit'=>1000)), 'code', 'code'),
//		        array('multiple'=>'multiple',
//			        'class'=>'tall',
//			        'onChange'=>'js:FillListValues(this)',
//			        'onMouseDown'=>'js:GetCurrentListValues(this)',
//		        ));
//		    ?>
<!--	        --><?php //echo CHtml::link(Yii::t('admin','Clear All'),'#',array(
//		        'class'=>'listboxReset',
//		        'onclick'=>'js:jQuery($("#'.CHtml::activeId($model,'codes').'").find("option").prop("selected", false))',
//	        ));
//	        ?>
<!--		    --><?php //echo $form->error($model,'codes'); ?>
<!--        </div>-->
	</div>
	<br clear="both">

    <div class="row tip">
       <?php echo Yii::t('admin',"Tip: Click in the scrollbar area to avoid accidentally clicking items when switching columns."); ?>

	    <div class="pull-right">
		    <?php $this->widget('bootstrap.widgets.TbButton', array(
		    'htmlOptions'=>array('id'=>'buttonSavePCR'),
		    'label'=>'Save',
		    'type'=>'primary',
		    'size'=>'small',
		    )); ?>
	    </div>
    </div>

	<?php echo $form->hiddenField($model,'id'); ?>

	<?php $this->endWidget(); ?>
</div><!-- form -->

