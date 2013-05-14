<?php
//We cheat a bit and use google naming on some field so our CSS still works

	switch ($_GET['item'])
	{


		case 'name0':
			echo '<div class="restrictions">';
			$form=$this->beginWidget('CActiveForm', array(
					'id'=>'googlecats',
					'enableAjaxValidation'=>false,
					'enableClientValidation'=>false,
				));

			echo '<h4>Editing: '.$strRequestUrl.'</h4><div class="ginstructions">Choose the most appropriate '.ucfirst($service).' category path for this item. Choose subcategories as needed. If the next dropdown remains dark, that means there are no additional levels for that category. '.$strModelName::getInstructions().'</div>';
			echo '<form>';

			$criteria = new CDbCriteria();
			$criteria->select = 't.id, t.name1';
			$criteria->AddCondition("name2 is NULL");
			$criteria->order = "name1";
			$arrCats =	CHtml::listData($strModelName::model()->findAll($criteria), 'id', 'name1');

			echo $form->dropdownList($model,'id',$arrCats,
				array(
					'id'=>'name1',
					'class'=>'tinyfont googleselect',
					'prompt'=>'--Choose--',
				));
			for($x=2; $x<=7; $x++) {
				echo '<br>'.str_repeat('&nbsp;',($x*2)).
					'<select disabled="true" name="name'.$x.'" id="name'.$x.'" class="tinyfont googleselect" >';
				echo '<option value="0">';
				echo '</select>';
			}

			if ($service=="google")
			{
				echo '<div id="extra" class="extra">'.str_repeat('&nbsp;',($x*2)).
					'&nbsp;Required for Apparel &amp; Accessories only: <b>Gender</b> <select name="googleg" id="googleg" class="tinyfont" >';
				echo '<option value="Unisex">Unisex';
				echo '<option value="Male">Male';
				echo '<option value="Female">Female';
				echo '</select>&nbsp;';

				echo '<b>Age</b> <select name="googlea" id="googlea" class="tinyfont" >';
				echo '<option value="Adult">Adult';
				echo '<option value="Kids">Kids';
				echo '</select></div>';
			}

			if ($service=="amazon")
			{
				echo '<div id="extra" class="extra">'.str_repeat('&nbsp;',($x*2)).
					'&nbsp;Specify exact Product Type, if applicable: <select name="producttype" id="producttype" class="tinyfont" >';
				echo '<option value="0">n/a';
				echo '</select></div>';
			}

			echo '  <div class="row pagination-centered">';
				$this->widget('bootstrap.widgets.TbButton', array(
					'htmlOptions'=>array('id'=>'buttonSavePCR'),
					'label'=>'Save',
					'type'=>'primary',
					'size'=>'small',
				));
			echo "</div>";

			$this->endWidget();
			echo '</div>';
			break;

		default:
			echo json_encode(array());

	}
?>
