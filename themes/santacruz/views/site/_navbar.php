<?php
/**
 * Created by Shannon Curnew.
 * Date: 10/6/13
 * Time: 11:06 PM
 */
?>
<div id="navigation-main">

<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div id="shoppingcart">
			<div class="navbar-header visible-xs">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#combined-nav">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Categories</a>
			</div>

			<?php
				/* Make subcategory trees*/
				$items = array();

				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM1)) {
					$subItems1 = array();
					$sub1 = Category::model()->findAll('parent=:parent', array('parent'=>Yii::app()->theme->config->CATEGORY_MENU_ITEM1));
					foreach($sub1 as $objSub) {
						array_push($subItems1,
							array('label'=>$objSub->label,
								'url'=>$objSub->link,
							));
					}
				}

				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM2)) {
					$subItems2 = array();
					$sub2 = Category::model()->findAll('parent=:parent', array('parent'=>Yii::app()->theme->config->CATEGORY_MENU_ITEM2));
					foreach($sub2 as $objSub) {
						array_push($subItems2,
							array('label'=>$objSub->label,
								'url'=>$objSub->link,
							));
					}
				}

				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM3)) {
					$subItems3 = array();
					$sub3 = Category::model()->findAll('parent=:parent', array('parent'=>Yii::app()->theme->config->CATEGORY_MENU_ITEM3));
					foreach($sub3 as $objSub) {
						array_push($subItems3,
							array('label'=>$objSub->label,
								'url'=>$objSub->link,
							));
					}
				}

				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM4)) {
					$subItems4 = array();
					$sub4 = Category::model()->findAll('parent=:parent', array('parent'=>Yii::app()->theme->config->CATEGORY_MENU_ITEM4));
					foreach($sub4 as $objSub) {
						array_push($subItems4,
							array('label'=>$objSub->label,
								'url'=>$objSub->link,
							));
					}
				}

				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM1)) {
					array_push($items,
						array('label'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM1)->label,
							'url'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM1)->link,
							'class'=>'dropdown',
							'itemOptions'=>array('class'=>'dropdown'),
							'items'=>$subItems1,
							'linkOptions'   =>  array('class'=>'dropdown-toggle disabled','data-toggle'=>'dropdown'),
							'submenuOptions'=>array('class'=>'dropdown-menu'),
						));
				}

				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM2)) {
					array_push($items,
						array('label'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM2)->label,
							'url'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM2)->link,
							'class'=>'dropdown',
							'itemOptions'=>array('class'=>'dropdown'),
							'items'=>$subItems2,
							'linkOptions'   =>  array('class'=>'dropdown-toggle disabled','data-toggle'=>'dropdown'),
							'submenuOptions'=>array('class'=>'dropdown-menu'),
						));
				}

				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM3)) {
					array_push($items,
						array('label'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM3)->label,
							'url'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM3)->link,
							'class'=>'dropdown',
							'itemOptions'=>array('class'=>'dropdown'),
							'items'=>$subItems3,
							'linkOptions'   =>  array('class'=>'dropdown-toggle disabled','data-toggle'=>'dropdown'),
							'submenuOptions'=>array('class'=>'dropdown-menu'),
						));
				}


				if ((Yii::app()->theme->config->CATEGORY_MENU_ITEM4)) {
					array_push($items,
						array('label'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM4)->label,
							'url'=>Category::model()->findByPk(Yii::app()->theme->config->CATEGORY_MENU_ITEM4)->link,
							'class'=>'dropdown',
							'itemOptions'=>array('class'=>'dropdown'),
							'items'=>$subItems4,
							'linkOptions'   =>  array('class'=>'dropdown-toggle disabled','data-toggle'=>'dropdown'),
							'submenuOptions'=>array('class'=>'dropdown-menu'),
						));
				}

				if (count(CustomPage::model()->toptabs()->findAll())) {
					$items_right = CustomPage::model()->toptabs()->findAll();
				}

				array_push($items_right,
					array('id'=>'cart_link',
						'label'=>'Cart: '.Yii::app()->shoppingcart->totalItemCount,
						'url'=>array('cart/index'),
						'itemOptions'=>array('id'=>'cart_link'),
					));

				$items_combined = array_merge($items,$items_right);

				?>

				<div id="combined-nav" class="collapse navbar-collapse ">
					<?php
						$this->widget('zii.widgets.CMenu',array(
						'items'=>$items_combined,
						'id'=>'',
						'htmlOptions'=>array('class'=>'nav nav-pills nav-justified'),
						)
					); ?>

					<?php

	//                $this->widget('zii.widgets.CMenu',array(
	//                        'items'=>$items_right,
	//                        'id'=>'',
	//                        'htmlOptions'=>array('class'=>'col-sm-6 nav navbar-nav navbar-right pull-right'),
	//                    )
	//                ); ?>

				</div>
            </div>
    </div>
    </div>
</nav>
</div>

