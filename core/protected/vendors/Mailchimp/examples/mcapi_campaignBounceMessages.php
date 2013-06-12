<?php
/**
This Example shows how to retrieve full Bounce Message data associated with a
campaign and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$msgs = $api->campaignBounceMessages($campaignId);

if ($api->errorCode){
	echo "Unable to run campaignBounceMessages()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
	echo "Total bounces:".$msgs['total']."\n";
	echo "Bounces returned:".sizeof($msgs['data'])."\n";
	foreach ($msgs['data'] as $msg){
        echo $msg['date']." - ".$msg['email']."\n";
        echo substr($msg['message'],0,150)."\n\n";
    }

}

?> 
