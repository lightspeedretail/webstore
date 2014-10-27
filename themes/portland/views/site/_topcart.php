<?php echo CHtml::link(Yii::t('cart','n==1#Cart ({n})|n>1#Cart ({n})',Yii::app()->shoppingcart->totalItemCount), array('cart/index')) ?>
<?= $this->renderPartial('/site/_sidecart',null, true); ?>
<script>

	$(document).ready(function() {

		$("#checkoutlink > a").click(function(e){
			e.preventDefault();
			$("#shoppingcart").toggleClass("shoppingcarthidden");
		})
	}
	);
</script>