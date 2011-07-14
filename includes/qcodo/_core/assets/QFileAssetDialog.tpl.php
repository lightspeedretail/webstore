<?php $_CONTROL->lblMessage->Render(); ?>
<p><?php $_CONTROL->flcFileAsset->Render(); ?></p>
<?php $_CONTROL->lblError->Render(); ?>
<p>
	<?php $_CONTROL->btnUpload->Render(); ?>
	<?php $_CONTROL->btnCancel->Render(); ?>
	<img src="<?php _p(__IMAGE_ASSETS__) ?>/spacer.png" width="150" height="1" alt=""/><?php $_CONTROL->objSpinner->Render(); ?>
</p>
