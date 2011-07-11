<div class="rounded" style="margin-left: 20px; border:1px sold #eee;">
<h1>Advanced Search</h1>
</div><br />

	<fieldset class="contact">
		
			<p align=center>
				<?php $this->lblError->Render(); ?>
			</p>
			<p>
			  <label for="name"><?php _xt('Search Term') ?>:</label><br />
			  <?php $this->txtSearch->RenderWithError(); ?>
			</p>
			<p>
			  <label for="email"><?php _xt('Start Price (optional)') ?>:</label><br />
			  <?php $this->txtStartPrice->RenderWithError(); ?>
			</p>
			<p>
			  <label for="phone"><?php _xt('End Price (optional)') ?>:</label><br />
			  <?php $this->txtEndPrice->RenderWithError(); ?>
			</p>

			<p>
			  <label for="subject"><?php _xt('Filters') ?>:</label><br />
			  <?php $this->lstFilters->RenderWithError(); ?>
			</p>						
						
		<p style="margin-top:40px;"><?php $this->btnSearch->Render(); ?></p>
			
		</fieldset>
