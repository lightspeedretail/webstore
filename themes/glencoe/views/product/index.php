<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'product'
));

//Note we used form-named DIVs with the Yii CHtml::tag() command so our javascript can update fields when choosing matrix items
?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        	<div id="product_details">
        		<div>
                    <div class="productheader">
                        <h1 class="title"><?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'title')),$model->Title); ?></h1>
                    </div>

        	        <div class="col-sm-6 col-md-4" style="text-align: center">
        		        <div id="photos">
        			        <?= $this->renderPartial('/product/_photos', array('model'=>$model), true); ?>
        	            </div>
        	            <div class="row">
        		            <?php if(_xls_get_conf('SHOW_SHARING'))
        			            echo $this->renderPartial('/site/_sharing_tools',array('product'=>$model),true); ?>
        	            </div>
        	        </div>

        	        <div class="col-sm-6 col-md-8">
                        <div class="jumbotron">
                            <div class="row">
                                <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'FormattedPrice'),'class'=>'price'),$model->Price); ?>

                                <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'FormattedRegularPrice').'_wrap','class'=>'price_reg',
                                        'style'=>(!$model->SlashedPrice ? 'display:none' : '')),
                                    Yii::t('product', 'Regular Price').": ".
                                    CHtml::tag('span',array('id'=>CHtml::activeId($model,'FormattedRegularPrice'),
                                        'class'=>'price_slash'),$model->SlashedPrice));
                                ?>
                            </div>
                            <div class="row" style="padding-top: 22px">
                                <?php if(_xls_get_conf('SHOW_FAMILY') && isset($model->family)): ?>
                                    <div class="col-xs-5 col-sm-5 col-md-4 codelabel">By: </div>
                                    <div class="col-xs-7 col-sm-7 col-md-8 code"><?= CHtml::link($model->family->family,$model->family->Link) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <?php if (_xls_get_conf('SHOW_TEMPLATE_CODE', true)): ?>
                                    <div class="col-xs-5 col-sm-5 col-md-4 codelabel">Product Code: </div>
                                    <div class="col-xs-7 col-sm-7 col-md-8 code"><?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'code')),$model->code); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-xs-5 col-sm-5 col-md-4 codelabel">Availability: </div>
                                <div class="col-xs-7 col-sm-7 col-md-8 code"><?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'InventoryDisplay'),'class'=>'stock'),
                                        $model->InventoryDisplay); ?></div>
                            </div>

                            <?php if ($model->IsMaster): ?>
                                <div class="row" style="padding-left: 18px; padding-top: 15px">
	                                <?php $this->widget('ext.wsmenu.wsmatrixselector', array(
		                                'form'=> $form,
		                                'model'=> $model
	                                )); //matrix chooser ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!_xls_get_conf('DISABLE_CART', false)): ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-4 btnwishlist">
                                    <?php if (_xls_get_conf('ENABLE_WISH_LIST'))
                                        echo CHtml::tag('div',array(
                                            'id'=>'addToWishList',
                                            'data-name'=>'gift',
                                            'data-size'=>'24',
                                            'data-color'=>'#ffffff',
                                            'data-hovercolor'=>'#ffffff',
                                            'data-eventtype'=>'click',
                                            'class'=>_xls_get_conf('SHOW_QTY_ENTRY') ? 'livicon wishlist showqty' : 'livicon wishlist',
                                            'onClick'=>CHtml::ajax(array(
                                                'url'=>array('wishlist/add'),
                                                'data'=>array('id'=>$model->id,
                                                    'qty'=>'js:$("#'.CHtml::activeId($model,'intQty').'").val()',
                                                    'size'=>'js:$("#SelectSize option:selected").val()',
                                                    'color'=>'js:$("#SelectColor option:selected").val()'),
                                                'type'=>'POST',
                                                'success' => 'function(data) {
                                            if (data=="multiple")
                                                $("#WishitemShare").dialog("open");
                                             else alert(data); }'
                                            )),
                                        ),CHtml::link(''.Yii::t('product', 'Add to Wish List'), ''));
                                    ?>
                                    </div>

                                    <div class="productQty col-xs-12 col-sm-12 col-md-2" <?php echo (_xls_get_conf('SHOW_QTY_ENTRY') ? '' : 'style="display:none"'); ?>>
                                        <?php echo $form->labelEx($model,'intQty'); ?>
                                        <?php echo $form->textField($model,'intQty'); ?>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-4 btnaddcart">
                                    <?= CHtml::tag('i',array(
                                        'class'=>_xls_get_conf('SHOW_QTY_ENTRY') ? 'livicon addcart_ico showqty addedtocart addcart' : 'livicon addcart_ico addedtocart addcart',
                                        'id'=>'addToCart',
                                        'data-name'=>'shopping-cart',
                                        'data-size'=>'24',
                                        'data-color'=>'#ffffff',
                                        'data-hovercolor'=>'#ffffff',
                                        'data-eventtype'=>'click',
                                        'onClick'=>CHtml::ajax(array(
                                            'url'=>array('cart/AddToCart'),
                                            //If we are viewing a matrix product, Add To Cart needs to pass selected options, otherwise just our model id
                                            'data'=>($model->IsMaster ?
                                                'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),
        							            "'.'product_color'.'": $("#SelectColor option:selected").val(),
        							            "'.'id'.'": '.$model->id.',
        							            "'.'cart'.'": "_topcart",
        							            "'.'qty'.'": $("#'.CHtml::activeId($model,'intQty').'").val() }'
                                                : array('id'=>$model->id,
                                                    'cart'=>'_topcart',
                                                    'qty'=>'js:$("#'.CHtml::activeId($model,'intQty').'").val()')),
                                            'type'=>'POST',
                                            'dataType'=>'json',
                                            'success' => 'js:function(data){
        				                    if (data.action=="alert") {
        				                      alert(data.errormsg);
        									} else if (data.action=="success") {
        										animateAddToCart("#shoppingcart");
        										'.(_xls_get_conf('AFTER_ADD_CART') ?
                                                'window.location.href="'.$this->createUrl("/cart").'"' :
                                                '$("#shoppingcart").html(data.shoppingcart);').'
        									}}'
                                        )),
                                    ),CHtml::link(''.Yii::t('product', 'Add to Cart'), ''));
                                    ?>
                                    </div>

                                </div>

                                <!-- <hr class="emboss"> -->
                            <div class="clearfix visible-xs"></div>
                                <div id="productdescription" class="hidden-sm col-xs-12">
                                    <?php if ($model->WebShortDescription || $model->WebLongDescription): ?>
                                    <h2><?= Yii::t('product', 'Description')?></h2>
                                    <?php if (_xls_get_conf('USE_SHORT_DESC'))
                                        echo CHtml::tag('div',
                                            array('id'=>CHtml::activeId($model,'description_short'),'class'=>'description'),
                                            $model->WebShortDescription);
                                    ?>
                                    <br>
                                    <?= CHtml::tag('div',
                                        array('id'=>CHtml::activeId($model,'description_long'),'class'=>'description'),
                                        $model->WebLongDescription); ?>
                                    <?php endif; ?>
                                </div>



                                <div class="row">
                                    <div class="col-sm-11">
                                        <?php
                                        $this->widget('zii.widgets.grid.CGridView', array(
                                            'id' => 'autoadd',
                                            'dataProvider' => $model->autoadd(),
                                            'showTableOnEmpty'=>false,
                                            'selectableRows'=>0,
                                            'emptyText'=>'',
                                            'summaryText' => Yii::t('global',
                                                'The following related products will be added to your cart automatically with this purchase:'),
                                            'hideHeader'=>false,
                                            'columns' => array(
                                                'SliderImageTag:html',
                                                'TitleTag:html',
                                                'Price',
                                            ),
                                        ));
                                        ?>
                                    </div>
                                </div>

                            <?php endif; ?>
                        </div>
                    </div><!-- end of top row -->
                    <div class="clearfix"></div>
                    <div id="productdescription" class="visible-sm hidden-md hidden-lg hidden-xs">
                        <?php if ($model->WebShortDescription || $model->WebLongDescription): ?>
                        <h2><?= Yii::t('product', 'Description')?></h2>
                        <?php if (_xls_get_conf('USE_SHORT_DESC'))
                            echo CHtml::tag('div',
                                array('id'=>CHtml::activeId($model,'description_short'),'class'=>'description'),
                                $model->WebShortDescription);
                        ?>
                        <br>
                        <?= CHtml::tag('div',
                            array('id'=>CHtml::activeId($model,'description_long'),'class'=>'description'),
                            $model->WebLongDescription); ?>
                        <?php endif; ?>
                    </div>


                    <div>
                        <div class="facebook_comments">
                            <?php if(_xls_facebook_login() && _xls_get_conf('FACEBOOK_COMMENTS')): ?>
                                <h2><?= Yii::t('product', 'Comments about this product')?></h2>
                                <?php  $this->widget('ext.yii-facebook-opengraph.plugins.Comments', array(
                                    'href' => $this->CanonicalUrl,
                                )); ?>
                            <?php endif; ?>
                        </div>
                    </div><!-- end of middle row -->
                </div>
        		<div class="clearfix"></div>

                <div class="container">
            	    <div class="row">
            	        <div class="col-sm-12">
            				<?php
            					$this->widget('ext.JCarousel.JCarousel', array(
            						'dataProvider' => $model->related(),
            						'thumbUrl' => '$data->SliderImage',
            						'imageUrl' => '$data->Link',
            						'summaryText' => Yii::t('global',
            							'Other items you may be interested in:'),
            						'emptyText'=>'',
            						'titleText' => '$data->Title',
            						'captionText' => '$data->Title."<br>"._xls_currency($data->Price)',
            						'target' => 'do-not-delete-this',
            						//'wrap' => 'circular',
            						'visible' => true,
            						'skin' => 'slider',
            						'clickCallback'=>'window.location.href=itemSrc;'
            					));
            				?>
            	        </div>
                    </div>
        	    </div>

            </div>
        </div>
	</div>

<?php $this->endWidget();

/* This is our add to wish list box, which remains hidden until used */
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'WishitemShare',
    'options'=>array(
	    'title'=>Yii::t('wishlist','Add to Wish List'),
	    'autoOpen'=>false,
	    'modal'=>'true',
	    'width'=>'330',
	    'height'=>'250',
	    'scrolling'=>'no',
	    'resizable'=>false,
	    'position'=>'center',
	    'draggable'=>false,
    ),
));
echo $this->renderPartial('/wishlist/_addtolist', array('model'=>$WishlistAddForm,'objProduct'=>$model) ,true);
$this->endWidget('zii.widgets.jui.CJuiDialog');
