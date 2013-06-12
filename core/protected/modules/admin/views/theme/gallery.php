<div class="span9">
	<div class="hero-unit nobottom">

		<h3><?php echo Yii::t('admin','Install themes from the LightSpeed Gallery'); ?></h3>
		<div class="editinstructions">
			<?php echo Yii::t('admin','To install a theme, click it and then click Install. The theme will be downloaded from our gallery to your Web Store and available for you to use.'); ?>
		</div>
		<div class="clearfix spaceafter"></div>
		<?php echo CHtml::beginForm('gallery', 'post',
			array(
				'onsubmit' => '$("#btnUpload").label = "Downloading";$("#submitSpinner").show()')
		); ?>

		<div class="row-fluid">
			<?php


			foreach($arrThemes as $key=>$objTheme):
				echo '<div class="span4"><div class="themeselect" >';
				echo CHtml::radioButton('gallery',
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
				echo CHtml::tag('div',array(
						'class'=>'themeinstaller',),
					$objTheme['name']);
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
		<p class="pull-right">
			<span id="submitSpinner" style="display:none">
				<?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?>
			</span>
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'label'=>'Install',
				'type'=>'primary',
				'size'=>'large',
				'htmlOptions'=>array('id'=>'btnUpload'),
			)); ?>
		</p>

		<?php echo CHtml::endForm(); ?>


</div>
</div>