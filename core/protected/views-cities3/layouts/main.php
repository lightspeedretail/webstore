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

			<div class="row-fluid">
				<!-- Side navigation / Menu tree -->
				<div class="span2">
					<?php echo $this->renderPartial("/site/_sidenavigation",null,true,false); ?>
				</div>
				<!-- content (viewport) -->
				<div class="span10">
					<?php echo $content; ?>
				</div>
			</div>

			<!-- footer -->
			<?php echo $this->renderPartial("/site/_footer",null,true,false); ?>

		</div>

		<?php echo $this->sharingFooter; ?>

		<!-- backwards compatibility only, to be removed by version 3.2 -->
		<?php echo $this->loginDialog; ?>
	</body>
</html>