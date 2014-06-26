<?php

/*
 * Note this is for backwards compatibility only, to be removed by version 3.5
 *
 * Please replace the call
    <?= $this->renderPartial('/product/_matrixdropdown', array('form'=>$form,'model'=>$model), true); ?>
 *
 * with
 *
 *
   <?php $this->widget('ext.wsmenu.wsmatrixselector', array(
		'form'=> $form,
		'model'=> $model
	)); //matrix chooser ?>
 *
 * in your product/index.php file in your theme, if necessary.
 *
 */
	$this->widget('ext.wsmenu.wsmatrixselector', array(
		'form'=> $form,
		'model'=> $model
	));
