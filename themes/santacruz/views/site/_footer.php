<div id="footer">
    <div id="footer-content" class="container">
    	<div class="row">
            <div class="col-sm-4">
                <?php
                    $content = CustomPage::model()->LoadByKey('about');
                    echo "<h5>" . $content->title .  "</h5>";
                    $arrContent =  _xls_parse_language_serialized($content->page);
                    echo $arrContent[Yii::app()->getLanguage()];
                ?>
            </div><!-- /span3-->
            <div class="col-sm-2">
                <div class="clearfix"></div>
                <div id="wishlist-link" class="row">
                    <?php if(_xls_get_conf('ENABLE_WISH_LIST')):
                        //if(Yii::app()->user->isGuest):
                        echo CHtml::link(Yii::t('global','Find a Wish List'),Yii::app()->createUrl('wishlist/search'),array('class'=>'btn btn-link','id'=>'wishlist-search'));
                    endif;
                    ?>
                </div>
            </div>

            <div id="bottomtabs" class="col-sm-offset-2 col-sm-4">
                <?php
                echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'),array('class'=>'btn btn-link','id'=>'site-map'));
                foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
                    if ($arrTab->page_key != 'about')
                        echo '<br>'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link,array('class'=>'btn btn-link','id'=> $arrTab->request_url));
                ?>
            </div>
        </div>
    </div><!-- /row -->
</div><!-- /container-->
