<?php
	// Tell QApplication that we are running as a Qcodo CLI Runner/Wrapper
	// so that it properly sets up QApplication::$ScriptFilename on QApplication::InitializeForCli()
	$_SERVER['QCODO_CLI_RUNNER'] = true;


	/*
	 * The following line should require() the prepend.inc.php file
	 * in your includes directory.  This can either be a relative
	 * or an absolute path, but it is recommended to use a relative
	 * path, especially for systems that use multiple instances of Qcodo.
	 * Feel free to modify as needed.
	 */
	require(dirname(__FILE__) . '/../../../prepend.inc.php');


	// Finally, require() the ScriptFilename that is being run/executed
	require(QApplication::$ScriptFilename);
?>
