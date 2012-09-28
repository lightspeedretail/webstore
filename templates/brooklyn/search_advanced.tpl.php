<fieldset>
	<legend><?php echo _sp('Advanced Search') ?></legend>
	<?php $this->lblError->Render(); ?>

	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Search Term"); ?></span>
			<?php $this->txtSearch->RenderWithError(); ?>
		</div>
	</div>

	<div class="row">
		<div class="five columns alpha">
			<span class="label"><?php echo _sp("Start Price (optional)"); ?></span>
			<?php $this->txtStartPrice->RenderWithError(); ?>
		</div>
		<div class="five columns omega">
			<span class="label"><?php echo _sp("End Price (optional)"); ?></span>
			<?php $this->txtEndPrice->RenderWithError(); ?>
		</div>
	</div>

	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Filters"); ?></span>
			<?php $this->lstFilters->RenderWithError(); ?>
		</div>
	</div>

	<?php $this->btnSearch->Render(); ?>
</fieldset>


