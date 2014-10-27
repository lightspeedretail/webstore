<div class='container-fluid product-grid'>
	<div class="subcategories">
		<?php
		if(isset($this->subcategories) && (count($this->subcategories) > 0)) {
			echo Yii::t('global','Subcategories').': ';
			$this->widget('zii.widgets.CMenu',array(
				'items'=>$this->subcategories,
				'htmlOptions'=>array('class'=>'nav nav-pills')
			));
		}
		?>
	</div>

	<?php if(isset($this->custom_page_content)): ?>
		<div id="custom_content">
			<?php echo $this->custom_page_content; ?>
		</div>
	<?php endif; ?>

	<?php if (count($model) > 0): ?>


		<?php
		$ct=-1;
		foreach($model as $objProduct):

			//Our product cell is a nested div, containing the graphic and text label with clickable javascript
			echo CHtml::tag('div',array(
		        'class'=>'product_cell'),

					CHtml::tag('div',array(
				    'class'=>'product_cell_graphic',
					'onclick'=>'window.location.href=\''.$objProduct->Link.'\''
						),
			        CHtml::link(CHtml::image($objProduct->ListingImage,$objProduct->Title), $objProduct->Link)).

					CHtml::tag('div',array(
					    'class'=>'product_cell_label',
						'onclick'=>'window.location.href=\''.$objProduct->Link.'\''
				        ),
				        CHtml::link(_xls_truncate($objProduct->Title , 50), $objProduct->Link).
					        CHtml::tag('span',array('class'=>'product_cell_price_slash'),$objProduct->SlashedPrice).
					        CHtml::tag('span',array('class'=>'product_cell_price'),$objProduct->Price)
		            )
				);

		endforeach; ?>

		<div class="clearfix"></div>

		<div id="paginator">
			<?php $this->widget('CLinkPager', array(
				'id'=>'pagination',
				'currentPage'=>$pages->getCurrentPage(),
				'itemCount'=>$item_count,
				'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
				'maxButtonCount'=>3,
				'firstPageLabel'=> Yii::t('global','First'),
				'lastPageLabel'=> Yii::t('global','Last'),
				'prevPageLabel'=> Yii::t('global','Previous'),
				'nextPageLabel'=> Yii::t('global','Next'),
				'header'=>'',
				'htmlOptions'=>array('class'=>'pagination'),
				)); ?>
        </div>

	<?php endif; ?>
</div>

