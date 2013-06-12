<?php
/**
This Example shows how to send Campaign Tests via the MCAPI class.

Note that a max of 25 of these may be sent
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$emails = array($my_email, $boss_man_email);
$retval = $api->campaignSendTest($campaignId, $emails);

if ($api->errorCode){
	echo "Unable to Send Test Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Campaign Tests Sent!\n";
}

?>
