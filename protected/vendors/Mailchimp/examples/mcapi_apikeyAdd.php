<?php
/**
This Example shows how to add a new API key using the MCAPI.php class and do
some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->apiKeyAdd($username, $password);

if ($api->errorCode){
	echo "Unable to load Add a New API Key()!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "New API Key:".$retval."\n";
}

?>
