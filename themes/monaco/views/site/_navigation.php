<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/", array('class'=>'logo') ); ?>


 			<div id="side">
               <ul id="acc3" class="accordion">
                    <li class="dropdown">
                        <a href="/search/browse" class="dropdown-toggle">Products </a>
	                    <?php
	                    if (count($this->MenuTree))
	                    {
		                    $this->widget('zii.widgets.CMenu', array(
			                    'items'=>$this->MenuTree
		                    ));
	                    }
	                    ?>
                    </li>
                    <?php
                    foreach (CustomPage::model()->toptabs()->findAll() as $arrTab)
                        echo '<li>'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).'</li>'; ?>

                </ul>
			</div>

<!-- START MENU TABLET,MOBILE -->
	<nav class="visible-phone visible-tablet">
		<ul>
			<?php if(Yii::app()->user->isGuest): ?>
				<?php echo '<li class="custom">'.CHtml::link(Yii::t('global', 'Login'), array("site/login")).'</li>'; ?>
			<?php else: ?>
				<?php echo '<li class="custom">'.CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, array('/myaccount')); ?>
			<?php endif; ?>

			<?php if(Yii::app()->user->isGuest): ?>
				<li class="custom"><a href="<?= _xls_site_url('myaccount/edit'); ?>"><?php echo Yii::t('global', 'Register'); ?></a></li>
			<?php else: ?>
				<?php echo '<li class="custom">'.CHtml::link(Yii::t('global', 'Logout'), array("site/logout")).'</li>'; ?>
			<?php endif; ?>

			<?php
			foreach (CustomPage::model()->toptabs()->findAll() as $arrTab)
				echo '<li class="custom">'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).'</li>';
			?>

			<?php
			if (count($this->MenuTree))
			{
				$this->widget('zii.widgets.CMenu', array(
					'items'=>$this->MenuTree
				));
			}
			?>

		</ul>
	</nav>
<!-- END MENU TABLET,MOBILE -->

<span class='divider'></span>
<div class="bottom-menu">
	<?php echo $this->renderPartial("/site/_search",array(),true); ?>

	<div class="login">
		<?php if(Yii::app()->user->isGuest): ?>
			<?php echo CHtml::link(Yii::t('global', 'Login'), array("site/login")); ?>
			&nbsp;/&nbsp;
			<a href="<?= _xls_site_url('myaccount/edit'); ?>"><?php echo Yii::t('global', 'Register'); ?></a>
		<?php else: ?>
			<?php echo CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, array('/myaccount')); ?>
			&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout")); ?>
		<?php endif; ?>
	</div>

	<div id="shoppingcart">
		<?= $this->renderPartial('/site/_topcart',null, true); ?>
	</div>

	<div id="wishlistsearch">
		<a href="<?php echo Yii::app()->createUrl('wishlist/search'); ?>"><?= Yii::t('global','Wish list Search'); ?></a>
	</div>

	<?php if(_xls_get_conf('LANG_MENU',0)): ?>
		<div id="langmenu">
			<?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
		</div>
	<?php endif; ?>
</div>

















