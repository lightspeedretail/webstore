<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

   <?php $this->registerAsset('css/admin.css'); //here instead of in controller so we can guarantee it's last ?>
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="<?php echo $this->createUrl("default/index"); ?>">Admin Panel</a>
            <div class="nav-collapse collapse">
                <p class="navbar-text pull-right">
	                <?php if(!Yii::app()->user->isGuest && !Yii::app()->user->getState('internal', false))
	                    echo CHtml::link('Logout ('.Yii::app()->user->firstname.')',array('default/logout'),array('class'=>'navbar-link'));
	                ?>
                </p>
	            <?php $this->widget('zii.widgets.CMenu',array(
		            'items'=>$this->moduleList,
		            'htmlOptions'=>array('class'=>'nav'),
	                'activeCssClass'=>'active',
                )); ?><!-- mainmenu -->
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">
	            <?php $this->widget('zii.widgets.CMenu',array(
	            'items'=>$this->menuItems,
	            'encodeLabel'=>false,
	            'htmlOptions'=>array('class'=>'nav nav-list'),
	            'activeCssClass'=>'active',
            )); ?>


            </div><!--/.well -->
        </div><!--/span-->

		    <?php if (Yii::app()->user->hasFlashes())
			    $this->widget('bootstrap.widgets.TbAlert', array(
			    'htmlOptions'=>array('class'=>'span9'),
			    'block'=>true, // display a larger alert block?
			    'fade'=>true, // use transitions?
			    'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
			    'alerts'=>array( // configurations per alert type
				    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				    'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				    'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				    'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				    'danger'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			    ),
		    )); ?><!-- flash messages -->

	    <?php echo $content; ?>
    </div><!--/row-->
</div><!--/.fluid-container-->
</body>
</html>
