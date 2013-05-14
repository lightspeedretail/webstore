<?php
/**
This Example shows how to immediately send a Campaign via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->campaignSendNow($campaignId);

if ($api->errorCode){
	echo "Unable to Send Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Campaign Sent!\n";
}

?>
