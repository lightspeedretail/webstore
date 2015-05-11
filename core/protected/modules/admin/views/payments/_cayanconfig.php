<?php
echo $form->renderBegin();
?>

<div class="links">
	<input class="btn btn-danger" type="button" value="Clear All" size="small" onclick="clearFields();">
</div>

<?php
echo $form->renderBody();
echo $form->renderEnd();
?>

<div class="pull-right">
	<?php
	$this->widget(
		'bootstrap.widgets.TbButton',
		array(
			'htmlOptions'=>array('id'=>'buttonSavePCR'),
			'label'=>'Save',
			'type'=>'primary',
		)
	);
	?>
</div>

<script type="text/javascript">

	// Make our text input fields user-friendly color pickers
	$('#cayanForm input[type="text"]').miniColors();

	function clearFields(){
		$('#cayanForm input[type="text"]').val('');
		$('#cayanForm input[type="checkbox"]').attr('checked', null);
	}

</script>