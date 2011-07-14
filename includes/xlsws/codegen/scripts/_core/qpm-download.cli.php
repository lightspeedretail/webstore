<?php
	/**
	 * Codegen Qcodo CLI file
	 * Part of the Qcodo Development Framework
	 * Copyright (c) 2005-2009, Quasidea Development, LLC
	 */

	// Setup the Parameters for qpm-download
	$objParameters = new QCliParameterProcessor('qpm-download', 'Qcodo Package Manager (QPM) Download and Install Tool v' . QCODO_VERSION);

	// Package Name is always required
	$objParameters->AddDefaultParameter('username/package_name', QCliParameterType::String, 'the username/package name pair of the QPM package you are wanting to download and install');

	// Optional Parameters include Username, Password, "Live" mode, and "Force" upload
	$objParameters->AddFlagParameter('l', 'live', 'actually perform the live download and install; by default, calling qpm-download will only *report* to you files that will be downloaded and installed; specify the "live" flag to actually perform the download and install');
	$objParameters->AddFlagParameter('f', 'force', 'force the download, even if the version of Qcodo used by the QPM author is different than what is currently installed here');
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
	
	try {
		$objQpm = new QPackageManagerDownload($strPackageName, $strUsername, $blnLive, $blnForce);
		$objQpm->PerformDownload();
	} catch (Exception $objExc) {
		print 'error: ' . trim($objExc->getMessage()) . "\r\n";
		exit(1);
	}
?>