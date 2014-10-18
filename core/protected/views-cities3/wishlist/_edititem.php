<?php
/* This is the contents of the wishlist item edit box */
?><div class="login popup">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'WishlistEditForm',
	'enableAjaxValidation'=>true,
	'focus'=>array($model,'qty'),
));
	echo $form->hiddenField($model,'code');
	echo $form->hiddenField($model,'id');
	?>
	<?php echo $form->errorSummary($model); ?>
        <div class="row shortrow">
	        <div class="span2">
				<?php echo $form->labelEx($model,'qty'); ?>
				<?php echo $form->textField($model,'qty'); ?>
				<?php echo $form->error($model,'qty'); ?>
	        </div>
            <div class="span2">
				<?php echo $form->label($model,'priority'); ?>
	            <?php echo $form->dropDownList($model,'priority',$model->getPriorities()); ?>
				<?php echo $form->error($model,'priority'); ?>
	        </div>
	    </div>
        <div class="shortrow">
		        <?php echo $form->label($model,'comment'); ?>
		        <?php echo $form->textArea($model,'comment'); ?>
				<?php echo $form->error($model,'comment'); ?>
        </div>

        <div class="shortrow buttons">
	        <?php
	        echo CHtml::ajaxSubmitButton(Yii::t('global','Update'),
	            CHtml::normalizeUrl(array('wishlist/edititem','render'=>false)),
	            array(
		            'type'=>"POST",
		            'dataType'=>'json',
		            'success'=>'js:function(data) {
		                if (data.status=="success") {
	                        $("#qty-"+data.id).html(data.qty);
                            $("#WishitemEdit").dialog("close");
                            if (data.reload) location.reload();
                        } else alert(data.errormsg);
                     }',
	            ),array('id'=>'btnWishList','name'=>'btnWishList')); ?>

        </div>
        <div class="shortrow buttons">
		<?php
		echo CHtml::ajaxSubmitButton(Yii::t('global','DELETE THIS ITEM'),
			CHtml::normalizeUrl(array('wishlist/deleteitem','render'=>false)),
			array(
				'type'=>"POST",
				'dataType'=>'json',
				'success'=>'js:function(data) {
		                if (data.status=="success") {
                            $("#WishitemEdit").dialog("close");
                            if (data.reload) location.reload();
                        } else alert(data.errormsg);
                     }',
			),array('id'=>'btnWishListDelete')); ?>

    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
