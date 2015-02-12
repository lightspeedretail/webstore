
<div class="span9">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'custompage',
	'enableClientValidation'=>true,
	)); ?>
    <div class="hero-unit">
        <h3><?php echo $this->editSectionName; ?></h3>
        <div class="editinstructions"><?php echo $this->editSectionInstructions; ?></div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'page_key'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'page_key'); ?></div>
            <div class="span3"><?php echo $form->error($model,'page_key'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'title'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'title'); ?></div>
            <div class="span3"><?php echo $form->error($model,'title'); ?></div>
        </div>

	    <div class="row">
		    <div class="span4"><?php echo $form->labelEx($model,'column_template'); ?></div>
		    <div class="span5"><?php echo $form->dropDownList($model,'column_template',array('1'=>'column1','2'=>'column2')); ?></div>
		    <div class="span3"><?php echo $form->error($model,'column_template'); ?></div>
	    </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'tab_position'); ?></div>
            <div class="span5"><?php echo $form->dropDownList($model,'tab_position',$model->getPositions()); ?></div>
            <div class="span3"><?php echo $form->error($model,'tab_position'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'product_tag'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'product_tag'); ?></div>
            <div class="span3"><?php echo $form->error($model,'product_tag'); ?></div>
        </div>


	    <div class="row">
		    <div class="span4"><?php echo $form->labelEx($model,'product_display'); ?></div>
		    <div class="span5"><?php echo $form->dropDownList($model,'product_display',array('2'=>'as grid','1'=>'as slider')); ?></div>
		    <div class="span3"><?php echo $form->error($model,'product_display'); ?></div>
	    </div>

        <div class="row">
            <div class="span4"><?php echo $form->labelEx($model,'meta_description'); ?></div>
            <div class="span5"><?php echo $form->textField($model,'meta_description'); ?></div>
            <div class="span3"><?php echo $form->error($model,'meta_description'); ?></div>
        </div>

        <div class="row">
            <div class="span4"><label>To Delete</label></div>
            <div class="span5 rememberMe"><?php echo $form->checkBox($model,'deleteMe'); ?>  <span class="rememberMe"><?php echo Yii::t('admin','Tick to delete this custom page on Save'); ?></span></div>
            <div class="span3"><?php echo $form->error($model,'deleteMe'); ?></div>
        </div>


	</div>

	<div class="span11">
	    <h4>Edit Page Content</h4>
		<div class="tip">Enter optional page content that will appear above any products (if you are using product tags feature). Click on the first tool on the toolbar to switch to editing HTML directly. Note this editor can be used for HTML and CSS, but any scripting language like JavaScript will not be functional.</div>
		<p></p>


		<?php if (_xls_get_conf('LANG_MENU'))
			$langs = _xls_comma_to_array(_xls_get_conf('LANG_OPTIONS'));
		else $langs=array("en:English");
		?>
		<div class="tabbable">
			<ul class="nav nav-tabs langedit">
				<?php
					foreach($langs as $lang)
					{
						$langa = explode(":",$lang);
						echo '<li '.($langa[0]=='en' ? 'class="active"' : '').
							'><a href="#'.$langa[0].'" data-toggle="tab">'.$langa[1].'</a></li>';
					}
				?>
			</ul>

			<div class="tab-content">
				<?php
				$origLang = Yii::app()->language;
				$values = _xls_parse_language_serialized($model->page_data);

				foreach($langs as $lang)
				{
					$langa = explode(":",$lang);
					Yii::app()->language = 	$langa[0];
					echo '<div class="tab-pane'.($langa[0]=='en' ? ' active' : '').'" id="'.$langa[0].'">';
					$this->widget('ImperaviRedactorWidget', array(
						'name' =>   'content-'.$langa[0],
						'value'=> (isset($values[$langa[0]]) ? $values[$langa[0]] : ""),
						'attribute' => 'page',
						'htmlOptions'=>array('style'=>"height: 400px; padding-bottom: 20px;"),
						'options' => array(
							'width'=> '500',
							'height'=> '400',
							'autoresize'=>false,
							'convertDivs'=>false,
							'linebreaks'=>false,
						),
					));
					echo '</div>';
				}
				Yii::app()->language = $origLang;
				?>
			</div>
		</div>
	</div> <!-- /tabbable -->

</div>


	<div class="row">
		<div class="span11">
            <div class="row">
	            <P></P>
		        <p class="pull-right">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'submit',
					'label'=>'Save',
					'type'=>'primary',
					'size'=>'large',
				)); ?>
				</p>
	        </div>

		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
