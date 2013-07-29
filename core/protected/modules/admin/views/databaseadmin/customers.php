<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'setpromo-modal',
	'options'=>array(
		'title'=>'View Wish Lists',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'800',
		'height'=>'353',
		'scrolling'=>'yes',
		'resizable'=>true,
		'position'=>'center',
		'draggable'=>true,
	),
));?>
<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'setpw-modal',
	'options'=>array(
		'title'=>'Reset Password',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'420',
		'height'=>'363',
		'scrolling'=>'no',
		'resizable'=>true,
		'position'=>'center',
		'draggable'=>true,
	),
));?>
<div class="span9">

    <h3>Customers</h3>
	<div class="span8">
	    <div class="editinstructions">
			<?php echo Yii::t('admin','You can make adjustments to customer accounts here. View wishlists, or mark an account to have Administrator Access to Admin panel (use caution!). Hint: click on headings to sort, clicking ID twice shows most recently created at top.'); ?>
	    </div>
    </div>
        <div class="clearfix search">
            <div class="pull-right">
				<?php echo CHtml::beginForm($this->createUrl('databaseadmin/customers'),'get'); ?>
				<?php echo CHtml::textField('q',Yii::app()->getRequest()->getQuery('q'),array('id'=>'xlsSearch','placeholder'=>'SEARCH...','submit'=>'')); ?>
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
			    'name' => 'id',
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'email',
			    'editable' => array(
				    'url' => $this->createUrl('databaseadmin/customers'),
				    'options'=>array(
					    'onblur' => 'cancel',
					    'showbuttons' => true,
					    'success' => 'js:function(response, newValue) {
									if(response!="success")
									{
										$("#alert-box").html(response);
										$("#alert-box").dialog("open");
									}

								}',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'first_name',
			    'editable' => array(
				    'url' => $this->createUrl('databaseadmin/customers'),
				    'options'=>array(
					    'onblur' => 'cancel',
					    'showbuttons' => true,
					    'success' => 'js:function(response, newValue) {
									if(response!="success")
									{
										$("#alert-box").html(response);
										$("#alert-box").dialog("open");
									}

								}',
				    ),
			    )
		    ),
		   	array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'last_name',
			    'editable' => array(
				    'url' => $this->createUrl('databaseadmin/customers'),
				    'options'=>array(
					    'onblur' => 'cancel',
					    'showbuttons' => true,
					    'success' => 'js:function(response, newValue) {
									if(response!="success")
									{
										$("#alert-box").html(response);
										$("#alert-box").dialog("open");
									}

								}',
				    ),
			    )
		    ),

		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'allow_login',
			    'header'=>'Access',
			    'headerHtmlOptions' => array('style' => 'span1'),
			    'editable' => array(
				    'type' => 'select',
				    'url' => $this->createUrl('databaseadmin/customers'),
				    'placement' => 'left',
				    'source' => array(1=>'Active',0=>'Blocked',2=>'ADMIN'),
				    'options'=>array(
					    'onblur' => 'submit',
					    'showbuttons' => false,
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

		    array(
			    'name' => 'wishlist',
			    'header'=>'Wishlists',
			    'type' => 'raw',
			    'value'=>'"<a href=\"#\" id=\"".$data->id."\" class=\"basic\">Wishlists</a>"',
			    'headerHtmlOptions' => array('class' => 'span1'),
		    ),
		    array(
			    'name' => 'resetpw',
			    'header'=>'Reset Password',
			    'type' => 'raw',
			    'value'=>'"<a href=\"#\" id=\"".$data->id."\" class=\"resetpw\">Reset Password</a>"',
			    'headerHtmlOptions' => array('class' => 'span2'),
		    ),
		    //We can't use this yet because LS isn't sending pricing levels for a qty of 1. Boourns.
//		    array(
//			    'class' => 'editable.EditableColumn',
//			    'name' => 'pricing_level',
//			    'editable' => array(
//				    'url' => $this->createUrl('databaseadmin/customers'),
//				    'type' => 'select',
//				    'source'=>CHtml::listData(PricingLevels::model()->findAll(array('order'=>'id')), 'id', 'label'),
//				    'placement' => 'left',
//				    'options'=>array(
//					    'onblur' => 'submit',
//					    'showbuttons' => false,
//				    ),
//			    )
//		    ),

//		    array(
//			    'name' => 'modified',
//		    ),



		),
    ));
	?>


</div>




