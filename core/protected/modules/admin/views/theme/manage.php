<div class="span9">
	<div class="hero-unit nobottom">

		<h3><?php echo Yii::t('admin','Manage My Themes'); ?></h3>
		<div class="editinstructions">
			<?php if (Yii::app()->getRequest()->getQuery('n'))
				echo "<h4>".Yii::t('admin','Your current template has an update available. Click Upgrade Template below the icon to download the latest version.')."</h4> ";

				echo Yii::t('admin','Choose the theme you wish to use by selecting the graphic and clicking Make Active to switch to the theme. Change {color} options for the currently active theme below the image and clicking Make Active. Your currently active theme is always listed first.',array('{color}'=>_xls_regionalize('color')));


			?>
		</div>
		<div class="clearfix spaceafter"></div>
		<div id="thememanage">
			<?php echo CHtml::beginForm('manage', 'post'); ?>


			<?php


				foreach($arrThemes as $key=>$objTheme):
					echo '<div class="span4 theme"><div class="themetitle">'.$objTheme['name'].'</div><div class="themeversion"> v'.$objTheme['version'].'</div><div class="clearfix"></div><div class="themeselect" >';
					echo CHtml::radioButton('theme',
						($key == Yii::app()->theme->name ?  true : false),
						array('id'=>$key,'value'=>$key));
					echo '</div>';
					echo CHtml::tag('div',array(
							'class'=>'themeicon '.($key == Yii::app()->theme->name ?  "selected" : ""),
							'id'=>'img'.$key,
							'onClick'=>'js:
									$("#"+picked).attr("checked", false);
									$("#img"+picked).removeClass("selected");
									$("#'.$key.'").attr("checked", true);
									picked = "'.$key.'";
									$("#img"+picked).addClass("selected")'),
						$objTheme['img']);

				    if($key == Yii::app()->theme->name && Yii::app()->getRequest()->getQuery('n'))
					    $this->widget('bootstrap.widgets.TbButton', array(
						'buttonType'=>'submit',
						'label'=>'Upgrade Template',
						'type'=>'danger',
						'size'=>'mini',
						'htmlOptions'=>array('id'=>'btnUpgrade','name'=>'btnUpgrade','value'=>'btnUpgrade'),
					)); else echo CHtml::tag('div',array(
						    'class'=>'themeoptions',),
					    $key == Yii::app()->theme->name ? $objTheme['options'] : "") ;
					echo '</div>';
				endforeach;
			?>
		</div>
		<div class="clearfix spaceafter"></div>


		<div class="clearfix spaceafter"></div>
	</div>


		<p class="pull-right">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'label'=>'Make Active',
				'type'=>'primary',
				'size'=>'large',
				'htmlOptions'=>array('id'=>'btnSet','value'=>'btnSet'),
			)); ?>
		</p>

	<p>
		<div>
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'label'=>'Copy selected theme for customization',
				'type'=>'inverse',
				'size'=>'mini',
				'htmlOptions'=>array('id'=>'btnCopy','value'=>'btnCopy'),
			)); ?>
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'label'=>'Remove unchanged files from selected theme',
				'type'=>'inverse',
				'size'=>'mini',
				'htmlOptions'=>array('id'=>'btnClean','value'=>'btnClean'),
			)); ?>
		</div>
	</p>

		<?php echo CHtml::endForm(); ?>


</div>
</div>