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
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */	
	
/**
 * Decrypts backed up DB from admin panel. Will use current LS password for decryption unless secondary password supplied.
 *
 * 
 *
 */
	$_SERVER['REQUEST_URI'] = "";  	
	// save current dir
	$dir = getcwd();
	
	
	//go to parent for inclusion
	chdir("..");
	require_once('includes/prepend.inc.php');

	// come back to original dir
	chdir($dir);
	
	if(isset($argv) && (count($argv)>1))
	{
		
		$file = $argv[1];
		if(!file_exists($file))
			exit("$file does not exist!\n");

		echo _xls_key_decrypt(file_get_contents($file) , isset($argv[2])?md5($argv[2]):false);
					
	}
	else
	{
		echo "Usage: $argv[0] filename [LS Password]\n\n\nDecrypts backed up DB from admin panel. Will use current LS password for decryption unless secondary password supplied.\n";
	}
		

				
?>
