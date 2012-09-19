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
 * template View full details of SRO
 *
 *
 *
 */


if ($this->sro): ?>



<div class="border padding_btm rounded" style="margin-top: -20px;">
	<div class="border_header">
		<p class="left"><?php _xt('Information') ?></p>
	</div>
	<p class="borderp">
		<?php _xt('Order ID') ?>: <?= $this->sro->LsId ?><br/>
		<?php _xt('Date') ?>: <?= $this->sro->DatetimeCre ?><br/>
		<?php _xt('Status') ?>: <?= $this->sro->Status ?></p>
</div>

<div class="border padding_btm rounded">
	<div class="border_header">
		<p class="left"><?php _xt('Problem') ?></p>
	</div>
	<ul class="table4" style="font-weight: bold"
	;>
	<li class="medium left"><?php _xt('Warranty') ?></li>
	<!--<li class="large"><?php _xt('Problem Description') ?></li>-->
	<li class="large"><?php _xt('Additional Items') ?></li>
	</ul>
	<div class="clear"></div>
	<ul class="table4">
		<li class="medium"><?= $this->sro->Warranty ?></li>
		<!--<li class="large"><?= $this->sro->ProblemDescription ?></li>-->
		<li class="large"><?= $this->sro->AdditionalItems ?></li>
	</ul>
	<br/>
</div>

<?php if (is_array($this->sro_repair) && (count($this->sro_repair) > 0)): ?>
	<div class="border padding_btm rounded">
		<div class="border_header">
			<p class="left">Repairs</p>
		</div>
		<ul class="table4" style="font-weight: bold"
		;>
		<li class="medium left"><?php _xt('Family') ?></li>
		<li class="large"><?php _xt('Description') ?></li>
		<li class="medium"><?php _xt('Purchase Date') ?></li>
		<li class="medium"><?php _xt('Serial Number') ?></li>
		</ul>
		<?php  foreach ($this->sro_repair as $repair): ?>

		<div class="clear"></div>
		<ul class="table4">
			<li class="medium left"><?= $repair->Family ?>&nbsp;</li>
			<li class="large"><?= $repair->Description ?>&nbsp;</li>
			<li class="medium"><?= $repair->PurchaseDate ?>&nbsp;</li>
			<li class="medium"><?= $repair->SerialNumber ?></li>
		</ul><br/>

		<?php endforeach; ?>
	</div>
	<?php endif; ?>

<?php if (is_array($this->sro_part) && (count($this->sro_part) > 0)): ?>
	<div class="border padding_btm rounded">
		<div class="border_header">
			<p class="left"><?php _xt('Parts required for repair') ?></p>
		</div>

		<ul class="table6" style="font-weight: bold;">
			<li class="medium">Product Code</li>
			<li class="large">Description</li>
			<li class="small">Price</li>
			<li class="small">Qty</li>
			<li class="small">Total</li>
		</ul>


		<?php foreach ($this->sro_part as $part): ?>

		<div class="clear"></div>
		<ul class="table6">
			<li class="medium"><?= $part->Code ?></li>
			<li class="large"><?= $part->Description ?> </li>
			<li class="small"><?= _xls_currency($part->Sell) ?></li>
			<li class="small"><?= $part->Qty ?></li>
			<li class="small"><?= _xls_currency($part->SellTotal) ?></li>
		</ul>

		<?php endforeach; ?>

	</div>
	<?php endif; ?>
<div class="border rounded">
	<div class="border_header">
		<p class="left">Problem Description</p>
	</div>
	<p class="borderp"><?= $this->sro->ProblemDescription ?></p>
</div>

<div class="border rounded">
	<div class="border_header">
		<p class="left">Notes</p>
	</div>
	<p class="borderp"><?php _xt('Printed Notes') ?>: <?= $this->sro->PrintedNotes ?><br/>
		<?php _xt('Work Performed') ?>: <?= $this->sro->WorkPerformed ?></p>
</div>

<?php endif; ?>
