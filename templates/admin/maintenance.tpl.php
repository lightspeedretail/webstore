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
 * Web Admin panel template called by xls_admin
 * Used for system Tasks panel
 * 
 *
 */

include_once(adminTemplate('header.tpl.php'));

$this->RenderBegin(); ?>
		<div id="mainNav">
		<?php
		
		foreach($this->arrTabs as $type=>$label) {
			echo '<a class="mainNavItem'.($type == $this->currentTab ? " active" : "").'" href="'.$this->get_uri($type).'"><span class="innertab">'.$label;
			 if($type == $this->currentTab)
				$this->objDefaultWaitIcon->Render();			
			echo '</span></a>';
		}
		?>
		</div>
		<br clear="both">
		
<div id="options"  style="width:960px;" >
	<div class="content">	

<?php

if(isset($this->HelperRibbon)) 
	if (strlen($this->HelperRibbon)>0)
		echo '<div style="padding: 5px;"><img style="padding-right: 5px;width:44px; height:35px;" align="left" src="'.adminTemplate('css/images/questionmark.png').'"> '.$this->HelperRibbon.'</div>';

?>
		<ul id="listOrder"> 
		<?php foreach($this->arrMPnls as $key=>$pnl): ?>
			
					<li class="rounded"> 
						<div class="title rounded"> 
							<a href="#" class="tooltip" title="<?= $pnl->ToolTip; ?>"><img src="<?= adminTemplate('css/images/btn_info.png') ?>"  class="info" /></a>
							<div class="name"><?php _xt($pnl->Name); ?></div> 
							<div class="button rounded" <?php $this->pxyAction->RenderAsEvents($key); ?> style="cursor: pointer;" ><?php _xt('Perform'); ?></div>
						</div> 
						<?php $pnl->Render('CssClass=task_list'); ?>
					</li>
		<?php endforeach; ?>
		</ul>
		
		</div>



<?php $this->RenderEnd(); ?>		
</body>
</html>
