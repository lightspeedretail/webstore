	<?php echo CHtml::beginForm(Yii::app()->createUrl('search/results'),'get'); ?>
		<?php
		$this->widget('bootstrap.widgets.TbTypeahead',array(
			'name'=>'q',
			'id'=>'xlsSearch',
			'htmlOptions'=>array('autocomplete'=>'off','placeholder'=>Yii::t('global','Search')),
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
	<a href="<?php echo $this->createUrl('/search'); ?>" class='advanced'>+</a>
	<?php echo CHtml::endForm(); ?>

