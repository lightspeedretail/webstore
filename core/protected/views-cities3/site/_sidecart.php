<div id="shoppingcart">
	<?php
	// If an item was added to the cart then render the modal with the item.
	$this->widget('ext.wscartmodal.wseditcartmodal');
	if (!empty($objCartItem)) {
		$this->widget(
			'ext.wscartmodal.wsaddtocartmodal',
			array('objCartItem' => $objCartItem)
		);
	}
	else
	{
		// Otherwise running the modal will simply require its JS & CSS dependencies.
		$this->widget('ext.wscartmodal.wsaddtocartmodal');
	}

	// If we are coming from the EditcartController, then open the edit cart modal
	if (isset($_GET['editcart']) && $_GET['editcart'] === 'true') {
		Yii::app()->clientScript->registerScript('showEditCartModal', 'showEditCartModal();',CClientScript::POS_END);
	}
	?>

</div>