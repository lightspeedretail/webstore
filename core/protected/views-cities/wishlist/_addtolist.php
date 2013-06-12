<?php
/* This is the contents of the wishlist item edit box */
?><div class="login popupsmall">
<?php $popupform=$this->beginWidget('CActiveForm', array(
	'id'=>'WishitemShareForm',
	'enableAjaxValidation'=>false,
));
	echo $popupform->hiddenField($model,'id');
	echo $popupform->hiddenField($model,'qty');
	if ($objProduct->IsMaster)
	{
		echo $popupform->hiddenField($model,'size');
		echo $popupform->hiddenField($model,'color');
	}
	?>

    <div class="row-fluid spaceafter">
		<?php echo $popupform->labelEx($model,'gift_code'); ?>
	    <?php echo $popupform->radioButtonList($model,'gift_code',$model->lists, array('separator'=>'')); ?>
		<?php echo $popupform->error($model,'gift_code'); ?>
    </div>

    <div class="row-fluid shortrow buttons">
        <?php
        echo CHtml::ajaxSubmitButton(Yii::t('global','Submit'),
            CHtml::normalizeUrl(array('wishlist/add','render'=>false)),
            array(
	            'type'=>"POST",
	            'success'=>'js:function(data) {
	                alert(data);
	                $("#WishitemShare").dialog("close");
                 }',
            ),array('id'=>'btnAddWishList')); ?>

    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
