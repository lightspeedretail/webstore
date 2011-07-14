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
 * Basic template: Wish List (Gift Registry) Editing, Invitees list
 * 
 * 
 *
 */

?>

		<div class="registry_row">
			<p class="invitee_name"><?= $_FORM->RecNameColumn_Render($_ITEM) ?></p>

				<?php if($_ITEM->EmailSent): ?>
				<p class="invitee_email"><?= $_FORM->RecEmailColumn_Render($_ITEM) ?></p>
				<?php else: ?>
				<p><?= $_FORM->RecEmailColumn_Render($_ITEM) ?></p>
				<?php endif; ?>
				<div class="invitee_tasks">
					<?= $_FORM->MailColumn_Render($_ITEM) ?>
					<?= $_FORM->EditRecColumn_Render($_ITEM) ?>
					<?= $_FORM->DelRecColumn_Render($_ITEM) ?>
				</div>

		</div>
