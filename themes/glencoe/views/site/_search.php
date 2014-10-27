<span class="search_advanced">
		<a href="<?php echo Yii::app()->createUrl('/search'); ?>"  title="advanced">
			<img src="<?= Yii::app()->theme->baseUrl; ?>/css/images/adv_search.png">
		</a>
</span>
<form class="form-search" style="padding-top: 5px" action="<?php echo Yii::app()->createUrl('search/results'); ?>" method="get">
<?php
//		$this->widget('bootstrap.widgets.TbTypeahead',array(
//			'name'=>'q',
//			'id'=>'xlsSearch',
//			'htmlOptions'=>array('autocomplete'=>'off','placeholder'=>Yii::t('global','SEARCH').'...'),
//			'options'=>array(
//				'minChars'=>2,
//				'autoFill'=>false,
//				'source'=>'js:function (query, process) {
//					$.get("'.Yii::app()->controller->createUrl("search/live").'",{q: query},function(jsdata) {
//						response = $.parseJSON(jsdata);
//						var data = new Array();
//						data.push("'.Yii::app()->controller->createUrl("search/results").'?q="+query+"|search for "+query);
//						for(var key in response.options)
//							data.push(key+"|"+response.options[key]);
//						process(data);
//					 });}',
//				'onchange'=>'js:function(value) {
//                    alert("enter");
//					}',
//				'highlighter'=>'js:function(item) {
//					var parts = item.split("|");
//					parts.shift();
//					var part = parts.join("|");
//					var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
//					return parts.join("|").replace(new RegExp("(" + query + ")", "ig"), function ($1, match) {
//				        return "<strong>" + match + "</strong>"
//				      })
//					}',
//				'updater'=>'js:function(item) {
//					var parts = item.split("|");
//					window.location.href=(parts.shift());
//					}',
//			)));
?>
    <input class="search_box" type="search" name="q" id="xlsSearch" placeholder="<?= Yii::t('global','SEARCH').'...' ?>">
	<input class="right btn-search" type="submit" value="" />
</form>






