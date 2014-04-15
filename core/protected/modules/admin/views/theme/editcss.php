<div class="span9">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'editcss',
	'enableClientValidation'=>true,
	)); ?>
    <div class="hero-unit">
        <h3><?php echo $this->editSectionName; ?></h3>
        <div class="editinstructions"><?= $this->editSectionInstructions; ?></div>
    </div>

	    <div class="tabbable">
		    <ul class="nav nav-tabs langedit">
			    <?php
				    foreach($files as $file)
					    echo '<li '.($file['tab']=='custom' ? 'class="active"' : '').
						    '><a href="#'.$file['tab'].'" data-toggle="tab">'.$file['filename'].'</a></li>';
			    ?>
		    </ul>

		    <div class="tab-content">
			    <?php
			    foreach($files as $file)
			    {
				    echo '<div class="tab-pane'.($file['tab']=='custom' ? ' active' : '').
					    '" id="'.$file['tab'].'">';
				    if ($file['tab'] != "custom") {
					    echo '<div class="choosedefault">';
					    echo CHtml::radioButtonList("radio".$file['tab'],$file['usecustom'],array(
							    0=> Yii::t('admin','Use default {filename} from {themename} theme',
							        array('{filename}'=>$file['filename'],'{themename}'=>ucfirst(Yii::app()->theme->name))),
							    1=> Yii::t('admin','Use my custom version of {filename}',
									array('{filename}'=>$file['filename'])))
					    );
					    echo '<div class="restoreoriginal">';
					    echo '<label for="',"check".$file['tab'],'">';

					    echo Yii::t('global',
						    "Restore original {file} file and erase my custom version permanently.",
						    array('{file}'=>$file['filename'])
					    );
					    echo CHtml::checkBox("check".$file['tab'],false,array('value'=>'on'));
					    echo '</label>';
					    echo '</div></div>';
				    } else echo CHtml::hiddenField("radio".$file['tab'],"1");

				    $this->widget('ImperaviRedactorWidget', array(
					    'name' => 'content-'.$file['tab'],
					    'value'=>  $file['contents'],
					    'attribute' => 'page',
					    'htmlOptions'=>array(

						    'style'=>"height: 400px; padding-bottom: 20px;",
						    'spellcheck'=>'false',
					    ),
					    'options' => array(
						    'width'=> '500',
						    'height'=> '400',
						    'autoresize'=>false,
						    'convertDivs'=>false,
						    'linebreaks'=>true,
						    'buttonSource'=>false,
						    'convertLinks'=>false,
						    'phpTags'=>false,
						    'visual'=>true,
						    'toolbar'=>false,

					    )
				    ));
				    echo '</div>';
			    }
			    ?>
		    </div>
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
					'htmlOptions'=>array('id'=>'submit','name'=>'submit','value'=>'Submit')
				)); ?>
				</p>
	        </div>

		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>