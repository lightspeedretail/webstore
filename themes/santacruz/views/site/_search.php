<!--<div xmlns="http://www.w3.org/1999/html">-->
	<?php echo CHtml::beginForm(Yii::app()->createUrl('search/results'),'get',array('role'=>'search')); ?>
    <div class="form-group col-xs-9">
        <?php
		$this->widget('bootstrap.widgets.TbTypeahead',array(
			'name'=>'q',
			'id'=>'appendedInputButton',
			'htmlOptions'=>array('class'=>'form-control','autocomplete'=>'off','placeholder'=>Yii::t('global','SEARCH').'...'),
			'options'=>array(
				'minChars'=>2,
				'autoFill'=>false,
				'source'=>'js:function (query, process) {
					$.get("'.Yii::app()->controller->createUrl("search/live").'",{q: query},function(jsdata) {
						response = $.parseJSON(jsdata);
						var data = new Array();
						data.push("'.Yii::app()->controller->createUrl("search/results").'?q="+query+"|search for "+query);
						for(var key in response.options)
							data.push(key+"|"+response.options[key]);
						process(data);
					 });}',
				'onchange'=>'js:function(value) {
                    alert("enter");
					}',
				'highlighter'=>'js:function(item) {
					var parts = item.split("|");
					parts.shift();
					var part = parts.join("|");
					var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
					return parts.join("|").replace(new RegExp("(" + query + ")", "ig"), function ($1, match) {
				        return "<strong>" + match + "</strong>"
				      })
					}',
				'updater'=>'js:function(item) {
					var parts = item.split("|");
					window.location.href=(parts.shift());
					}',
			))); ?>
    </div>
    <div class="visible-xs col-xs-2">
        <?php echo CHtml::imageButton(Yii::app()->theme->baseUrl.'/css/images/spyglass.png',
            array('id'=>'search-btn','class'=>'btn btn-default'));?>
    </div>
<?php echo CHtml::endForm(); ?>


<!--    --><?php //echo CHtml::link('Advanced Search',_xls_site_url('/search'),array('class'=>'btn btn-link')); ?>
<!--</div>-->

