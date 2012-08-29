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
 * Web Admin panel template called by xlsws_admin_ship_modules class
 * Used for shipping modules
 * 
 *
 */

include_once(adminTemplate('header.tpl.php'));

$this->RenderBegin(); ?>
		<div id="mainNav">
		<?php
		
		foreach($this->arrTabs as $type=>$label)
			echo '<a class="mainNavItem'.($type == $this->currentTab ? " active" : "").'" href="'.$this->get_uri($type).'"><span class="innertab">'.$label.'</span></a>';
		?>
		</div>
		<br clear="both">
		
<div id="options"  style="width:960px;" >
	<div class="content">	
<?php

if(isset($this->HelperRibbon)) 
	if (strlen($this->HelperRibbon)>0)
		echo '<div style="padding: 5px;"><img style="padding-right: 5px; width:44px; height:35px;" align="left" src="'.adminTemplate('css/images/questionmark.png').'"> '.$this->HelperRibbon.'</div>';

?>		
		<div id="modulesList" class="accord rounded">
		<ul id="listOrder">
		
			<?php
			
			
				foreach($this->modules as $module)
					if($module['panel'] instanceof QControl)
						$module['panel']->Render();
			
			?>
		</ul>

		</div>
		
		
<?php $this->RenderEnd(); ?>		
</body>
</html>
