<?php
/**
This Example shows how to send Delete Campaigns via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->campaignDelete($campaignId);

if ($api->errorCode){
	echo "Unable to Delete Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Campaign Deleted!\n";
}

?>
