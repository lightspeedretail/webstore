<?php
	if ($_CONTROL->File) {
		if ($strUrl = $_CONTROL->GetWebUrl()) print('<a href="' . $strUrl . '" target="_blank">');
		$_CONTROL->imgFileIcon->Render();
		if ($strUrl) print ('</a>');
		print('<br/>');
		if ($_CONTROL->Enabled)
			$_CONTROL->btnDelete->Render();
	} else {
		if ($strUrl = $_CONTROL->GetWebUrl()) print('<a href="' . $strUrl . '" target="_blank">');
		$_CONTROL->imgFileIcon->Render();
		if ($strUrl) print ('</a>');
		print('<br/>');
		if ($_CONTROL->Enabled)
			$_CONTROL->btnUpload->Render();
	}
?>
<?php if ($_CONTROL->Enabled) $_CONTROL->dlgFileAsset->Render(); ?>