<?php
	/**
	 * Codegen Qcodo CLI file
	 * Part of the Qcodo Development Framework
	 * Copyright (c) 2005-2009, Quasidea Development, LLC
	 */

	// Setup the Parameters for qpm-upload
	$objParameters = new QCliParameterProcessor('qpm-upload', 'Qcodo Package Manager (QPM) Uploader Tool v' . QCODO_VERSION);

	// Package Name is always required
	$objParameters->AddDefaultParameter('username/package_name', QCliParameterType::String, 'your username and the name of the QPM package you are wanting to upload');

	// Optional Parameters include Username, Password, "Live" mode, and "Force" upload
	$objParameters->AddNamedParameter('p', 'password', QCliParameterType::String, null, 'the qcodo.com password to use, or if not specified, it will use the information stored in the QPM Settings file');
	$objParameters->AddFlagParameter('l', 'live', 'actually perform the live upload; by default, calling qpm-upload will only *report* to you files that will be uploaded; specify the "live" flag to actually perform the upload');
	$objParameters->AddFlagParameter('f', 'force', 'force the upload, even if the most recent Qcodo version is more recent than what is currently installed here');
	$objParameters->AddNamedParameter('s', 'settings-path', QCliParameterType::Path, null, 'path to the QPM Settings XML file; defaults to ' . __DEVTOOLS_CLI__ . '/settings_qpm.xml');
	$objParameters->AddNamedParameter('n', 'notes', QCliParameterType::String, null, 'text of any notes to include with this QPM package');
	$objParameters->AddNamedParameter('N', 'notes-path', QCliParameterType::Path, null, 'path to textfile containing any notes to include with this QPM package; if both notes and notes-path are set, only the contents from notes-path will be included in the QPM package');
	$objParameters->Run();

	// Pull the Parameter Values
	$strPackageName = $objParameters->GetDefaultValue('username/package_name');
	$strUsername = null;
	if (($intPosition = strpos($strPackageName, '/')) !== false) {
		$strUsername = substr($strPackageName, 0, $intPosition);
		$strPackageName = substr($strPackageName, $intPosition+1);
	}
	$blnLive = $objParameters->GetValue('l');
	$blnForce = $objParameters->GetValue('f');
	$strPassword = $objParameters->GetValue('p');
	$strSettingsFilePath = $objParameters->GetValue('s');
	$strNotes = $objParameters->GetValue('n');
	$strNotesPath = $objParameters->GetValue('N');
		
	try {
		$objQpm = new QPackageManagerUpload($strPackageName, $strUsername, $strPassword, $blnLive, $blnForce, $strSettingsFilePath, $strNotes, $strNotesPath);
		$objQpm->PerformUpload();
	} catch (Exception $objExc) {
		print 'error: ' . trim($objExc->getMessage()) . "\r\n";
		exit(1);
	}
?>