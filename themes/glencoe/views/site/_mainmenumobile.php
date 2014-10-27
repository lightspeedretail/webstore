<div class="container mainnav mobile">
    <div class="list-nav row">
            <nav class="navbar navbar-default" role="navigation">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle top_toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="visible-xs navbar-brand" href="#">Menu</a>
              </div>

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav-pills nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Products <b class="caret"></b></a>
                    <?php
                    echo '<ul class="dropdown-menu">';
                    foreach ($this->MenuTree as $cat) {
                        if ($cat['hasChildren']) {
                            echo '<li class="dropdown-submenu">';
                            echo '<a href="'.$cat['link'].'">'.$cat['label'].'</a>';
                            echo '<ul class="dropdown-menu" ';
                            if (strpos($this->CanonicalUrl,$cat['link']))
                                echo '>';
                            else if (strpos(Yii::app()->getRequest()->getUrl(),'brand') && ($cat['label']==_xls_get_conf('ENABLE_FAMILIES_MENU_LABEL')))
                                    echo '>';
                                 else echo 'style="">';
                            foreach ($cat['children'] as $cat1) {
                                if ($cat1['hasChildren']) {
                                    echo '<li class="dropdown-submenu">';
                                    echo '<a href="'.$cat1['link'].'">'.$cat1['label'].'</a>';
                                    echo '<ul class="dropdown-menu" ';
                                    if (strpos($this->CanonicalUrl,$cat1['link']))
                                        echo '>';
                                    else echo 'style="">';
                                    foreach ($cat1['children'] as $cat2)
                                    {
                                        echo '<li>';
                                        echo '<a href="'.$cat2['link'].'">'.$cat2['label'].'</a>'.'</li>';
                                    }
                                    echo '</ul>';
                                }
                                else {
                                    echo '<li >';
                                    echo $cat1['text'].'</li>';
                                }
                            }
                            echo '</ul>';
                        }
                        else {
                            echo '<li>';
                            echo $cat['text'].'</li>';
                        }
                    }
                    echo '</ul>';?>

                    </li>
                    <?php
                    foreach (CustomPage::model()->toptabs()->findAll() as $arrTab)
                        echo '<li>'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).'</li>'; ?>

                </ul>
              </div><!-- /.navbar-collapse -->
            </nav>

    </div>


    <div class="visible-xs row">


            <nav class="navbar log-in navbar-default" role="navigation">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle users" data-toggle="collapse" data-target=".navbar-ex2-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="users-ico"></span>
                </button>

                <p class="visible-xs navbar-brand"> 
                    <?php if(!Yii::app()->user->isGuest): ?>
                        
                            <span class="userlogin"><?php echo 'Welcome back '.CHtml::link(Yii::app()->user->firstname, array('/myaccount')); ?></span>
                        
                    <?php else: ?>
                            <?php if(Yii::app()->user->isGuest): ?>
                                 <?php echo CHtml::link('<span class="ir icon log-in"></span>'.Yii::t('global', strtoupper('<span>Login</span>')), array("site/login")); ?>
                            <?php endif; ?>
                    <?php endif; ?>

                </p>
              </div>
             
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse navbar-ex2-collapse">
                 <ul>
                    <li>
                        <?php echo CHtml::link(Yii::t('global','Wish List'),Yii::app()->createUrl('/wishlist')) ?>
                    </li>

                    <li>
                        <?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')); ?>
                    </li>

                    <li>
                        <?php if(Yii::app()->user->isGuest): ?>
                             <?php echo CHtml::link(''.Yii::t('global', strtoupper('Login')), array("site/login")); ?>
                        <?php else: ?>
                            <?php echo CHtml::link(''.Yii::t('global', strtoupper('<span class="logout">Log out</span>')), array("site/logout")); ?>
                        <?php endif; ?>
                    </li>
                </ul>
              </div><!-- /.navbar-collapse -->
            </nav>



        <div class="visible-xs col-xs-12 menu_xs">
    
        </div>
    </div>

</div>

<div class="visible-xs container header-logo xs-logo">
    <div class="row">
        <div class="col-sm-12">
            <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE'),
                                    Yii::t('global','header image')), Yii::app()->baseUrl."/"); ?>
        </div>


    </div>
</div>



<script type="text/javascript">
    
$(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
        $( ".mainnav.mobile" ).css("opacity","0.5"); 
    } else {
        console.log('there');
        $( ".mainnav.mobile" ).css("opacity","1"); 
    }
});

</script>




