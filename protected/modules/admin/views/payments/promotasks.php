<div class="span9">
	<h3>Promo Code Tasks</h3>
	<div class="hero-unit">
		<div class="editinstructions"><?php echo $this->editSectionInstructions; ?></div>
		<?php echo $form->renderBegin(); ?>
		<?php echo $form->renderBody();?>

		<p class="pull-right">
			<?php
				$this->widget('bootstrap.widgets.TbButton', array(
					'htmlOptions'=>array('name'=>'buttonCreate'),
					'buttonType'=>'submit',
					'label'=>'Create Codes',
					'type'=>'primary',
					'size'=>'medium',
				)); ?>

		</p>
		<?php $form->renderEnd(); ?>

		<div class="clearfix"></div>
		<hr>

		<?php echo $form2->renderBegin(); ?>

		<?php for($x=1; $x<=4; $x++): ?>
			<div class="row-fluid">
				<div class="span1">
					&nbsp;
				</div>
				<div class="span7"><label>
					<?php switch ($x){
						case 1: echo "Delete all codes that are used up (where Qty Remaining = 0)"; $btnN = "DeleteUsed"; $btn = "Delete Used"; break;
						case 2: echo "Delete all codes that are Expired (where set Valid Until date has passed)"; $btnN = "DeleteExpired"; $btn = "Delete Expired"; break;
						case 3: echo "Delete all codes that are Single Use (whether valid or not)"; $btnN = "DeleteSingleUse"; $btn = "Delete Single Use"; break;
						case 4: echo "Delete all codes (complete erase all defined Promo Codes)"; $btnN = "DeleteEverything"; $btn = "Delete Everything"; break;

					} ?></label>
				</div>

				<div class="span4">
					<p class="pull-right">
						<?php
							$this->widget('bootstrap.widgets.TbButton', array(
								'htmlOptions'=>array('name'=>$btnN),
								'buttonType'=>'submit',
								'label'=>$btn,
								'type'=>'primary',
								'size'=>'medium',
							)); ?>
					</p>
				</div>
			</div>
		<?php endfor; ?>
		<?php $form2->renderEnd(); ?>

	</div>
</div>