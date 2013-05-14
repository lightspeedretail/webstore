<?php

echo $form->dropDownList($model,'product_size',$model->Sizes,array(
	'id'=>'SelectSize',
	'prompt'=>Yii::t('global','Select {label}...',array('{label}'=>$model->SizeLabel)),
	'ajax' => array(
		'type'=>'POST',
		'dataType'=>'json',
		'data' => 'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),"'.'id'.'": '.$model->id.'}',
		'url'=>CController::createUrl('product/getcolors'),
		'success'=>'js:function(data) {
			data.product_colors = "<option value=\'\'>'.Yii::t('global','Select {label}...',array('{label}'=>$model->ColorLabel)) .'</option>" + data.product_colors;
			$("#SelectColor").empty();
			$("#SelectColor").html(data.product_colors);
			$("#WishlistAddForm_size").val($("#SelectSize option:selected").val());
		}',
	)));


echo $form->dropDownList($model,'product_color',array(),array(
	'id'=>'SelectColor',
	'prompt'=>Yii::t('global','Select {label}...',array('{label}'=>$model->ColorLabel)),
	'ajax' => array(
		'type'=>'POST',
		'dataType'=>'json',
		'data' => 'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),"'.'product_color'.'": $("#SelectColor option:selected").val(),"'.'id'.'": '.$model->id.'}',
		'url'=>CController::createUrl('product/getmatrixproduct'),
		'success'=>'js:function(data) {
			$("#' . CHtml::activeId($model,'FormattedPrice') . '").html(data.FormattedPrice);
			$("#' . CHtml::activeId($model,'FormattedRegularPrice') . '").html(data.FormattedRegularPrice);
			if (data.FormattedRegularPrice != null) $("#' . CHtml::activeId($model,'FormattedRegularPrice') . '_wrap").show();
				else $("#' . CHtml::activeId($model,'FormattedRegularPrice') . '_wrap").hide();
			$("#' . CHtml::activeId($model,'description_long') . '").html(data.description_long);
			$("#' . CHtml::activeId($model,'image_id') . '").html(data.image_id);
			$("#' . CHtml::activeId($model,'InventoryDisplay') . '").html(data.InventoryDisplay);
			$("#' . CHtml::activeId($model,'title') . '").html(data.title);
			$("#' . CHtml::activeId($model,'code') . '").html(data.code);
			$("#photos").html(data.photos);
			if($.isFunction(bindZoom)) bindZoom();
			$("#WishlistAddForm_color").val($("#SelectColor option:selected").val());
		}',
	)));

