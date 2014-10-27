<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
	<!-- Head section -->
	<?php echo $this->renderPartial("/site/_head",null,true,false); ?>

	<body>

		<?php echo $this->sharingHeader; ?>

        <?php echo $this->renderPartial("/site/_header",null,true,false); ?>

        <div class="visible-xs hidden-sm hidden-md hidden-lg main-menu">
                <?php echo $this->renderPartial("/site/_mainmenumobile",null,true,false); ?>
        </div>

        <div class="hidden-xs main-menu">
                <?php echo $this->renderPartial("/site/_mainmenu",null,true,false); ?>
        </div>
        
        <div class="cart-search">
            <?php echo $this->renderPartial("/site/cart-search",null,true,false); ?>
        </div>



        <div class="container">
            <?php echo $this->renderPartial("/site/breadcrumbs",null,true,false); ?>
        </div>



        <div class="container">

                <?php echo $this->renderPartial("/site/_navigation",null,true,false); ?>

                <!-- Product Grid -->
                <?php echo $content; ?>


        </div>


        <!-- footer -->
        <?php echo $this->renderPartial("/site/_footer",null,true,false); ?>



		<?php echo $this->sharingFooter; ?>

	</body>
</html>