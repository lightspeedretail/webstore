<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
	<!-- <head> section -->
	<?php echo $this->renderPartial("/site/_head",null,true,false); ?>

	<body>
		<?php echo $this->sharingHeader; ?>
		<div id="container" class="container-fluid text-center">

			<!-- template header -->
			<?php echo $this->renderPartial("/site/_header",null,true,false); ?>

			<!-- Require the navigation -->
			<?php echo $this->renderPartial("/site/_navigation",null,true,false); ?>

			<!-- content (viewport) -->
			<?php echo $content; ?>

			<!-- footer -->
			<?php echo $this->renderPartial("/site/_footer",null,true,false); ?>

		</div>

		<?php echo $this->sharingFooter; ?>

		<?php echo $this->loginDialog; ?>

	</body>
</html>