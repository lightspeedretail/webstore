<?php
/**
This Example shows how to retrieve a list of your campaigns via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->campaigns();

if ($api->errorCode){
	echo "Unable to Pull list of Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
    echo sizeof($retval['total'])." Total Campaigns Matched.\n";
    echo sizeof($retval['data'])." Total Campaigns returned:\n";
    foreach($retval['data'] as $c){
        echo "Campaign Id: ".$c['id']." - ".$c['title']."\n";
        echo "\tStatus: ".$c['status']." - type = ".$c['type']."\n";
        echo "\tsent: ".$c['send_time']." to ".$c['emails_sent']." members\n";
    }
}

?>
