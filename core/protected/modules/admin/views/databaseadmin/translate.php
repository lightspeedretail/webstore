<div class="span9">

    <h3><?php echo Yii::t('admin','Translation for {dest}',array('{dest}'=>$model->dest)); ?></h3>
	<div class="span8">
	    <div class="editinstructions">
			<?php echo Yii::t('admin','Click on the phrase to change the foreign language translation. The original appears on the left. Any strings inside {curly braces} must be left intact.'); ?>
	    </div>
    </div>
        <div class="clearfix search">
            <div class="pull-right">
				<?php echo CHtml::beginForm($this->createUrl('databaseadmin/translate'),'get'); ?>
	            <?php echo CHtml::dropDownList('category',$model->category,$model->getCategories(),
					array('submit' => '')); ?>
				<?php echo CHtml::textField('q',Yii::app()->getRequest()->getQuery('q'),array('id'=>'xlsSearch','placeholder'=>'SEARCH...','submit'=>'')); ?>
	            <?php echo CHtml::hiddenField('dest',$model->dest); ?>
				<?php echo CHtml::endForm(); ?>
            </div>
        </div>

	    <?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'id' => 'product-grid',
	    'itemsCssClass' => 'table-bordered',
	    'dataProvider' => $model->searchAdmin(),
		'summaryText' => '',
	    'columns'=>array(
		    array(
			    'name' => 'message',
			    'header'=>'Original',
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'message',
			    'value' => '($data->stringtranslates === null) ? "Error" : $data->getTranslated("'.$model->dest.'",$data->id)',
			    'editable' => array(
				    'title'=>'Translation',
				    'url' => $this->createUrl('databaseadmin/translate?dest='.$model->dest),
				    'options'=>array(
					    'onblur' => 'cancel',
					    'showbuttons' => true,
					    'success' => 'js:function(response, newValue) {
									if(response!="success")
									{
										$("#alert-box").html(response);
										$("#alert-box").dialog("open");
									}
									$.fn.yiiGridView.update("product-grid");

								}',
				    ),
			    )
		    ),

		),
    ));
	?>


</div>




