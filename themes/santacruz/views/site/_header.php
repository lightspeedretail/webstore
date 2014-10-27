<?php
/**
* Created by Shannon Curnew
* Date: 9/21/13
* Time: 2:28 PM
 */
?>

	<div class="row logreg">
        <div id="acctbuttons" class="pull-right col-sm-3 col-xs-12">
            <?php if(Yii::app()->user->isGuest): ?>
	            <?php echo CHtml::link('<span class="fa fa-sign-in"></span>'.Yii::t('global', 'Login'), array("site/login"),array('class'=>'btn acctbtn  hidden-xs col-sm-offset-3 col-sm-3 ')); ?>

                <?php echo CHtml::link(Yii::t('global', '<i class="fa fa-pencil-square-o"></i> Register'),_xls_site_url('myaccount/edit'),array('class'=>'btn acctbtn hidden-xs col-sm-3'));?>
                <?php echo CHtml::link(Yii::t('global', 'Register'),_xls_site_url('myaccount/edit'),array('class'=>'btn btn-block acctbtn visible-xs col-xs-12 regxs'));?>

            <?php else: ?>
                <?php echo CHtml::link(Yii::app()->user->firstname." (My Account)", array('/myaccount'),array('class'=>'btn btn-link')); ?>
                <i class="icon-user"></i> <!--  CHtml::image(Yii::app()->user->profilephoto)-->
                <?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout"),array('class'=>'btn btn-link')); ?>
            <?php endif; ?>
        </div>
     </div>
   
   <div id="login-header" class="container">
        <div id="headerimage" class="col-xs-12 col-sm-offset-3 col-sm-6 center-block">
			<?php echo CHtml::link(CHtml::image($this->pageHeaderImage,'Home',array('class'=>'img-responsive')), $this->createUrl("site/index")); ?>
    </div>

</div>



