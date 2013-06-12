

	<?php foreach($model->wishlists as $wishlist):	?>
		<div class="editorder" xmlns="http://www.w3.org/1999/html">

		<h3><?php echo $wishlist->registry_name; ?></h3>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'enableAjaxValidation'=>true,
			'id'=>'editpending',
		)); ?>

		<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'menu-grid'.$wishlist->id,
			'dataProvider'=>$wishlist->getDataItems(),
			'summaryText'=>'',
			'columns'=>array(
				array(
					'name'=>'qty',
					'sortable'=>false,
					'htmlOptions'=>array("class"=>"span1"),
				),
				array(
					'name'=>'product.title',
					'header'=>'Product',
					'sortable'=>false,
					'htmlOptions'=>array("class"=>"span4"),
					'type'=>'raw',
				),
				array(
					'name'=>'product.code',
					'sortable'=>false,
					'htmlOptions'=>array("class"=>"span1"),
					'type'=>'raw',
				),

				array(
					'name'=>'qty_received',
					'header'=>'Qty R\'cvd',
					'sortable'=>false,
					'htmlOptions'=>array("class"=>"span2"),
				),
				array(
					'name'=>'purchasedBy.fullname',
					'header'=>'Purchased By',
					'sortable'=>false,
					'htmlOptions'=>array("class"=>"span2"),
				),

			),
		)); ?>

	</div>

	<?php $this->endWidget(); ?>
<?php endforeach; ?>