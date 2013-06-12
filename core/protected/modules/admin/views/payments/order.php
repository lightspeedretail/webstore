<div class="span9">
    <h3><?php echo Yii::t('admin','Set Checkout Display Order'); ?></h3>
    <div class="hero-unit">
        <div class="editinstructions">
		   <p><?php echo Yii::t('admin','This is the display order for Payment modules on checkout. (Note that any restrictions may mean not all options are available on every checkout.)'); ?></p>
            <p><?php echo Yii::t('admin','Grab a name of a module (anywhere in the box) and drag to a new position. The positions are saved immediately.'); ?></p>
        </div>
	<input type="hidden" id="controller" value="payments">
	<ul id="sortable">
		<?php foreach($model as $item): ?>
	        <li id="<?= $item->id?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?= $item->name?></li>
	    <?php endforeach; ?>
	</ul>

	</div>
</div>