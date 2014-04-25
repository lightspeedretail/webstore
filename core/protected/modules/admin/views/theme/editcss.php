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
		    <ul class="nav nav-tabs langedit" id="nav_tabs">
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

					echo '<div class="choosedefault">';
				    echo '<div class="enable">';
					    echo '<label for="',"check1".$file['tab'],'">';
				        echo CHtml::checkBox("check1".$file['tab'],$file['useme'],array('value'=>'on'));
					    echo Yii::t('global',
						    "Use {file} with {themename}.",
						    array('{file}'=>$file['filename'],'{themename}'=>ucfirst(Yii::app()->theme->name))
				        );
				        echo '</label>';
					echo '</div>';
					echo '</div>';
				    if ($file['tab'] != "custom")
					    echo CHtml::hiddenField("radio".$file['tab'],"0");
				    else
					    echo CHtml::hiddenField("radio".$file['tab'],"1");

				    $this->widget('Codemirror', array(
					        'model' => $file,
						    'name' => 'content'.$file['tab'],
					        'value'=>  $file['contents'],
						    'attribute' => 'page',
						    'htmlOptions'=>array(
							    'style'=>"height: 400px; width: 500px; padding-bottom: 20px;",
							    'spellcheck'=>'false',
						    ),
						    'options' => array(
							    'lineNumbers'=>true,
							    'lineWrapping'=>true,
							    'matchBrackets'=>true,
							    'mode'=>'text/css',
							    'readOnly' => $file['tab']!='custom',
						    )
					    ));


				    echo '</div>';

			    }
			    ?>
		    </div>
	    </div>

    <script>
        $(function () {
            $('#nav_tabs a[data-toggle="tab"]').on('shown', (function (e) {
                window['codeMirrorcontent'+ $(this).attr('href').slice(1)].refresh();
            }));

        });
    </script>


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