
    <div class="row">
        <!-- Breadcrumbs -->
        <div class="col-xs-12 col-sm-12">
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
                'homeLink'=>CHtml::link(Yii::t('global','Home'), array('/site/index')),
                'htmlOptions'=>array('class'=>'breadcrumbs col-sm-offset-1 col-sm-6 pull-left'),
                'separator'=>' <span>/</span> ',
            )); ?>
            <?php endif?>
        </div>
    </div>