<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.

 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Web Admin panel chart template 
 *
 * 
 *
 */
include_once(adminTemplate('header.tpl.php'));

include_once(adminTemplate('pages.tpl.php'));

$this->RenderBegin(); ?>
		<br /><br />
			
		<div id="options" class="accord rounded"> 
		<div id="tabs">
			<ul>
				<?php foreach($this->arrTabs as $type=>$label): ?>
				<a href="<?= $this->get_uri($type); ?>" >
					<li class="rounded 
						<?php if($type == $this->currentTab): ?>
							active
						<?php endif; ?> {5px top transparent}" style="display:block; float: left">
						<?= $label; ?>
					</li>
				</a>
				<?php endforeach; ?>
			</ul>
		</div>

		<ul id="listOrder">
		<?php foreach($this->arrGraphPnls as $key=>$pnl): ?>
			
					<li class="rounded"> 
						<div class="title rounded"> 
							<a href="#" class="tooltip" title="Click on view to display the chart"><img src="<?= adminTemplate('css/images/btn_info.png') ?>"  class="info" /></a>
							<div class="name"><?php _xt($pnl->Name); ?></div> 
							<div class="button rounded" <?php $this->pxyViewChart->RenderAsEvents($key); ?> style="cursor: pointer;" ><?php _xt('View'); ?></div>
						</div> 
						<?php $pnl->Render('CssClass=charts'); ?>
					</li>
		<?php endforeach; ?>
		</ul>
		</div>

<?php $this->RenderEnd(); ?>
</body>
</html>
