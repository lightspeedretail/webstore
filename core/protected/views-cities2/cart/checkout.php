<div class="container">

<?php $form = $this->beginWidget('CActiveForm', array(
		'id'=>'checkout',
		'enableClientValidation'=>true,
		'enableAjaxValidation'=>true,
		'htmlOptions'=>array(
			'onsubmit' => '$("#submitSpinner").show()')
	));

?>

<div id="checkoutHeader"><?= Yii::t('global','Checkout') ?></div>

<?php if(Yii::app()->user->isGuest): ?>
    <div id="customercontact">
	    <div id="CustomerContactBillingInfo">
            <fieldset class="col-sm-10">
                <legend><?php echo Yii::t('checkout','Customer Contact'); ?></legend>

                <div class="row">
                    <div class="col-sm-6">
	                    <?php echo $form->labelEx($model,'contactFirstName'); ?>
	                    <?php echo $form->textField($model,'contactFirstName',
	                               array('onChange' => 'js:if(!$("#'.CHtml::activeId($model,'shippingFirstName').'").val())
	                               $("#'.CHtml::activeId($model,'shippingFirstName').'").val(this.value)' )); ?>
	                    <?php echo $form->error($model,'contactFirstName'); ?>
                    </div>
                    <div class="col-sm-6">
	                    <?php echo $form->labelEx($model,'contactLastName'); ?>
	                    <?php echo $form->textField($model,'contactLastName',
	                               array('onChange' => 'js:if(!$("#'.CHtml::activeId($model,'shippingLastName').'").val())
	                               $("#'.CHtml::activeId($model,'shippingLastName').'").val(this.value)' )); ?>
	                    <?php echo $form->error($model,'contactLastName'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
	                    <?php echo $form->labelEx($model,'contactCompany'); ?>
	                    <?php echo $form->textField($model,'contactCompany'); ?>
	                    <?php echo $form->error($model,'contactCompany'); ?>
                    </div>

                      <div class="col-sm-6">
	                    <?php echo $form->labelEx($model,'contactPhone'); ?>
	                    <?php echo $form->textField($model,'contactPhone'); ?>
	                    <?php echo $form->error($model,'contactPhone'); ?>
                    </div>

                </div>

             

                <div class="row">
                    <div class="col-sm-6">
	                    <?php echo $form->labelEx($model,'contactEmail'); ?>
	                    <?php echo $form->textField($model,'contactEmail'); ?>
	                    <?php echo $form->error($model,'contactEmail'); ?>
                    </div>

	                <?php if(Yii::app()->user->isGuest): ?>
	                    <div class="col-sm-6">
	                        <?php echo $form->labelEx($model,'contactEmail_repeat'); ?>
		                    <?php echo $form->textField($model,'contactEmail_repeat'); ?>
		                    <?php echo $form->error($model,'contactEmail_repeat'); ?>
	                    </div>
		            <?php endif; ?>
                </div>

            </fieldset>
        </div>
    </div>


    <div id="createaccount" class="checkoutpage">
        <fieldset class="col-sm-10">
            <legend><?php echo Yii::t('checkout','Create a Free Account!'); ?></legend>
            <div class="row instructions">
                <?php
                if (_xls_get_conf('REQUIRE_ACCOUNT',0))
                    echo Yii::t('checkout',
                        '<div id="passMsg">Enter a password to create your account.</div>');
                else echo Yii::t('checkout',
                    'To save your information, enter a password here to create an account, or leave blank to check out as a guest.'); ?>
            </div>
            <div class="row">

                <div class="col-sm-6">
                    <?php echo $form->labelEx($model,'createPassword'); ?>
                    <?php echo $form->passwordField($model,'createPassword',
                        array('placeholder'=>"", 'autocomplete'=>"off")); ?>
                    <?php echo $form->error($model,'createPassword'); ?>
                </div>
                <div class="col-sm-6">
                    <?php echo $form->labelEx($model,'createPassword_repeat'); ?>
                    <?php echo $form->passwordField($model,'createPassword_repeat',
                        array('placeholder'=>"", 'autocomplete'=>"off")); ?>
                    <?php echo $form->error($model,'createPassword_repeat'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?php echo $form->checkBox($model,'receiveNewsletter'); ?>
                    <?php echo $form->label($model,'receiveNewsletter'); ?>
                    <?php echo $form->error($model,'receiveNewsletter'); ?>
                </div>
            </div>
        </fieldset>
    </div>


<?php endif; ?>


	<div id="shippingaddress">
		<?php
			//If we have addresses from the address book, display for the user to choose, plus an option to add a new one
			if(count($model->objAddresses)>0): ?>
			<div class="row">
				<fieldset class="col-sm-10">
				        <legend><?php echo Yii::t('checkout','Choose your shipping address'); ?></legend>
							<?php foreach ($model->objAddresses as $objAddress): ?>
								<div class="col-sm-3 myaddress spaceafter">
									<?php echo $form->radioButton($model,'intShippingAddress',
											array('value'=>$objAddress->id,'uncheckValue'=>null,
												  'onclick'=> 'js:$("#CustomerContactShippingAddress").hide();
															   js:$("#btnCalculate").click();')); ?>
									<div class="addresslabel"><?= $objAddress->address_label ?></div>
									<?= $objAddress->formattedblock ?>
								</div>
							<?php endforeach; ?>
					<div class="clearfix"></div>
					<?php echo $form->radioButton($model,'intShippingAddress',array('value'=>0,'uncheckValue'=>null,
								'onclick'=> 'js:$("#CustomerContactShippingAddress").show(); singlePageCheckout.updateShippingAuto();')); ?>
		            <div class="addresslabel"><?= Yii::t('checkout','Or enter a new address'); ?></div>

	            </fieldset>
			</div>
			<?php else: ?>
			<div class="row">
				<div style="display: none">
				<?php echo $form->radioButton($model,'intShippingAddress',array('value'=>0,'uncheckValue'=>null,
					'onclick'=> 'js:$("#CustomerContactShippingAddress").show();')); ?>
	                </div>
			</div>
			<?php endif; ?>

		<div class="row">
			<fieldset class="col-sm-10">
				<div id="CustomerContactShippingAddress">

				        <legend><?php echo Yii::t('checkout','Shipping Address'); ?></legend>


							<div class="row">
	                            <div class="col-sm-6">
							        <?php echo $form->labelEx($model,'shippingFirstName'); ?>
							        <?php echo $form->textField($model,'shippingFirstName'); ?>
							        <?php echo $form->error($model,'shippingFirstName'); ?>
	                            </div>
	                            <div class="col-sm-6">
							        <?php echo $form->labelEx($model,'shippingLastName'); ?>
							        <?php echo $form->textField($model,'shippingLastName'); ?>
							        <?php echo $form->error($model,'shippingLastName'); ?>
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="col-sm-6">
			                        <?php echo $form->labelEx($model,'shippingAddress1'); ?>
			                        <?php echo $form->textField($model,'shippingAddress1'); ?>
			                        <?php echo $form->error($model,'shippingAddress1'); ?>
	                            </div>
	                            <div class="col-sm-6">
			                        <?php echo $form->labelEx($model,'shippingAddress2'); ?>
			                        <?php echo $form->textField($model,'shippingAddress2'); ?>
			                        <?php echo $form->error($model,'shippingAddress2'); ?>
	                            </div>

	                        </div>
	                        <div class="row">
	                            <div class="col-sm-6">
							        <?php echo $form->labelEx($model,'shippingCity'); ?>
							        <?php echo $form->textField($model,'shippingCity'); ?>
							        <?php echo $form->error($model,'shippingCity'); ?>
	                            </div>

	                            <div class="col-sm-6">
							        <?php echo $form->labelEx($model,'shippingCountry'); ?>
							        <?php echo $form->dropDownList($model,'shippingCountry',$model->getCountries(),array(
								        'ajax' => array(
									        'type'=>'POST',
									        'url'=>CController::createUrl('cart/getdestinationstates'),
									        'success'=>'js:function(data){
										        $("#' . CHtml::activeId( $model, 'shippingState') .'").html(data);
										        $("#' . CHtml::activeId( $model, 'shippingProvider') .'").html("");
			                                    $("#' . CHtml::activeId( $model, 'shippingPriority') .'").html(""); }',
									        'data' => 'js:{"'.'country_id'.'": $("#'.CHtml::activeId($model,'shippingCountry').
										        ' option:selected").val()}',
								        ))); ?>
							        <?php echo $form->error($model,'shippingCountry'); ?>
	                            </div>

	                  
	                       
	                        </div>


	                    <div class="row">


	                    	    <div class="col-sm-6">
							        <?php echo $form->labelEx($model,'shippingState'); ?>
							        <?php echo $form->dropDownList($model,'shippingState',$model->getStates('shipping'),array(
		                                'prompt' =>'--',
		                                'ajax' => array(
				                            'type'=>'POST',
			                                'dataType'=>'json',
				                            'url'=>CController::createUrl('cart/settax'),
				                            'success'=>'js:function(data){ singlePageCheckout.updateTax(data) }',
				                            'data' => 'js:{"'.'state_id'.'": $("#'.CHtml::activeId($model,'shippingState').
					                            ' option:selected").val(),
				                                "'.'postal'.'": $("#'.CHtml::activeId($model,'shippingPostal').'").val()}',
			                            ))); ?>
							        <?php echo $form->error($model,'shippingState'); ?>
	                            </div>

	                           	<div class="col-sm-6">
							        <?php echo $form->labelEx($model,'shippingPostal'); ?>
							        <?php echo $form->textField($model,'shippingPostal',array(
			                            'ajax' => array(
				                            'type'=>'POST',
				                            'dataType'=>'json',
				                            'url'=>CController::createUrl('cart/settax'),
				                            'success'=>'js:function(data){ singlePageCheckout.updateTax(data) }',
				                            'data' => 'js:{"'.'state_id'.'": $("#'.CHtml::activeId($model,'shippingState').
					                            ' option:selected").val(),
					                            "'.'postal'.'": $("#'.CHtml::activeId($model,'shippingPostal').'").val()}',
			                            ))); ?>
							        <?php echo $form->error($model,'shippingPostal'); ?>
	                            </div>


						</div>


					 <div class="row">

	                            <div class="col-sm-6">
							        <?php echo $form->labelEx($model,'shippingLabel'); ?>
							        <?php echo $form->textField($model,'shippingLabel'); ?>
							        <?php echo $form->error($model,'shippingLabel'); ?>
	                        	</div>


							<div class="col-sm-6 rememberMe">
								<?php echo $form->checkBox($model,'shippingResidential'); ?>
								<?php echo $form->label($model,'shippingResidential'); ?>
								<?php echo $form->error($model,'shippingResidential'); ?>
							</div>

						</div>



				

			</fieldset>
		</div>
	</div>



<?php //We keep this outside the shipping address block because the rest may be hidden ?>
				<div class="row">
						<div class="sameAsBilling col-sm-9">
							<?php echo $form->checkBox($model,'billingSameAsShipping',array(
								'onclick'=>'js:jQuery($("#CustomerContactBillingAddress")).toggle()',
								'disabled'=>Yii::app()->params['SHIP_SAME_BILLSHIP']
							)); ?>
							<?php echo $form->label($model,'billingSameAsShipping'); ?>
							<?php echo $form->error($model,'billingSameAsShipping'); ?>
						</div>
				</div>



    <div id="billingaddress" class="row">
	    <fieldset class="col-sm-10">
		    <div id="CustomerContactBillingAddress">
			    <?php
	            //If we have addresses from the address book, display for the user to choose, plus an option to add a new one
	            if(count($model->objAddresses)>0): ?>
		            <div class="row">
			            <div class="col-sm-12">
				            <legend><?php echo Yii::t('checkout','Choose your billing address'); ?></legend>
				            <?php foreach ($model->objAddresses as $objAddress): ?>
					            <div class="col-sm-3 myaddress spaceafter">
						            <?php echo $form->radioButton($model,'intBillingAddress',
							            array('value'=>$objAddress->id,'uncheckValue'=>null,
								            'onclick'=> 'js:$("#CustomerContactBillingAddressAdd").hide();')); ?>
						            <div class="addresslabel"><?= $objAddress->address_label ?></div>
						            <?= $objAddress->formattedblock ?>
					            </div>
				            <?php endforeach; ?>
				            <div class="clearfix"></div>
				            <?php echo $form->radioButton($model,'intBillingAddress',array('value'=>0,'uncheckValue'=>null,
					            'onclick'=> 'js:$("#CustomerContactBillingAddressAdd").show();')); ?>
				            <div class="addresslabel"><?= Yii::t('checkout','Or enter a new address'); ?></div>

			            </div>
		            </div>
	            <?php else: ?>
		            <div class="row">
			            <div style="display: none">
				            <?php echo $form->radioButton($model,'intBillingAddress',array('value'=>0,'uncheckValue'=>null,
					            'onclick'=> 'js:$("#CustomerContactBillingAddressAdd").show();')); ?>
			            </div>
		            </div>
	            <?php endif; ?>
	            <div id="CustomerContactBillingAddressAdd">
		            <legend><?php echo Yii::t('checkout','Billing Address'); ?></legend>
		           

	                <div class="row">
	                      <div class="col-sm-6">
			                    <?php echo $form->labelEx($model,'billingAddress1'); ?>
			                    <?php echo $form->textField($model,'billingAddress1'); ?>
			                    <?php echo $form->error($model,'billingAddress1'); ?>
	                        </div>
	                        <div class="col-sm-6">
			                    <?php echo $form->labelEx($model,'billingAddress2'); ?>
			                    <?php echo $form->textField($model,'billingAddress2'); ?>
			                    <?php echo $form->error($model,'billingAddress2'); ?>
	                        </div>
	                </div>

	                <div class="row">
	                    <div class="col-sm-6">
				            <?php echo $form->labelEx($model,'billingCity'); ?>
				            <?php echo $form->textField($model,'billingCity'); ?>
				            <?php echo $form->error($model,'billingCity'); ?>
	                    </div>

	                    <div class="col-sm-6">
				            <?php echo $form->labelEx($model,'billingCountry'); ?>
				            <?php echo $form->dropDownList($model,'billingCountry',$model->getCountries(),array(
				            'ajax' => array(
					            'type'=>'POST',
					            'url'=>CController::createUrl('cart/getdestinationstates'), //url to call
					            'update'=>'#'.CHtml::activeId($model,'billingState'), //selector to update
					            'data' => 'js:{"country_id": $("#'.CHtml::activeId($model,'billingCountry').' option:selected").val()}',
				            ))); ?>
				            <?php echo $form->error($model,'billingCountry'); ?>
	                    </div>
		            </div>

		            <div class="row">
	                    <div class="col-sm-6">
				            <?php echo $form->labelEx($model,'billingState'); ?>
				            <?php echo $form->dropDownList($model,'billingState',
				            $model->getStates('billing'),array('prompt' =>'--')); ?>
				            <?php echo $form->error($model,'billingState'); ?>
	                    </div>
	                    <div class="col-sm-6">
				            <?php echo $form->labelEx($model,'billingPostal'); ?>
				            <?php echo $form->textField($model,'billingPostal'); ?>
				            <?php echo $form->error($model,'billingPostal'); ?>
	                    </div>
	                </div>

	                <div class="row">
			            <div class="col-sm-6">
				            <?php echo $form->labelEx($model,'billingLabel'); ?>
				            <?php echo $form->textField($model,'billingLabel'); ?>
				            <?php echo $form->error($model,'billingLabel'); ?>
			            </div>
		         
			            <div class="col-sm-6">
				            <div class="rememberMe">
					            <?php echo $form->checkBox($model,'billingResidential'); ?>
					            <?php echo $form->label($model,'billingResidential'); ?>
					            <?php echo $form->error($model,'billingResidential'); ?>
				            </div>
			            </div>
		             </div>



				</div>
		    </div>
	    </fieldset>
    </div>


    

	<div id="promocode" class="row">
	    <fieldset class="col-sm-10">
	        <legend><?php echo Yii::t('checkout','Promo Code'); ?></legend>
		    <div class="promoCodeLabel">
		        <?php echo Yii::t('checkout','Enter a Promotional Code here to receive a discount.'); ?>
	        </div>
		    <div class="row">
	            <div id="promoCode" class="col-sm-4" >
					<?php echo $form->textField($model,'promoCode'); ?>
	            </div>
	            <div class="offset2 col-sm-4" >
                    <?php echo CHtml::ajaxButton (Yii::t('checkout','Apply Promo Code'),
                        array('cart/applypromocode'),
			            array('type'=>"POST",
				            'dataType'=>'json',
				            'data'=>'js:jQuery($("#' . CHtml::activeId($model,'promoCode') .'")).serialize()',
			                'success' => 'js:function(data){
			                    if (data.action=="alert") {
			                      alert(data.errormsg);
			                    } else if (data.action=="error") {
			                        alert(data.errormsg);
									$("#' . CHtml::activeId($model,'promoCode') .'_em_").html(data.errormsg).show();
								} else if (data.action=="triggerCalc") {
									$("#btnCalculate").click();
									alert(data.errormsg);
								} else if (data.action=="success") {
									$("#cartItems").html(data.cartitems);
									savedCartScenarios = data.cartitems;
									$("#' . CHtml::activeId($model,'promoCode') .'_em_").html(data.errormsg).show();
									alert(data.errormsg);
									singlePageCheckout.updateShippingAuto();
								}
			                }'),
	                    array('id' => 'CheckoutForm_btnPromoCode')); ?>
	            </div>
	        </div>
		    <div class="row">
	            <div class="col-sm-6" >
					<?php echo $form->error($model,'promoCode'); ?>
	            </div>
	        </div>
	    </fieldset>
	</div>

	<div id="shipping" class="row">
		<fieldset class="col-sm-10">
			<legend><?php echo Yii::t('checkout','Shipping'); ?></legend>
			<div class="col-sm-3">
				<?php echo $form->labelEx($model,'shippingProvider'); ?>
                <div id="shippingProviderRadio">
				<?php echo $form->radioButtonList($model,'shippingProvider',$model->getProviders(),
						array(  'onclick' => 'singlePageCheckout.pickShippingProvider(this.value)',
								'separator'=>'')); ?>
                </div>
				<?php echo $form->error($model,'shippingProvider',null,false,false); ?>
				<div id="shippingSpinner" style="display:none"><?php
					echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?></div>
			</div>

            <div class="col-sm-5">
		        <?php echo $form->labelEx($model,'shippingPriority'); ?>
                <div id='shippingPriorityRadio'>
		        <?php echo $form->radioButtonList($model,'shippingPriority', $model->getPriorities($model->shippingProvider),
	                array(  'onclick' => 'singlePageCheckout.updateCart(this.value)',
	                        'separator'=>'')); ?>
	             </div>
		        <?php echo $form->error($model,'shippingPriority',null,false,false); ?>
            </div>

            <div class="col-sm-3" >
				<input type="button" id="btnCalculate" value="<?= CHtml::encode(Yii::t('checkout', 'Calculate Shipping')); ?>">
				<?php
					Yii::app()->clientScript->registerScript(
						'btnCalculate-script',
						'$("#btnCalculate").click(singlePageCheckout.calculateShipping.bind(singlePageCheckout))',
						CClientScript::POS_LOAD
					);
				?>
            </div>
        </fieldset>
    </div>


	<?php //The contents of the div id=cartItems are refreshed through various AJAX actions such as taxes and shipping ?>
	<div id="checkoutShoppingCart" class="row">
	    <fieldset class="col-sm-10">
	        <legend><?php echo Yii::t('checkout','Shopping Cart'); ?></legend>
		    <div id="cartItems"><?php $this->renderPartial('/cart/_cartitems'); ?></div>
	    </fieldset>
	</div>


	<div id="payment" class="row">
		<fieldset class="col-sm-10">
		    <legend><?= Yii::t('checkout','Payment'); ?></legend>

	        <div class="col-sm-9">
		        <?php echo $form->labelEx($model,'paymentProvider'); ?>
		        <?php echo $form->dropDownList($model,'paymentProvider',$model->GetPaymentModules(),array(
				        'onchange'=>'singlePageCheckout.changePayment(this.value)'
			        )); ?>
		        <?php echo $form->error($model,'paymentProvider'); ?>
	        </div>


			<?php /* If we have payment modules with custom forms, they are rendered here */ ?>
            <div id="Payforms" class="row">
				<?php foreach($paymentForms as $moduleName=>$paymentForm)
						echo $this->renderPartial('/cart/_paymentform',
							array('moduleName'=>$moduleName,'form'=>$paymentForm,'model'=>$model),true);
			    ?>
            </div>

			<?php /* The credit card form renders hidden and will display if a payment module needs it */ ?>
            <div id="CreditCardForm" style="display: none" class="col-sm-10">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
						<?php echo $form->labelEx($model,'cardType'); ?>
						<?php echo $form->dropDownList($model,'cardType',$model->getCardTypes()); ?>
						<?php echo $form->error($model,'cardType'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-5">
						<?php echo $form->labelEx($model,'cardNumber'); ?>
						<?php echo $form->textField($model,'cardNumber',array('autocomplete'=>'off')); ?>
						<?php echo $form->error($model,'cardNumber'); ?>
                    </div>
                    <div class="col-xs-12 col-sm-2">
						<?php echo $form->labelEx($model,'cardCVV'); ?>
						<?php echo $form->textField($model,'cardCVV',array('autocomplete'=>'off')); ?>
						<?php echo $form->error($model,'cardCVV'); ?>
                    </div>
                    <div class="col-xs-12 col-sm-2">
						<?php echo $form->labelEx($model,'cardExpiryMonth'); ?>
						<?php echo $form->dropDownList($model,'cardExpiryMonth',$model->getCardMonths(),array('prompt'=>'--')); ?>
						<?php echo $form->error($model,'cardExpiryMonth'); ?>
                    </div>
                    <div class="col-xs-12 col-sm-2">
						<?php echo $form->labelEx($model,'cardExpiryYear'); ?>
						<?php echo $form->dropDownList($model,'cardExpiryYear',$model->getCardYears(),array('prompt'=>'--')); ?>
						<?php echo $form->error($model,'cardExpiryYear'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
						<?php echo $form->labelEx($model,'cardNameOnCard'); ?>
						<?php echo $form->textField($model,'cardNameOnCard'); ?>
						<?php echo $form->error($model,'cardNameOnCard'); ?>
                    </div>
                </div>


            </div>


		</fieldset>
	</div>

	<div id="checkoutSubmit" class="row">
	    <fieldset class="col-sm-10">
	        <legend><?php echo Yii::t('checkout','Submit your order'); ?></legend>

		        <div class="col-sm-9">
					<div id="commentsNote"><?php echo $form->labelEx($model,'orderNotes'); ?></div>
					<?php echo $form->textArea($model,'orderNotes',array('rows'=>4, 'cols'=>90)); ?>
					<?php echo $form->error($model,'orderNotes'); ?>
			    </div>

	        <div class="rememberMe">
		        <div class="col-sm-9 termsConditions">
					<?php echo $form->checkBox($model,'acceptTerms'); ?>
                    <label>
                        <?php echo Yii::t('checkout',
                            'I hereby agree to the {terms} of shopping with {storename}',
                            array('{storename}'=>_xls_get_conf('STORE_NAME'),
                                '{terms}'=>CHtml::link(Yii::t('global','Terms and Conditions'),$this->createUrl('/terms-and-conditions')))) ?>
                    </label>
					<?php echo $form->error($model,'acceptTerms'); ?>
		        </div>
	        </div>

	    </fieldset>

	</div>

	<div class="clearfix"></div>

	<div id="submitblock">
		<div class="row">
	        <div id="submitSpinner" style="display:none">
		        <?=
		            CHtml::image(
			            Yii::app()->getBaseUrl(true) . '/images/wait_animated.gif'
		            )
		        ?>
	        </div>
			<?=
				CHtml::submitButton(
					Yii::t(
						'forms',
						'Submit'
					),
					array(
						'id' => 'checkoutSubmitButton'
					)
				); ?>
		</div>
	</div>

</div>

<?php
	$this->endWidget();

	$this->renderPartial(
		'ext.wssinglepagecheckout.views.instantiate',
		array(
			'model' => $model
		)
	);
