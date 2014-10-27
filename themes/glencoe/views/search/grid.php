<?php if (_xls_get_conf('ENABLE_CATEGORY_IMAGE', 0) && isset($this->category) && $this->category->ImageExist): ?>
    <div class="row">
      <div class="col-sm-12">
            <div id="gridheader">

                  <div id="category_image">
                      <img src="<?= $this->category->CategoryImage; ?>"/>
                  </div>

            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
  <div class="col-sm-12">
        <div id="gridheader">
            <h1><?php echo $this->pageHeader; ?></h1>
              <div class="subcategories">
              <?php  if(isset($this->subcategories) && (count($this->subcategories) > 0)): ?>
              <?php echo _sp("Subcategories"); ?>:
              <?php foreach ($this->subcategories as $item)
                  echo CHtml::link(trim($item['label']), $item['link']); ?>
              <?php endif; ?>
              </div>
        </div>
    </div>
</div>



<?php if(isset($this->custom_page_content)): ?>
    <div class="row">
      <div class="col-sm-12">
            <div id="gridheader">
                    <div id="custom_content">
                    <?php echo $this->custom_page_content; ?>
                    </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (count($model) > 0): ?>

    <?php

    $ct=-1;
        echo '<div class="product-row row">';
        echo '<div class="col-sm-12 grid_prod">';
    foreach($model as $objProduct): ?>
    

    <?php

        echo CHtml::tag('div',array(
                'class'=>'product_cell fade-in three col-xs-12 col-sm-4 col-md-4 col-lg-'.(12/$this->gridProductsPerRow)),
        

            CHtml::tag('div',array(
                    'class'=>'product_cell_graphic',
                    'onclick'=>'window.location.href=\''.$objProduct->Link.'\''),
                CHtml::link(CHtml::image($objProduct->ListingImage,$objProduct->Title), $objProduct->Link)).


            CHtml::tag('div',array(
                    'class'=>'product_cell_label',
                    'onclick'=>'window.location.href=\''.$objProduct->Link.'\''
                ),
                CHtml::link(_xls_truncate($objProduct->Title , 45), $objProduct->Link).
                CHtml::tag('span',array('class'=>'product_cell_price_slash'),$objProduct->SlashedPrice).' '.
                CHtml::tag('span',array('class'=>'product_cell_price'),$objProduct->Price)
            
            )


        );


    ?>
      

    <?php endforeach; ?>

        </div>
        </div>


<?php endif; ?>



    <div id="paginator" class="col-xs-12 col-sm-12">
      <?php $this->widget('CLinkPager', array(
        'id'=>'pagination',
        'currentPage'=>$pages->getCurrentPage(),
        'itemCount'=>$item_count,
        'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
        'maxButtonCount'=>5,
        'firstPageLabel'=> '<span data-s="15" data-color="#E95A44" data-hc="#E95A44" class="livicon" data-name="angle-wide-left" data-l="true"></span>',
        'lastPageLabel'=> '<span data-s="15" data-color="#E95A44" data-hc="#E95A44" class="livicon" data-name="angle-wide-right" data-l="true"></span>',
        'prevPageLabel'=> '<span data-s="15" data-color="#E95A44" data-hc="#E95A44" class="livicon" data-name="chevron-left" ></span>',
        'nextPageLabel'=> '<span data-s="15" data-color="#E95A44" data-hc="#E95A44" class="livicon" data-name="chevron-right" ></span>',
        'header'=>'',
        'htmlOptions'=>array('class'=>'pagination'),
        )); ?>
    </div>

