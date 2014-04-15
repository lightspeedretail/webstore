<div class="span9">
	<div class="hero-unit nobottom">

		<h3><?php echo Yii::t('admin','Install themes from the LightSpeed Gallery'); ?></h3>
		<div class="editinstructions">
			<?php echo Yii::t('admin','To install a theme, click it and then click Install. The theme will be downloaded from our gallery to your Web Store and available for you to use. Any theme without an install button has already been installed.'); ?>
		</div>
		<div class="clearfix spaceafter"></div>
		<?php echo CHtml::beginForm('gallery', 'post'); ?>
		<div class="row-fluid">
			<?php foreach($arrThemes as $key=>$objTheme):
				echo '<div class="span4"><div class="themeselect" >';
				echo CHtml::radioButton('gallery',
					($key == $currentTheme ?  true : false),
					array('id'=>$key,'value'=>$key));
				echo '</div>';
				echo CHtml::tag('div',array(
						'class'=>'themeicon '.($key == $currentTheme ?  "selected" : ""),
						'id'=>'img'.$key,
						'onClick'=>'js:
								$("#"+picked).attr("checked", false);
								$("#img"+picked).removeClass("selected");
								$("#'.$key.'").attr("checked", true);
								picked = "'.$key.'";
								$("#img"+picked).addClass("selected")'),
					$objTheme['img']);
				echo CHtml::tag('div',array(
						'class'=>'themeinstaller',),
					$objTheme['name']); ?>

				<?php if ($objTheme['newver']>0)
					$this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'submit',
					'label'=>($objTheme['newver']==1 ? 'Update' : 'Install'),
					'type'=>($objTheme['newver']==1 ? 'danger' : 'primary'),
					'size'=>'small',
					'htmlOptions'=>array(
						'id'=>$objTheme['name'],
						'value'=>($objTheme['newver']==1 ? 'update' : 'install'),
						'name'=>$key,
						'class'=>'galleryinstall',
						'onclick'=>'js:$(this).addClass("installing");$(this).html("")'
					)
				));

				echo CHtml::tag('div',array(
						'class'=>'themeinstallerdesc',),
					"Version ".$objTheme['version']);
				echo CHtml::tag('div',array(
						'class'=>'themeinstallerdesc',),
					$objTheme['description']);
				echo CHtml::tag('div',array(
						'class'=>'themeinstallercredit',),
					"By: ".$objTheme['credit']);

				echo '</div>';
			endforeach;
			?>
		</div>
		<div class="clearfix spaceafter"></div>


		<div class="clearfix spaceafter"></div>
	</div>

		<?php echo CHtml::endForm(); ?>


</div>
</div>