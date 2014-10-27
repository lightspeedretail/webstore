<!--<li class="list-group-item-heading" data-toggle="collapse" data-target="#orl">-->
<!--	--><?php //echo CHtml::link(Yii::t('global','Order Lookup'),'#'); ?>
<!--	<ul class="collapse" id="orl">-->
<!--		<li class="list-group-item-heading" data-toggle="collapse" data-target="#orl">-->
		<li class="list-group-item-heading" id="orl">
			<?php $this->widget("application.extensions.wsborderlookup.wsborderlookup",array()); ?>
		</li>
<!--	</ul>-->
<!--</li>-->
