<?php $this->layout='//layouts/column1'; ?>

<div id="product-index" class="container">
<div class="row">
    <?php if(isset($this->breadcrumbs)):?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links'=>$this->breadcrumbs,
                        'homeLink'=>CHtml::link(Yii::t('global','Home'),$this->createAbsoluteUrl("/")),
                        'htmlOptions'=>array('class'=>'breadcrumb col-sm-6 pull-left'),
                        'separator'=>' // '
                        )); ?><!-- breadcrumbs -->
    <?php endif?>
</div>

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'product',
    'htmlOptions'=>array('role'=>'form','class'=>'form-horizontal',),
));

//Note we used form-named DIVs with the Yii CHtml::tag() command so our javascript can update fields when choosing matrix items
?>
	<div id="product-details">
			<div id="photos" class="col-sm-6">
				<?= $this->renderPartial('/product/_photos', array('model'=>$model), true); ?>
				<br/>
				<!--<div class="row-fluid col-sm-6">-->
					<?php if(_xls_get_conf('SHOW_SHARING'))
						echo $this->renderPartial('/site/_sharing_tools',array('product'=>$model),true); ?>
				<!--</div>-->
			</div>

        <div id="product-info" class="col-sm-6">

            <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'title'),'class'=>'h1'),$model->Title); ?>

            <div id="add-fields" class="row">
                <?php if (_xls_get_conf('SHOW_TEMPLATE_CODE', true)): ?>
                    <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'code'),'class'=>'code col-sm-6'),$model->code); ?>
                <?php endif; ?>
                <?php if(_xls_get_conf('SHOW_FAMILY') && isset($model->family)): ?>
                    <h2 class="brand col-sm-6">By: <?= CHtml::link($model->family->family,$model->family->Link) ?></h2>
                <?php endif; ?>
            </div>

            <div id="regular-price" class="row">
                <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'FormattedPrice'),'class'=>'col-sm-3 h1 price'),$model->Price); ?>
            </div>

            <?php if (!$model->SlashedPrice || ($model->SlashedPrice == $model->Price) /*&& !$model->IsMaster*/): ?>
            <div id="slashed-price" class="row">
                <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'FormattedRegularPrice').'_wrap','class'=>'price_reg col-sm-6'),
//                        'style'=>(!$model->SlashedPrice ? 'display:none' : '')),
                    Yii::t('product', 'Regular').": ".
                        CHtml::tag('span',array('id'=>CHtml::activeId($model,'FormattedRegularPrice'),
                            'class'=>'price_slash'),$model->SlashedPrice));
                ?>
            </div>
            <?php endif; ?>

            <div id="web-long-description" class="row">
                    <?= CHtml::tag('div',
                        array('id'=>CHtml::activeId($model,'description_long'),'class'=>'col-sm-12'),
                        $model->WebLongDescription); ?>
            </div>

            <?php if (_xls_get_conf('USE_SHORT_DESC')): ?>
                <div id="web-short-description" class="row">
                <?php echo CHtml::tag('div',
                    array('id'=>CHtml::activeId($model,'description_short'),'class'=>'col-sm-12 list-unstyled'),
                    $model->WebShortDescription);
                ?>
                </div>
            <?php endif; ?>

            <div id="inventory-row" class="row">
                <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'InventoryDisplay'),'class'=>'stock h2 col-sm-6'),
                    $model->InventoryDisplay); ?>
            </div>

            <?php if ($model->IsMaster): ?>
                <div class="row">
                    <div class="clearfix"></div>
	                <?php $this->widget('ext.wsmenu.wsmatrixselector', array(
		                'form'=> $form,
		                'model'=> $model
	                )); //matrix chooser ?>
                </div>
            <?php endif; ?>

            <div id="qty-row" class="row form-group intQty" <?php echo (_xls_get_conf('SHOW_QTY_ENTRY') ? '' : 'style="display:none"'); ?>>
                <?php echo $form->labelEx($model,'intQty', array('class'=>'col-xs-2 col-sm-1 h2 control-label')); ?>
                <div class="col-xs-2 col-sm-2">
                    <?php echo $form->numberField($model,'intQty', array('class'=>'form-control', 'type'=>'number')); ?>
                </div>
            </div>

	            <?php if (!_xls_get_conf('DISABLE_CART', false)): ?>
		            <div id="add-row" class="row">
                        <?= CHtml::tag('div',array(
                            'class'=>'col-sm-4 h2 btn btn-primary',
                            'id'=>'addToCart',
                            'onClick'=>CHtml::ajax(array(
                                    'url'=>array('cart/AddToCart'),
                                    //If we are viewing a matrix product, Add To Cart needs to pass selected options, otherwise just our model id
                                    'data'=>($model->IsMaster ?
                                            'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),
                                    "'.'product_color'.'": $("#SelectColor option:selected").val(),
                                    "'.'id'.'": '.$model->id.',
                                    "'.'cart'.'": "_cartnav",
                                    "'.'qty'.'": $("#'.CHtml::activeId($model,'intQty').'").val() }'
                                            : array('id'=>$model->id,
                                                'cart'=>'_cartnav',
                                                'qty'=>'js:$("#'.CHtml::activeId($model,'intQty').'").val()')),
                                    'type'=>'POST',
                                    'dataType'=>'json',
                                    'success' => 'js:function(data){
                                if (data.action=="alert") {
                                  alert(data.errormsg);
                                } else if (data.action=="success") {
                                	animateAddToCart("#cart_link");
                                    '.(_xls_get_conf('AFTER_ADD_CART') ?
                                            'window.location.href="'.$this->createUrl("/cart/index").'"' :
                                            '$("#shoppingcart").html(data.shoppingcart);').'
                                }}'
                                )),
                        ),CHtml::link(Yii::t('product', 'Add to Cart'), '#'));
                        ?>

                        <?php if (_xls_get_conf('ENABLE_WISH_LIST'))
				            echo CHtml::tag('div',array(
					            'id'=>'addToWishList',
					            'class'=>'h2 col-sm-offset-1 col-sm-4 btn btn-link',
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
				            ),CHtml::link(Yii::t('product', 'Add to Wish List'), '#'));
			            ?>

                    </div>
            </div>
        </div>
        <div class="clearfix"></div>
	<?php
	$related = $model->related()->data;
	if ($related) : ?>
    <div id="upsell-row" class="row">
            <div class="col-sm-2 center-block">
                <h1>Related</h1>

                <div id="relatedCarousel" class="carousel">
                    <!--    <ol class="carousel-indicators">-->
                    <!--        <li data-target="#relatedCarousel" data-slide-to="0" class="active"></li>-->
                    <!--    </ol>-->
                    <div class="carousel-inner">

                        <?php
                        foreach($related as $index=>$objRelated): ?>
                        <?php if ($index == 0): ?>
                        <div class="item active">
                            <?php else: ?>
                            <div class="item">
                                <?php endif; ?>
                                <?=CHtml::link(CHtml::image($objRelated->SliderImage),$objRelated->Link); ?>
<!--                                <div class="carousel-caption">-->
<!--                                    --><!--<?//= CHtml::link($objRelated->title, $objRelated->Link) ?>-->
<!--                                </div>-->
                            </div>

                            <?php endforeach; ?>

                        </div>

                        <a class="left carousel-control" href="#relatedCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="right carousel-control" href="#relatedCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </div>
                </div>
			            <div class="col-sm-6 pull-right">
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
	</div><!-- end of top row -->
 	<?php endif; ?>
<div class="clearfix"></div>

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


<?php $this->endWidget(); ?>

<?php
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
    ?>
</div>

