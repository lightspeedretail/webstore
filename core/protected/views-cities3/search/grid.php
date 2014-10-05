<div id="gridheader">

		<?php if (_xls_get_conf('ENABLE_CATEGORY_IMAGE', 0) && isset($this->category) && $this->category->ImageExist): ?>
	    <div id="category_image">
	        <img src="<?= $this->category->CategoryImage; ?>"/>
	    </div>
		<?php endif; ?>

	    <h1><?php echo $this->pageHeader; ?></h1>

	    <div class="subcategories">
			<?php
			if(isset($this->subcategories) && (count($this->subcategories) > 0)) {
				echo _sp("Subcategories").':';
				foreach ($this->subcategories as $item)
					echo CHtml::link(trim($item['label']), $item['link']);
			}
			?>
	    </div>

		<?php if(isset($this->custom_page_content)): ?>
		    <div id="custom_content">
				<?php echo $this->custom_page_content; ?>
		    </div>
		<?php endif; ?>


	</div>


<?php if (count($model) > 0): ?>


		<?php
		$ct=-1;
		foreach($model as $objProduct):


			if ($objProduct->rowBookendFront)
				echo '<div class="row-fluid">';

			//Our product cell is a nested div, containing the graphic and text label with clickable javascript
			echo CHtml::tag('div',array(
		        'class'=>'product_cell span'.(12/$this->gridProductsPerRow)),

					CHtml::tag('div',array(
				    'class'=>'product_cell_graphic',
					'onclick'=>'window.location.href=\''.$objProduct->Link.'\''),
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
			if ($objProduct->rowBookendBack)
				echo '</div>';

		endforeach; ?>

		<div class="clearfix"></div>

		<div id="paginator" class="span12">
			<?php $this->widget('CLinkPager', array(
				'id'=>'pagination',
				'currentPage'=>$pages->getCurrentPage(),
				'itemCount'=>$item_count,
				'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
				'maxButtonCount'=>5,
				'firstPageLabel'=> '<< '.Yii::t('global','First'),
				'lastPageLabel'=> Yii::t('global','Last').' >>',
				'prevPageLabel'=> '< '.Yii::t('global','Previous'),
				'nextPageLabel'=> Yii::t('global','Next').' >',
				'header'=>'',
				'htmlOptions'=>array('class'=>'pagination'),
				)); ?>
        </div>


<?php endif;

