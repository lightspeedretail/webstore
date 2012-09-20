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
		echo '<div class="helperribbon"><img style="padding-right: 5px; width:44px; height:35px;" align="left" src="'.adminTemplate('css/images/questionmark.png').'"> '.$this->HelperRibbon.'</div>';

?>	


	<div class="title rounded"> 
		<div class="name" style="cursor:pointer;">Edit Product</div> 
		<div style="float:right">
			<?php $this->btnSave->Render('CssClass=button rounded'); ?><?php $this->btnCancel->Render('CssClass=button rounded'); ?></div> 
	</div>
<div style="height: 300px;">						
<?php

	if (!isset($this->intRowId)) {

		echo "<table width=400px style='margin-left: 30px;'><td><h3>Search for product code:</h3></td><td>";
		$this->ctlProductCode->Render();
		echo "</td><td>";
		$this->btnSearch->Render();
		echo "</td></table>";
		echo "<table width=400px style='margin-left: 30px;color:#ff0000'><td><b>";
		$this->ctlSearchResult->Render();
		echo "</b></td></table>";	
	}
	else
	{ ?>

	<h3 style="margin-left: 30px;margin-right:30px;">The product details are below. If there are products which have not been updated compared to the rest, they may be orphaned. You can delete individual products here, then reupload them from LightSpeed by unchecking/checking the Sell on Web checkbox and Reupload Photo in the product card. The next Update Store will reupload the products.</h3>
	<div id='editcontainer'>
	
		<div class="basic_row">
	 
	 	<div class="clear_float"></div>
	
	
			<div class="basic_row tableheader smallfont">
				<div class="colfield w70 smallfont">ID#</div>
				<div class="colfield w100 smallfont">Code</div>
				<div class="colfield w50 smallfont">Current</div>
				<div class="colfield w50 smallfont">Web</div>
				<div class="colfield w50 smallfont">Master?</div>
				<div class="colfield w50 smallfont">MasterId</div>
				<div class="colfield w50 smallfont">Inv</div>
				<div class="colfield w50 smallfont">iTtl</div>
				<div class="colfield w50 smallfont">iRsrv</div>
				<div class="colfield w50 smallfont">iAvail</div>
				<div class="colfield w100 smallfont">Last Modified</div>
				<div class="colfield w70 smallfont">Delete</div>

				<div class="clear_float"></div>
			</div> 



	<? $x=1; foreach ($this->arrProducts as $arrProduct) { ?>
		
			<div class="basic_row rowbg smallfont" id="row<? echo $arrProduct['Rowid']->Text; ?>">
				<div class="colfield w70 smallfont"><?php $arrProduct['Rowid']->Render(); ?></div>
				<div class="colfield w100 smallfont"><?php $arrProduct['OriginalCode']->Render(); ?></div>
				<div class="colfield w50 smallfont"><?php $arrProduct['Current']->Render(); ?>&nbsp;</div>
				<div class="colfield w50 smallfont"><?php $arrProduct['Web']->Render(); ?></div>
				<div class="colfield w50 smallfont"><?php $arrProduct['MasterModel']->Render(); ?>&nbsp;</div>
				<div class="colfield w50 smallfont"><?php $arrProduct['FkProductMasterId']->Render(); ?>&nbsp;</div>

				<div class="colfield w50 smallfont"><?php $arrProduct['OriginalInventory']->Render(); ?></div>
				<div class="colfield w50 smallfont"><?php $arrProduct['InventoryTotal']->Render(); ?></div>
				<div class="colfield w50 smallfont"><?php $arrProduct['InventoryReserved']->Render(); ?></div>
				<div class="colfield w50 smallfont"><?php $arrProduct['InventoryAvail']->Render(); ?></div>

				<div class="colfield w100 smallfont"><?php $arrProduct['Modified']->Render(); ?></div>
				<div class="colfield w70 smallfont"><?php $arrProduct['Delete']->Render(); ?></div>


				<div class="clear_float"></div>
			</div> 
		<?	
		
		
		}
	
	?>
			</div>			
		</div>
	</div>




	<? }

?>             
       
</div>
	</div>
								
				
		
<?php $this->RenderEnd(); ?>		
</body>
</html>
