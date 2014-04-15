<h1><?php
	if ($model->id >0)
		echo Yii::t('global','Edit address').": ".$model->address_label;
	else
		echo Yii::t('global','Create a new address book entry');
		?></h1>

<?php
/* Create a new wish list form. We use the Checkout ID to reuse our CSS formatting */
?><div id="checkout">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wishlistdisplay',
	'enableClientValidation'=>true,
	'focus'=>array($model,'registry_name'),
));
	if ($model->id > 0) {
		echo $form->hiddenField($model,'id');
	}
	?>
	<div id="myaccountAddress">
        <div class="row">
	        <div class="col-sm-5">
				<?php echo $form->labelEx($model,'address_label'); ?>
				<?php echo $form->textField($model,'address_label',array('prompt' =>'Home, Work')); ?>
				<?php echo $form->error($model,'address_label'); ?>
	        </div>
	        <div class="col-sm-5 rememberMe">
			      <div>  
			      	<?php echo $form->checkbox($model,'active'); ?>
			        <?php echo $form->labelEx($model,'active'); ?>
			        <?php echo $form->error($model,'active'); ?><br>
			      </div>
			       <div> 
			        <?php echo $form->checkbox($model,'makeDefaultBilling'); ?>
			        <?php echo $form->labelEx($model,'makeDefaultBilling'); ?>
			        <?php echo $form->error($model,'makeDefaultBilling'); ?><br>
	 			</div>
				<div id="defaultShippingAddress"> 
			        <?php echo $form->checkbox($model,'makeDefaultShipping'); ?>
			        <?php echo $form->labelEx($model,'makeDefaultShipping'); ?>
			        <?php echo $form->error($model,'makeDefaultShipping'); ?>
	   			</div>
	        </div>
        </div>

	    <div class="row">
            <div class="col-sm-5">
				<?php echo $form->label($model,'first_name'); ?>
				<?php echo $form->textField($model,'first_name'); ?>
				<?php echo $form->error($model,'first_name'); ?>
            </div>
           <div class="col-sm-5">
				<?php echo $form->label($model,'last_name'); ?>
				<?php echo $form->textField($model,'last_name'); ?>
				<?php echo $form->error($model,'last_name'); ?>
            </div>
	    </div>



        <div class="row">
            <div class="col-sm-5">
				<?php echo $form->labelEx($model,'address1'); ?>
				<?php echo $form->textField($model,'address1'); ?>
				<?php echo $form->error($model,'address1'); ?>
			</div>
			<div class="col-sm-5">
				<?php echo $form->labelEx($model,'address2'); ?>
				<?php echo $form->textField($model,'address2'); ?>
				<?php echo $form->error($model,'address2'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">
				<?php echo $form->labelEx($model,'city'); ?>
				<?php echo $form->textField($model,'city'); ?>
				<?php echo $form->error($model,'city'); ?>
            </div>
      
            <div class="col-sm-5">
				<?php echo $form->labelEx($model,'country_id'); ?>
				<?php echo $form->dropDownList($model,'country_id',$checkout->getCountries(),array(
				'ajax' => array(
					'type'=>'POST',
					'url'=>CController::createUrl('cart/getdestinationstates'), //url to call
					'update'=>'#'.CHtml::activeId($model,'state_id'), //selector to update
					'data' => 'js:{"country_id": $("#'.CHtml::activeId($model,'country_id').' option:selected").val()}',
				))); ?>
				<?php echo $form->error($model,'country_id'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">
				<?php echo $form->labelEx($model,'state_id'); ?>
				<?php echo $form->dropDownList($model,'state_id',
					$checkout->getStates('myaccount',$model->country_id),array('prompt' =>'--')); ?>
				<?php echo $form->error($model,'state_id'); ?>
            </div>
            <div class="col-sm-5">
				<?php echo $form->labelEx($model,'postal'); ?>
				<?php echo $form->textField($model,'postal'); ?>
				<?php echo $form->error($model,'postal'); ?>
            </div>
        </div>

	    <div class="row">
		    <div class="col-sm-5 rememberMe">
				<?php echo $form->checkbox($model,'residential'); ?>
				<?php echo $form->labelEx($model,'residential'); ?>
				<?php echo $form->error($model,'residential'); ?>
		    </div>
		</div>

	    <div class="row">
	        <div class="col-sm-5">
				<?php echo $form->labelEx($model,'phone'); ?>
				<?php echo $form->textField($model,'phone'); ?>
				<?php echo $form->error($model,'phone'); ?>
	        </div>
	    </div>


        <div class="col-sm-9 submitblock" >
			<?php echo CHtml::submitButton('Submit', array('id'=>'btnSubmit'));  ?>
        </div>
	</div>


	<?php $this->endWidget(); ?>
</div><!-- form -->
