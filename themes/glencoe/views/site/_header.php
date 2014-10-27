<!-- HEADER: logo, store info & social buttons, search bar, login, cart dropdown -->
<div class="hidden-xs top-bar">
    <div class="container">
        <div class="row">
            <div class="col-xs-3 shipping">
               <?php if(_xls_get_conf('LANG_MENU',0)): ?>
                    <div id="langmenu">
                        <?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>

                    </div>
                <?php endif; ?>
            </div>

            <div class="col-xs-9 menu clearfix">
                <ul class="clearfix rr">
                    <?php if(!Yii::app()->user->isGuest): ?>
                        <li>
                            <!-- <span class="ir icon my-account"></span> -->
                            <span data-size="16"  data-color="#5D322D" data-hovercolor="#5D322D" class="livicon" data-name="user"></span>
                            <?php echo 'Welcome back '.CHtml::link(Yii::app()->user->firstname, array('/myaccount')); ?>
                        </li>
                    <?php else: ?>
                    <!-- nothing here -->
                    <?php endif; ?>
					<?php if(_xls_get_conf('ENABLE_WISH_LIST')): ?>
						<li>
							<span data-size="16" data-color="#5D322D" data-hovercolor="#5D322D" class="livicon" data-name="star-full"></span>
							<span><?php echo CHtml::link(Yii::t('global','Wish List'),Yii::app()->createUrl('/wishlist')) ?></span>
						</li>
					<?php endif; ?>
                    <li>
                        <span data-size="16" data-color="#5D322D" data-hovercolor="#5D322D" class="livicon" data-name="credit-card"></span>
                        <span><?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')); ?></span>
                    </li>

                    <li>
                        <?php if(Yii::app()->user->isGuest): ?>
                             <?php echo CHtml::link('<span data-size="16" data-color="#5D322D" data-hovercolor="#5D322D" class="livicon" data-name="sign-in"></span>'.Yii::t('global', strtoupper('<span>Login</span>')), array("site/login")); ?>
                        <?php else: ?>
                            <?php echo CHtml::link('<span  data-size="16" data-color="#5D322D" data-hovercolor="#5D322D" class="livicon" data-name="sign-out"></span>'.Yii::t('global', strtoupper('<span>Log out</span>')), array("site/logout")); ?>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="hidden-xs container header-logo">
    <div class="row">
        <div class="col-sm-12">
	        <?php echo CHtml::link(CHtml::image($this->pageHeaderImage,
		        Yii::t('global','header image')),$this->createUrl("site/index")); ?>
        </div>
    </div>
</div>


<!-- END HEADER -->