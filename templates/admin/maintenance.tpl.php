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
<script language="javascript">
	var prunning=0;
	var pinttimer=0;

	var urunning=0;
	var uinttimer=0;

	var irunning=0;
	var iinttimer=0;

    function startPhotoMigration(key) {
        document.getElementById('MigratePhotos').innerHTML = "<span style='font-size: 13pt'>Converting photos... <img src='assets/images/spinner_14.gif'/><br></span>";
        pinttimer=self.setInterval(function(){migratePhotos(key)},500);
        migratePhotos(key);
    }
    function migratePhotos(key)
    {
	    if (prunning==1) return;
        prunning=1;
		var strUrl = "xls_admin_js.php?item=migratephotos&" + key;
        $.get(strUrl, function(data){
            document.getElementById('MigratePhotos').innerHTML = data;
            prunning=0;
	        if (data.indexOf('All')>0)
	        clearInterval(pinttimer);
        });
    }

	function startUrlMigration(key) {
        document.getElementById('MigrateURL').innerHTML = "<span style='font-size: 13pt'>Converting URLs... <img src='assets/images/spinner_14.gif'/><br></span>";
        uinttimer=self.setInterval(function(){migrateURLs(key)},500);
        migrateURLs(key);
    }
    function migrateURLs(key)
    {
	    if (urunning==1) return;
        urunning=1;
		var strUrl = "xls_admin_js.php?item=migrateurls&" + key;
        $.get(strUrl, function(data){
            document.getElementById('MigrateURL').innerHTML = data;
            urunning=0;
	        if (data.indexOf('Done')>0)
	        clearInterval(uinttimer);
        });
    }

	function startInventoryCalc(key) {
        document.getElementById('RecalculateAvail').innerHTML = "<span style='font-size: 13pt'>Calculating available inventory... <img src='assets/images/spinner_14.gif'/><br></span>";
        iinttimer=self.setInterval(function(){InventoryCalc(key)},500);
        migrateURLs(key);
    }
    function InventoryCalc(key)
    {
	    if (irunning==1) return;
        irunning=1;
		var strUrl = "xls_admin_js.php?item=recalculateinventory&" + key;
        $.get(strUrl, function(data){
            document.getElementById('RecalculateAvail').innerHTML = data;
            irunning=0;
	        if (data.indexOf('recalculated')>0)
	        clearInterval(iinttimer);
        });
    }

</script>
<div id="options"  style="width:960px;" >
	<div class="content">	

<?php

if(isset($this->HelperRibbon)) 
	if (strlen($this->HelperRibbon)>0)
		echo '<div class="helperribbon"><img style="padding-right: 5px;width:44px; height:35px;" align="left" src="'.adminTemplate('css/images/questionmark.png').'"> '.$this->HelperRibbon.'</div>';

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
	</div>
</body>
</html>
