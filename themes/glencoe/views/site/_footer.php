<footer class="footer">
    <div class="footer_decoration"></div>
    <div class="container">
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 infostore">
                    <?php echo _xls_get_conf('STORE_NAME')."<br>"; ?>
                    <?php echo _xls_get_conf('STORE_ADDRESS1')."<br>";
                          echo _xls_get_conf('STORE_ADDRESS2')."<br>";
                          echo _xls_get_conf('STORE_HOURS')."<br>";
                          echo _xls_get_conf('STORE_PHONE')."<br>";
                          echo CHtml::link(_xls_get_conf('EMAIL_FROM'),'mailto:'._xls_get_conf('EMAIL_FROM'));
                    ?>
            </div>
            <div class="clearfix visible-xs"></div>
            <div id="bottomTabs" class="col-xs-12 col-sm-3">
                <ul>
                    <?php
                    foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
                        echo '<li style="list-style-type: none;">'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link, array('id'=> $arrTab->request_url)).'</li>'; ?>
                    <li style="list-style-type: none;">
                        <?php echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'),array('id'=>'site-map')); ?>
                    </li>
                </ul>
            </div>
            <div class="clearfix visible-xs"></div>
            <div id="customPages" class="col-xs-12 col-sm-3">
                <ul>
                    <?php
                    foreach (CustomPage::model()->toptabs()->findAll() as $arrTab)
                        echo '<li style="list-style-type: none;">'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link, array('id'=> $arrTab->request_url)).'</li>'; ?>
                </ul>
            </div>
            <div class="clearfix visible-xs"></div>
            <div class="col-xs-12 col-sm-2">
                <div class="cards" >
                    <?php $ccs = CreditCard::model()->findAllByAttributes(array('enabled'=>1));
                        foreach ($ccs as $cc) : ?>
                            <img style="padding-bottom: 2px" src="/themes/glencoe/css/images/payment/<?php echo str_replace(' ','-',strtolower($cc->label)); ?>-curved-32px.png" alt="img <?php echo str_replace(' ','-',strtolower($cc->label)) ?>">
                    <?php endforeach;
                    $paypal = false;
                    $payments = Modules::model()->findAllByAttributes(array('category'=>'payment','active'=>'1'));
                    foreach ($payments as $payment)
                        if (stripos($payment->name,'paypal')!==false) $paypal = true;
                    if ($paypal) : ?>
                    <img src="/themes/glencoe/css/images/payment/paypal-curved-32px.png" alt="img paypal">
                    <?php endif; unset($paypal); unset($payments); ?>
                </div>
	            <?php
	            if (_xls_get_conf('SOCIAL_FACEBOOK') ||
	                _xls_get_conf('SOCIAL_LINKEDIN') ||
	                _xls_get_conf('SOCIAL_PINTEREST')||
	                _xls_get_conf('SOCIAL_TWITTER')  ||
	                _xls_get_conf('SOCIAL_INSTAGRAM')
	            ):
	            ?>
                <ul class="top-bar-list">
                        <li>
                           <ul class="nav social col-xs-12 col-sm-12">
                                <?php if (_xls_get_conf('SOCIAL_FACEBOOK')) echo '<li><a href="'._xls_get_conf('SOCIAL_FACEBOOK').'" data-s="21" data-color="#ffffff" data-hc="#E95A44" class="livicon" data-name="facebook-alt" ></a></li>';
                                      if (_xls_get_conf('SOCIAL_LINKEDIN')) echo '<li><a href="'._xls_get_conf('SOCIAL_LINKEDIN').'" data-s="21" data-color="#ffffff" data-hc="#E95A44" class="livicon" data-name="linkedin-alt" ></a></li>';
                                      if (_xls_get_conf('SOCIAL_PINTEREST')) echo '<li><a href="'._xls_get_conf('SOCIAL_PINTEREST').'" data-s="21" data-color="#ffffff" data-hc="#E95A44" class="livicon" data-name="pinterest-alt"></a></li>';
                                      if (_xls_get_conf('SOCIAL_TWITTER')) echo '<li><a href="'._xls_get_conf('SOCIAL_TWITTER').'" data-s="21" data-color="#ffffff" data-hc="#E95A44" class="livicon" data-name="twitter-alt"></a></li>';
                                      if (_xls_get_conf('SOCIAL_INSTAGRAM')) echo '<li><a href="'._xls_get_conf('SOCIAL_INSTAGRAM').'" data-s="21" data-color="#ffffff" data-hc="#E95A44" class="livicon" data-name="instagram"></a></li>';
                                ?>
                           </ul>
                        </li>
                </ul>
	            <?php endif; ?>
            </div>

            <div class="clearfix visible-xs"></div>
            <div class="visible-xs hidden-sm hidden-md hidden-lg col-sm-2">
                <?php if(_xls_get_conf('LANG_MENU',0)): ?>
                    <div id="langmenu">
                        <?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 copyright" >
                <span><br>&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.</span>
            </div>
        </div>


    </div>
</footer>





                
     