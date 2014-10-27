<?php
/**
 * Created by Shannon Curnew.
 * Date: 10/16/13
 * Time: 4:05 PM
 */
$this->layout='//layouts/column1';
?>
    <div id="breadcrumb-bar" class="row">
        <div class="col-xs-12 col-sm-4 pull-right">
            <?php echo $this->renderPartial("/site/_search",array(),true); ?>
        </div>

        <div
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links'=>$this->breadcrumbs,
                    'homeLink'=>CHtml::link(Yii::t('global','Home'),$this->createAbsoluteUrl("/")),
                    'htmlOptions'=>array('class'=>'breadcrumb col-sm-6 pull-left'),
                    'separator'=>' // '
                    )); ?><!-- breadcrumbs -->
        <?php endif?>
        </div>
    </div>
    <div class="row">
        <?php if (_xls_get_conf('ENABLE_CATEGORY_IMAGE', 0) && isset($this->category) && $this->category->ImageExist): ?>
            <div id="category_image">
                <img src="<?= $this->category->CategoryImage; ?>"/>
            </div>
        <?php endif; ?>



        <ul class="col-sm-offset-1 nav nav-pills">
                <li class="h1"><?php echo $this->pageHeader; ?></li>
                <?php
                if(isset($this->subcategories) && (count($this->subcategories) > 0)):
                    foreach ($this->subcategories as $item)
                        echo '<li class="h3 subcategories">'.CHtml::link(trim($item['label']), $item['link']);
                endif;
                ?>
            </ul>


        <?php if(isset($this->custom_page_content)): ?>
            <div id="custom_content">
                <?php echo $this->custom_page_content; ?>
            </div>
        <?php endif; ?>

    </div>

<?php
if (count($model) > 0): ?>

    <div id="pagination">
        <div id="page-line" class="col-sm-10 hidden-xs">
            <hr>
        </div>
        <div id="paginator" class="col-sm-2 center-bloc">

            <?php $this->widget('CLinkPager', array(
                'id'=>'pagination',
                'currentPage'=>$pages->getCurrentPage(),
                'itemCount'=>$item_count,
                'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
                'maxButtonCount'=>5,
                'firstPageLabel'=> '<<',
                'lastPageLabel'=> '>>',
                'prevPageLabel'=> '<',
                'nextPageLabel'=> '>',
                'header'=>'',
                'htmlOptions'=>array('class'=>'pagination'),
            )); ?>
        </div>
    </div>

    <div class="container">
<?php

    foreach($model as $index=>$objProduct): ?>
<!--        Our product cell is a nested div, containing the graphic and text label with clickable javascript-->

        <?php if ($index % $this->gridProductsPerRow == 0) {
            echo '<div class="product-row row">';
        } ?>
        <?= CHtml::tag('div',array(
                'class'=>'product_cell col-sm-'.(12/$this->gridProductsPerRow)),false, false); ?>

        <?= CHtml::tag('div',array(
                    'class'=>'product_cell_graphic',
			        'onclick'=>'window.location.href=\''.$objProduct->Link.'\''),
                CHtml::link(CHtml::image($objProduct->ListingImage,'',array('class'=>'img-responsive')), $objProduct->Link));
        ?>

        <?= CHtml::tag('div',array(
		            'class'=>'product_cell_label',
		            'onclick'=>'window.location.href="'),
			    CHtml::link(_xls_truncate($objProduct->Title , 50), $objProduct->Link),false);
        ?>
                <div class="row">

                    <div class="col-sm-6">
                    <?= CHtml::tag('span',array('class'=>'product_cell_price_slash'),$objProduct->SlashedPrice).CHtml::tag('span',array('class'=>'product_cell_price'),$objProduct->Price); ?>
                    </div>
                </div>
            </div>

        <?php if ((($index+1) % $this->gridProductsPerRow == 0)) {
            echo '</div>';
        } ?>

</div>
    <?php endforeach; ?>
    <div id="pagination">
        <div id="page-line" class="col-sm-10 hidden-xs">
            <hr>
        </div>
        <div id="paginator" class="col-sm-2 center-bloc">
        <?php $this->widget('CLinkPager', array(
            'id'=>'pagination',
            'currentPage'=>$pages->getCurrentPage(),
            'itemCount'=>$item_count,
            'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
            'maxButtonCount'=>5,
            'firstPageLabel'=> '<<',
            'lastPageLabel'=> '>>',
            'prevPageLabel'=> '<',
            'nextPageLabel'=> '>',
            'header'=>'',
            'htmlOptions'=>array('class'=>'pagination'),
        )); ?>
        </div>
    </div>
<?php endif;

?>

