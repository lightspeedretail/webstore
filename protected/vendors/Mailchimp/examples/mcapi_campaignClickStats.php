<?php
/**
This Example shows how to pull and iterate through a campaignClickStats call via
using the MCAPI wrapper class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$stats = $api->campaignClickStats($campaignId);
if ($api->errorCode){
    echo "Unable to load campaignClickStats()!\n\t";
    echo "Code=".$api->errorCode."\n\t";
    echo "Msg=".$api->errorMessage."\n";
} else {
    if (sizeof($stats)==0){
        echo "No stats for this campaign yet!\n";
    } else {
	    foreach($stats as $url=>$detail){
		    echo "URL: ".$url."\n";
		    echo "\tClicks = ".$detail['clicks']."\n";
		    echo "\tUnique Clicks = ".$detail['unique']."\n";
	    }
    }
}

?> 
